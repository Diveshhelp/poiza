<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UserUnsubscribe extends Component
{
    public $email;
    public $token;
    public $user;
    public $status = 'processing'; // processing, success, error
    public $message = '';
    public $resubscribeEmail = '';
    public $resubscribeStatus = null; // success, error
    public $resubscribeMessage = '';

    public function mount($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
        
        $this->processUnsubscribe();
    }
    
    public function processUnsubscribe()
    {
        // Find the user
        $this->user = User::where('email', $this->email)->first();
        
        // Verify the user and token
        if (!$this->user || $this->user->unsubscribe_token !== $this->token) {
            $this->status = 'error';
            $this->message = 'Invalid unsubscribe link. Please contact customer support.';
            Log::warning("Invalid unsubscribe attempt for email: {$this->email}");
            return;
        }
        
        // Update the user's preferences
        $this->user->update([
            'subscribed_to_emails' => false,
            'unsubscribed_at' => now()
        ]);
        
        // Log the unsubscribe action
        Log::info("User unsubscribed: {$this->email}");
        
        $this->status = 'success';
        $this->message = 'You have been successfully unsubscribed from our email communications.';
        $this->resubscribeEmail = $this->email;
    }
    
    public function resubscribe()
    {
        // Find the user by email
        $user = User::where('email', $this->resubscribeEmail)->first();
        
        if (!$user) {
            $this->resubscribeStatus = 'error';
            $this->resubscribeMessage = 'Email address not found.';
            return;
        }
        
        $user->update([
            'subscribed_to_emails' => true,
            'unsubscribed_at' => null
        ]);
        
        $this->resubscribeStatus = 'success';
        $this->resubscribeMessage = 'You have been successfully resubscribed to our emails.';
        
        Log::info("User resubscribed: {$this->resubscribeEmail}");
    }
    
    public function render()
    {
        return view('livewire.user-unsubscribe')
            ->layout('layouts.guest', ['title' => 'Email Subscription Management']);
    }
}