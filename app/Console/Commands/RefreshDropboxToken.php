<?php

namespace App\Console\Commands;

use App\Models\DropboxToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshDropboxToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dropbox:refresh-token {--force : Force token refresh regardless of expiration}
                            {--debug : Show detailed debug information}
                            {--use-env : Use environment variables instead of database token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Dropbox access token and verify connection';

    /**
     * Connection error message if any.
     *
     * @var string|null
     */
    protected $connectionError = null;

    /**
     * Token information.
     *
     * @var array
     */
    protected $tokenInfo = [];

    /**
     * Dropbox account information.
     *
     * @var array|null
     */
    protected $dropboxAccountInfo = null;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting Dropbox token refresh process...');

        $useEnv = $this->option('use-env');
        $forceRefresh = $this->option('force');
        $debug = $this->option('debug');

        if ($useEnv) {
            return $this->handleEnvironmentTokenRefresh($debug);
        } else {
            return $this->handleDatabaseTokenRefresh($forceRefresh, $debug);
        }
    }

    /**
     * Handle token refresh using environment variables.
     *
     * @param bool $debug Whether to show debug information
     * @return int Command exit code
     */
    protected function handleEnvironmentTokenRefresh($debug)
    {
        $appKey = env('DROPBOX_APP_KEY');
        $appSecret = env('DROPBOX_APP_SECRET');
        $refreshToken = env('DROPBOX_REFRESH_TOKEN');

        if (!$appKey || !$appSecret || !$refreshToken) {
            $this->error('Missing required environment variables: DROPBOX_APP_KEY, DROPBOX_APP_SECRET, or DROPBOX_REFRESH_TOKEN');
            return Command::FAILURE;
        }

        $this->info('Using token from environment variables');

        try {
            $response = Http::timeout(15)->asForm()->post('https://api.dropboxapi.com/oauth2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $appKey,
                'client_secret' => $appSecret,
            ]);

            if (!$response->successful()) {
                $errorDetails = $response->json();
                $errorMessage = $errorDetails['error_description'] ?? $errorDetails['error'] ?? 'Unknown error';
                $this->error("Failed to refresh token: {$errorMessage} (HTTP {$response->status()})");
                
                if ($debug) {
                    $this->line('Full error response:');
                    $this->line(json_encode($errorDetails, JSON_PRETTY_PRINT));
                }
                
                Log::error('Dropbox token refresh failed', [
                    'status' => $response->status(),
                    'error' => $errorDetails,
                ]);
                
                return Command::FAILURE;
            }

            $tokenData = $response->json();
            
            $this->info('Token refreshed successfully');
            $this->info('Access token expires in: ' . ($tokenData['expires_in'] ?? 'Unknown') . ' seconds');
            
            if ($debug) {
                $this->line('Response data:');
                $this->line(json_encode(array_diff_key($tokenData, ['access_token' => true]), JSON_PRETTY_PRINT));
            }

            // Verify the token works
            $this->info('Verifying token by fetching account info...');
            if ($this->verifyTokenWithAccountInfo($tokenData['access_token'], $debug)) {
                $this->info('✓ Token verified successfully');
                return Command::SUCCESS;
            } else {
                $this->error('✗ Token verification failed');
                return Command::FAILURE;
            }
            
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            
            if ($debug) {
                $this->line('Exception details:');
                $this->line($e->getTraceAsString());
            }
            
            Log::error('Dropbox token refresh exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return Command::FAILURE;
        }
    }

    /**
     * Handle token refresh using database record.
     *
     * @param bool $forceRefresh Whether to force token refresh
     * @param bool $debug Whether to show debug information
     * @return int Command exit code
     */
    protected function handleDatabaseTokenRefresh($forceRefresh, $debug)
    {
        $tokenRecord = DropboxToken::latest()->first();
        
        if (!$tokenRecord) {
            $this->error('No Dropbox token found in database');
            return Command::FAILURE;
        }

        if (!$tokenRecord->refresh_token) {
            $this->error('No refresh token available in database record');
            return Command::FAILURE;
        }

        // Check if token needs refreshing
        $shouldRefresh = $forceRefresh;
        
        if (!$shouldRefresh && $tokenRecord->expires_at) {
            $timeUntilExpiry = now()->diffInMinutes($tokenRecord->expires_at, false);
            $this->info("Current token expires in {$timeUntilExpiry} minutes");
            
            // Refresh if token expires in less than 5 minutes
            $shouldRefresh = $timeUntilExpiry < 5;
            
            if (!$shouldRefresh && !$forceRefresh) {
                $this->info('Current token is still valid, use --force to refresh anyway');
                return Command::SUCCESS;
            }
        }

        $this->info('Refreshing token...');

        try {
            $response = Http::asForm()->post('https://api.dropboxapi.com/oauth2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $tokenRecord->refresh_token,
                'client_id' => config('services.dropbox.client_id'),
                'client_secret' => config('services.dropbox.app_secret'),
            ]);


            if (!$response->successful()) {
                $errorDetails = $response->json();
                $errorMessage = $errorDetails['error_description'] ?? $errorDetails['error'] ?? 'Unknown error';
                $this->error("Failed to refresh token: {$errorMessage} (HTTP {$response->status()})");
                
                if ($debug) {
                    $this->line('Full error response:');
                    $this->line(json_encode($errorDetails, JSON_PRETTY_PRINT));
                }
                
                Log::error('Dropbox token refresh failed', [
                    'status' => $response->status(),
                    'error' => $errorDetails,
                ]);
                
                return Command::FAILURE;
            }

            $tokenData = $response->json();
            
            // Update access token in database
            $tokenRecord->access_token = $tokenData['access_token'];
            
            if (isset($tokenData['expires_in'])) {
                // Add a small buffer (30 seconds) to account for processing time
                $tokenRecord->expires_at = now()->addSeconds($tokenData['expires_in'] - 30);
            }
            
            // Update refresh token if provided (some OAuth providers refresh the refresh token too)
            if (isset($tokenData['refresh_token'])) {
                $tokenRecord->refresh_token = $tokenData['refresh_token'];
                $this->info('Received and updated refresh token');
            }
            
            $tokenRecord->save();
            
            $this->info('Token refreshed and saved to database');
            $this->info('Access token expires at: ' . ($tokenRecord->expires_at ? $tokenRecord->expires_at->format('Y-m-d H:i:s') : 'Never'));
            
            // Update token info for display
            $this->tokenInfo = [
                'connected_since' => $tokenRecord->created_at->format('Y-m-d H:i:s'),
                'last_refreshed' => now()->format('Y-m-d H:i:s'),
                'access_token_expires' => $tokenRecord->expires_at 
                    ? $tokenRecord->expires_at->format('Y-m-d H:i:s')
                    : 'Never',
            ];
            
            if ($debug) {
                $this->line('Token info:');
                $this->table(['Key', 'Value'], collect($this->tokenInfo)->map(function($value, $key) {
                    return [$key, $value];
                })->toArray());
            }
            
            // Verify the token works
            $this->info('Verifying token by fetching account info...');
            if ($this->verifyTokenWithAccountInfo($tokenData['access_token'], $debug)) {
                $this->info('✓ Token verified successfully');
                return Command::SUCCESS;
            } else {
                $this->error('✗ Token verification failed');
                return Command::FAILURE;
            }
            
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            
            if ($debug) {
                $this->line('Exception details:');
                $this->line($e->getTraceAsString());
            }
            
            Log::error('Dropbox token refresh exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return Command::FAILURE;
        }
    }

    /**
     * Verify token by fetching account info.
     *
     * @param string $accessToken Access token to verify
     * @param bool $debug Whether to show debug information
     * @return bool Whether token verification was successful
     */
    protected function verifyTokenWithAccountInfo($accessToken, $debug = false)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.dropboxapi.com/2/users/get_current_account', null);

            
            if (!$response->successful()) {
                $errorDetails = $response->json();
                $errorSummary = $errorDetails['error_summary'] ?? 'Unknown error';
                $this->error("Failed to fetch account info: {$errorSummary} (HTTP {$response->status()})");
                
                if ($debug) {
                    $this->line('Full error response:');
                    $this->line(json_encode($errorDetails, JSON_PRETTY_PRINT));
                }
                
                return false;
            }
            
            $this->dropboxAccountInfo = $response->json();
            
            if ($debug) {
                $this->info('Connected to Dropbox account:');
                $accountName = $this->dropboxAccountInfo['name']['display_name'] ?? 'Unknown';
                $accountEmail = $this->dropboxAccountInfo['email'] ?? 'Unknown';
                $accountType = $this->dropboxAccountInfo['account_type']['.tag'] ?? 'Unknown';
                
                $this->line("Name: {$accountName}");
                $this->line("Email: {$accountEmail}");
                $this->line("Account type: {$accountType}");
            } else {
                $accountName = $this->dropboxAccountInfo['name']['display_name'] ?? 'Unknown';
                $this->info("Connected to Dropbox account: {$accountName}");
            }
            
            return true;
            
        } catch (\Exception $e) {
            $this->error('Failed to fetch account info: ' . $e->getMessage());
            
            if ($debug) {
                $this->line('Exception details:');
                $this->line($e->getTraceAsString());
            }
            
            Log::error('Dropbox account info exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
}