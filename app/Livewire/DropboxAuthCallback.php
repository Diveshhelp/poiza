<?php

namespace App\Livewire;

use App\Models\DropboxToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\Rule;

class DropboxAuthCallback extends Component
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
        $this->authCode= $_REQUEST['code'];

        try {
            // Exchange the code for tokens
            $response = Http::asForm()->post('https://api.dropboxapi.com/oauth2/token', [
                'code' => $this->authCode,
                'grant_type' => 'authorization_code',
                'client_id' => config('services.dropbox.client_id'),
                'client_secret' => config('services.dropbox.app_secret'),
                'redirect_uri' =>  config('services.dropbox.redirect_url'),
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
            redirect('dropbox');
            
        } catch (\Exception $e) {
            $this->connectionError = 'An error occurred: ' . $e->getMessage();
        }
      
    }

    public function storeTokens(array $tokenData)
    {
        DropboxToken::create(
            [
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'] ?? null,
                'expires_at' => isset($tokenData['expires_in']) 
                    ? now()->addSeconds($tokenData['expires_in']) 
                    : null,
            ]
        );
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
}