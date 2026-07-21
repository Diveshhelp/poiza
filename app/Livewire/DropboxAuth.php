<?php

namespace App\Livewire;

use App\Models\DropboxToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\Rule;

class DropboxAuth extends Component
{
    public $authUrl = '';
    public $showAuthUrlSection = true;
    public $showCodeSection = false;
    public $showSuccessSection = false;
    public $showTokenDetails = false;
    
    #[Rule('required|string')]
    public $authCode = '';
    
    public $tokenInfo = [];
    public $isConnected = false;
    public $connectionError = null;
    public $dropboxAccountInfo = null;

    public function mount()
    {
        // Check if user already has tokens
        $this->checkExistingConnection();
    }

    public function checkExistingConnection()
    {
        $tokenRecord = DropboxToken::first();
        
        if ($tokenRecord && $tokenRecord->refresh_token) {
            $this->isConnected = true;
            $this->showAuthUrlSection = false;
            $this->showCodeSection = false;
            $this->showSuccessSection = true;
            
            // Display token info
            $this->tokenInfo = [
                'connected_since' => $tokenRecord->created_at->format('M d, Y H:i'),
                'last_refreshed' => $tokenRecord->updated_at->format('M d, Y H:i'),
                'access_token_expires' => $tokenRecord->expires_at ? $tokenRecord->expires_at->format('M d, Y H:i') : 'Unknown'
            ];
        } else {
            $this->isConnected = false;
            $this->getAuthorizationUrl();
        }
    }

    public function getAuthorizationUrl()
    {
        $query = http_build_query([
            'client_id' => config('services.dropbox.client_id'),
            'response_type' => 'code',
            'redirect_uri' => config('services.dropbox.redirect_url'),
            'token_access_type' => 'offline', // Request refresh token
            'force_reapprove' => 'true'       // Force consent to ensure refresh token is issued
        ]);

        $this->authUrl = "https://www.dropbox.com/oauth2/authorize?{$query}";
        $this->showAuthUrlSection = true;
        $this->showCodeSection = true;
    }

    public function proceedToCodeEntry()
    {
        $this->showAuthUrlSection = false;
        $this->showCodeSection = true;
    }

    public function exchangeCodeForToken()
    {
        $this->validate();
        
        try {
            // Exchange the code for tokens
            $response = Http::asForm()->post('https://api.dropboxapi.com/oauth2/token', [
                'code' => $this->authCode,
                'grant_type' => 'authorization_code',
                'client_id' => config('services.dropbox.client_id'),
                'client_secret' => config('services.dropbox.client_secret'),
                'redirect_uri' => config('services.dropbox.redirect'),
            ]);

            if (!$response->successful()) {
                $this->connectionError = 'Failed to obtain access token: ' . 
                    ($response->json()['error_description'] ?? 'Unknown error');
                return;
            }

            $tokenData = $response->json();
            
            // Verify that we received a refresh token
            if (!isset($tokenData['refresh_token'])) {
                $this->connectionError = 'No refresh token received. Make sure you approved the authorization.';
                return;
            }

            // Store tokens in database
            $this->storeTokens($tokenData);
            
            // Fetch account info to verify connection
            $this->fetchAccountInfo($tokenData['access_token']);

            // Update UI
            $this->showCodeSection = false;
            $this->showSuccessSection = true;
            $this->isConnected = true;
            
            // Reset error if any
            $this->connectionError = null;
            
            // Update token info
            $this->tokenInfo = [
                'connected_since' => now()->format('M d, Y H:i'),
                'access_token_expires' => isset($tokenData['expires_in']) 
                    ? now()->addSeconds($tokenData['expires_in'])->format('M d, Y H:i') 
                    : 'Unknown'
            ];
            
            session()->flash('message', 'Successfully connected to Dropbox!');
            
        } catch (\Exception $e) {
            $this->connectionError = 'An error occurred: ' . $e->getMessage();
        }
    }

