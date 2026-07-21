<?php

namespace App\Services;

use App\Models\DropboxToken;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DropboxTokenService
{
    /**
     * Get a valid access token for Dropbox API
     *
     * @return string
     */
    public function getAccessToken()
    {
        $token = DropboxToken::latest()->first();
        
        // If no token exists or token is expired
        if (!$token || Carbon::parse($token->expires_at)->isPast()) {
            try {
                // Try to refresh if we have a refresh token
                if ($token && $token->refresh_token) {
                    $token = $this->refreshToken($token->refresh_token);
                } else {
                    // Generate a new token if refresh fails or no token exists
                    $token = $this->generateNewToken();
                }
            } catch (\Exception $e) {
                Log::error('Token refresh failed: ' . $e->getMessage());
                try {
                    // If refresh fails, try to generate a new token
                    $token = $this->generateNewToken();
                } catch (\Exception $innerException) {
                    Log::error('Token generation failed: ' . $innerException->getMessage());
                    throw new Exception('Unable to get a valid Dropbox token: ' . $innerException->getMessage());
                }
            }
        }
        
        return $token->access_token;
    }
    
    /**
     * Refresh an existing token using the refresh token
     *
     * @param string $refreshToken
     * @return DropboxToken
     * @throws Exception
     */

     
    public function refreshToken($refreshToken)
    {
        $client = new Client();
    
        try {
            $response = $client->post('https://api.dropbox.com/oauth2/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                    'client_id' => config('services.dropbox.app_key'),
                    'client_secret' => config('services.dropbox.app_secret'),
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            Log::info('Dropbox token refreshed successfully', [
                'expires_in' => $data['expires_in'] ?? 'not provided'
            ]);
            
            // Create or update token in database
            $token = new DropboxToken();
            $token->access_token = $data['access_token'];
            // Dropbox may not return a new refresh token, so keep the old one if not provided
            $token->refresh_token = $data['refresh_token'] ?? $refreshToken;
            // Set expiration time based on response or default to 4 hours
            $token->expires_at = now()->addSeconds($data['expires_in'] ?? 14400);
            $token->save();
            
            return $token;
        } catch (RequestException $e) {
            $errorBody = $e->hasResponse() ? (string) $e->getResponse()->getBody() : null;
            $errorJson = $errorBody ? json_decode($errorBody, true) : null;
            $errorMessage = $errorJson ? json_encode($errorJson) : $e->getMessage();
            
            Log::error('Dropbox token refresh failed', [
                'error' => $errorMessage,
                'status' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : 'no response'
            ]);
            
            throw new Exception('Failed to refresh token: ' . $errorMessage);
        }
    }
    
    /**
     * Generate a new token using fallback credentials
     *
     * @return DropboxToken
     * @throws Exception
     */
    public function generateNewToken()
    {
        Log::info('Attempting to generate new Dropbox token');
        
        // For Dropbox, we need to use pre-generated tokens stored in config
        // since automatic token generation requires user interaction
        $accessToken = config('services.dropbox.token');
        $refreshToken = config('services.dropbox.refresh_token');
        
        if (!$accessToken || !$refreshToken) {
            Log::error('Cannot generate Dropbox token: No fallback tokens configured');
            throw new Exception('Cannot generate token automatically. No fallback tokens configured.');
        }
        
        Log::info('Creating new token record from fallback tokens');
        
        // Create a new token record with the fallback tokens
        return DropboxToken::create([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_at' => now()->addHours(4) // Set a reasonable expiration time
        ]);
    }
}