    public function storeTokens(array $tokenData)
    {
        DropboxToken::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'] ?? null,
                'expires_at' => isset($tokenData['expires_in']) 
                    ? now()->addSeconds($tokenData['expires_in']) 
                    : null,
                'account_id' => $tokenData['account_id'] ?? null
            ]
        );
    }
    
    public function refreshToken()
    {
        $tokenRecord = DropboxToken::latest()->first();
        
        if (!$tokenRecord || !$tokenRecord->refresh_token) {
            $this->connectionError = 'No refresh token available';
            return;
        }

        try {
            $response = Http::asForm()->post('https://api.dropboxapi.com/oauth2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $tokenRecord->refresh_token,
                'client_id' => config('services.dropbox.client_id'),
                'client_secret' => config('services.dropbox.client_secret'),
            ]);

            if (!$response->successful()) {
                $this->connectionError = 'Failed to refresh token: ' . 
                    ($response->json()['error_description'] ?? 'Unknown error');
                return;
            }

            $tokenData = $response->json();
            
            // Update access token in database
            $tokenRecord->access_token = $tokenData['access_token'];
            $tokenRecord->expires_at = isset($tokenData['expires_in']) 
                ? now()->addSeconds($tokenData['expires_in']) 
                : null;
            $tokenRecord->save();
            
            // Update token info
            $this->tokenInfo = [
                'connected_since' => $tokenRecord->created_at->format('M d, Y H:i'),
                'last_refreshed' => now()->format('M d, Y H:i'),
                'access_token_expires' => isset($tokenData['expires_in']) 
                    ? now()->addSeconds($tokenData['expires_in'])->format('M d, Y H:i') 
                    : 'Unknown'
            ];
            
            // Fetch account info to verify connection
            $this->fetchAccountInfo($tokenData['access_token']);
            
            session()->flash('message', 'Token refreshed successfully!');
            
        } catch (\Exception $e) {
            $this->connectionError = 'An error occurred: ' . $e->getMessage();
        }
    }
    
    public function fetchAccountInfo($accessToken = null)
    {
        if (!$accessToken) {
            $tokenRecord = DropboxToken::latest()->first();
            if (!$tokenRecord) {
                $this->connectionError = 'No access token available';
                return;
            }
            $accessToken = $tokenRecord->access_token;
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post('https://api.dropboxapi.com/2/users/get_current_account');
            
            if ($response->successful()) {
                $this->dropboxAccountInfo = $response->json();
            } else {
                $this->connectionError = 'Failed to fetch account info: ' . 
                    ($response->json()['error_summary'] ?? 'Unknown error');
            }
        } catch (\Exception $e) {
            $this->connectionError = 'Failed to fetch account info: ' . $e->getMessage();
        }
    }
    
    public function disconnectDropbox()
    {
        $tokenRecord = DropboxToken::latest()->first();
        
        if ($tokenRecord) {
            // Revoke the token on Dropbox side
            try {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . $tokenRecord->access_token
                ])->post('https://api.dropboxapi.com/2/auth/token/revoke');
                
                // Delete the token from our database
                $tokenRecord->delete();
                
                // Reset UI state
                $this->isConnected = false;
                $this->showAuthUrlSection = true;
                $this->showCodeSection = false;
                $this->showSuccessSection = false;
                $this->dropboxAccountInfo = null;
                $this->tokenInfo = [];
                $this->connectionError = null;
                
                // Generate a new authorization URL
                $this->getAuthorizationUrl();
                
                session()->flash('message', 'Successfully disconnected from Dropbox');
                
            } catch (\Exception $e) {
                $this->connectionError = 'Error disconnecting: ' . $e->getMessage();
            }
        }
    }
    
    public function toggleTokenDetails()
    {
        $this->showTokenDetails = !$this->showTokenDetails;
    }

    public function render()
    {
        return view('livewire.dropbox-auth')->layout('layouts.app');
    }
}