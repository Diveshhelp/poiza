<?php

namespace App\Livewire;

use App\Models\PasswordManagers as Password;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Str;

class PasswordCollection extends Component
{
    use WithPagination;

    public $moduleTitle = "Password Manager";
    public $perPage = 10;
    
    public $url;
    public $password;
    public $note;
    public $category;
    public $status = 'active';
    public $uuid;
    
    public $isEditing = false;
    public $showPassword = false;
    public $showPasswordId = null;
    
    public $filterKeyword = '';
    public $filterCategory = '';

    // Enhanced security properties
    public $isAuthenticated = false;
    public $accessPassword = '';
    public $wrongPasswordAttempt = false;
    public $sessionDuration = 2; 
    public $formattedRemainingTime;
    public $sessionExpiryTime = null;
    public $sessionStartTime = null;
    
    // Add animation status variable
    public $timerWarning = false;

    protected $listeners = ['checkAuthenticationStatus', 'resetSession', 'extendSession'];
    protected $rules = [
        'url' => 'required',
        'password' => 'required|string|min:6|max:255',
        'note' => 'nullable|string|max:1000',
        'category' => 'required|string|max:50',
        'status' => 'required|in:active,archived'
    ];
    
    protected $securityRules = [
        'accessPassword' => 'required|string',
    ];
    
    protected $messages = [
        'url.required' => 'The URL or Email is required.',
        'password.required' => 'The password is required.',
        'password.min' => 'The password must be at least 6 characters.',
        'password.max' => 'The password cannot exceed 255 characters.',
        'note.max' => 'The note cannot exceed 1000 characters.',
        'category.required' => 'Please select a category.',
        'category.max' => 'The category cannot exceed 50 characters.',
        'status.required' => 'Please select a status.',
        'status.in' => 'Please select a valid status.',
        'accessPassword.required' => 'Please enter the access password.',
    ];

    public function mount()
    {
        $this->resetFields();
        $this->checkAuthentication();
       
    }
    
    // Updated helper method for updating session timers
    private function updateSessionTimers()
    {
        $authTimestamp = session('password_manager_auth_time');
        if ($authTimestamp) {
            $this->sessionStartTime = Carbon::parse($authTimestamp)->timestamp;
            $this->sessionExpiryTime = Carbon::parse($authTimestamp)
                ->addMinutes($this->sessionDuration)
                ->timestamp;
                
            // Calculate remaining time
            $currentTime = Carbon::now()->timestamp;
            $remainingSeconds = max(0, $this->sessionExpiryTime - $currentTime);
            
            // Set warning flag if less than 1 minute remains
            $this->timerWarning = $remainingSeconds < 60;
            
            // Dispatch timer update to Alpine.js
            $this->dispatch('updateTimer', [
                'expiryTime' => $this->sessionExpiryTime,
                'currentTime' => $currentTime,
                'isAuthenticated' => $this->isAuthenticated,
                'timerWarning' => $this->timerWarning
            ]);
        }
    }

    public function resetFields()
    {
        $this->url = '';
        $this->password = '';
        $this->note = '';
        $this->category = '';
        $this->status = 'active';
        $this->uuid = null;
        $this->isEditing = false;
    }
    
    // Improved authentication status check
    public function checkAuthenticationStatus()
    {
        $this->checkAuthentication();
        
        if ($this->isAuthenticated) {
            $authTimestamp = session('password_manager_auth_time');
            $expiryTime = Carbon::parse($authTimestamp)->addMinutes($this->sessionDuration);
            $now = Carbon::now();
            $remainingSeconds = $now->diffInSeconds($expiryTime, false);
            
            // Set warning flag if less than 1 minute remains
            $this->timerWarning = $remainingSeconds < 60;
            
            $this->dispatch('updateTimer', [
                'expiryTime' => $expiryTime->timestamp,
                'currentTime' => $now->timestamp,
                'isAuthenticated' => $this->isAuthenticated,
                'timerWarning' => $this->timerWarning
            ]);
            
            // If time is up, reset session
            if ($remainingSeconds <= 0) {
                $this->resetSession();
            }
        } else {
            $this->dispatch('sessionExpired');
        }
    }
    
    protected function initializeSession()
    {
        $now = Carbon::now();
        
        // Check if we have an existing auth time
        $authTime = session('password_manager_auth_time', $now);
        
        if ($authTime instanceof \DateTime) {
            $authTime = Carbon::instance($authTime);
        } elseif (is_string($authTime)) {
            $authTime = Carbon::parse($authTime);
        }
        
        $this->sessionStartTime = $authTime->timestamp;
        $this->sessionExpiryTime = $authTime->copy()->addMinutes($this->sessionDuration)->timestamp;
        
        // Calculate remaining time
        $currentTime = Carbon::now()->timestamp;
        $remainingSeconds = max(0, $this->sessionExpiryTime - $currentTime);
        
        // Set warning flag if less than 1 minute
        $this->timerWarning = $remainingSeconds < 60;
        
        // Format the time
        $minutes = floor($remainingSeconds / 60);
        $seconds = $remainingSeconds % 60;
        $this->formattedRemainingTime = sprintf('%02d:%02d', $minutes, $seconds);
    }
    
    // Added session extension method
    public function extendSession()
    {
        // Set new authentication time
        $now = Carbon::now();
        session(['password_manager_auth_time' => $now]);
       
        // Calculate new expiry time
        $this->sessionStartTime = $now->timestamp;
        $this->sessionExpiryTime = $now->copy()->addMinutes($this->sessionDuration)->timestamp;
        $this->timerWarning = false;
       
        $this->initializeSession();
       
        // Dispatch event to update Alpine.js timer
        $this->dispatch('sessionExtended', [
            'expiryTime' => $this->sessionExpiryTime,
            'currentTime' => $now->timestamp
        ]);
       
        // Confirm action to user
        session()->flash('message', 'Session extended for 2 minutes.');
    }
    
    // Reset session method
    public function resetSession()
    {
        session()->forget('password_manager_auth_time');
        $this->isAuthenticated = false;
        $this->dispatch('sessionExpired');
    }
    
    public function checkAuthentication()
    {
        $authTimestamp = session('password_manager_auth_time');
        
        if ($authTimestamp) {
            $expiryTime = Carbon::parse($authTimestamp)->addMinutes($this->sessionDuration);
            
            if (Carbon::now()->lt($expiryTime)) {
                $this->isAuthenticated = true;
                $this->updateSessionTimers();
            } else {
                session()->forget('password_manager_auth_time');
                $this->isAuthenticated = false;
                $this->dispatch('sessionExpired');
            }
        } else {
            $this->isAuthenticated = false;
        }
    }
    
    public function authenticate()
    {
        $this->validate($this->securityRules);
        
        $masterPassword = Auth::user()->security_code;
        
        if ($this->accessPassword === $masterPassword) {
            $now = Carbon::now();
            session(['password_manager_auth_time' => $now]);
            
            $this->sessionStartTime = $now->timestamp;
            $this->sessionExpiryTime = $now->addMinutes($this->sessionDuration)->timestamp;
            
            $this->isAuthenticated = true;
            $this->wrongPasswordAttempt = false;
            $this->timerWarning = false;
            $this->accessPassword = '';
            
            // Dispatch timer start event
            $this->dispatch('timerStarted', [
                'expiryTime' => $this->sessionExpiryTime,
                'currentTime' => Carbon::now()->timestamp
            ]);
        } else {
            $this->wrongPasswordAttempt = true;
        }
    }
    
    public function getRemainingTimeProperty()
    {
        $authTimestamp = session('password_manager_auth_time');
        
        if (!$authTimestamp) {
            return 0;
        }
        
        $expiryTime = Carbon::parse($authTimestamp)->addMinutes($this->sessionDuration);
        $remainingSeconds = Carbon::now()->diffInSeconds($expiryTime, false);
        
        return max(0, $remainingSeconds);
    }
    
    public function getFormattedRemainingTimeProperty()
    {
        $remainingSeconds = $this->remainingTime;
        $minutes = floor($remainingSeconds / 60);
        $seconds = $remainingSeconds % 60;
        
        return sprintf("%02d:%02d", $minutes, $seconds);
    }
    
    // Password management methods remain the same...
    public function savePassword()
    {
        $this->validate();

        if ($this->isEditing) {
            $password = Password::where('uuid', $this->uuid)->first();
            
            if (!$password) {
                session()->flash('error', 'Password not found.');
                return;
            }
            
            $password->url = $this->url;
            $password->password = Crypt::encryptString($this->password);
            $password->note = $this->note;
            $password->category = $this->category;
            $password->status = $this->status;
            $password->save();
            
            session()->flash('message', 'Password updated successfully!');
        } else {
            $password = new Password();
            $password->uuid = Str::uuid();
            $password->url = $this->url;
            $password->password = Crypt::encryptString($this->password);
            $password->note = $this->note;
            $password->category = $this->category;
            $password->status = $this->status;
            $password->team_id = Auth::user()->currentTeam->id;
            $password->user_id = Auth::id();
            $password->created_by = Auth::id();
            
            $password->save();
            
            session()->flash('message', 'Password saved successfully!');
        }
        
        $this->resetFields();
    }
    
    public function togglePasswordVisibility($id)
    {
        if ($this->showPasswordId === $id) {
            $this->showPasswordId = null;
        } else {
            $this->showPasswordId = $id;
        }
    }

    public function copyToClipboard($uuid)
    {
        $password = Password::where('uuid', $uuid)->first();
        if ($password) {
            return Crypt::decryptString($password->password);
        }
        return '';
    }

    public function editPassword($uuid)
    {
        $this->isEditing = true;
        $this->uuid = $uuid;
        
        $password = Password::where('uuid', $uuid)->first();
        if ($password) {
            $this->url = $password->url;
            $this->password = Crypt::decryptString($password->password);
            $this->note = $password->note;
            $this->category = $password->category;
            $this->status = $password->status;
        }
    }

    public function deletePassword($uuid)
    {
        $password = Password::where('uuid', $uuid)->first();
        
        if ($password) {
            $password->delete();
            session()->flash('message', 'Password deleted successfully!');
        } else {
            session()->flash('error', 'Password not found.');
        }
    }

    public function render()
    {
        if (!$this->isAuthenticated) {
            return view('livewire.password.password-collection', [
                'passwords' => collect(),
                'categories' => collect(),
                'sessionDuration' => $this->sessionDuration * 60,
                'sessionExpiryTime' => $this->sessionExpiryTime,
                'sessionStartTime' => $this->sessionStartTime,
                'timerWarning' => $this->timerWarning,
            ])->layout('layouts.app');
        }
        
        $query = Password::query()
            ->where('team_id', Auth::user()->currentTeam->id)
            ->where('user_id', Auth::user()->id);
            
        if ($this->filterKeyword) {
            $query->where(function($q) {
                $q->where('url', 'like', '%' . $this->filterKeyword . '%')
                  ->orWhere('note', 'like', '%' . $this->filterKeyword . '%')
                  ->orWhere('category', 'like', '%' . $this->filterKeyword . '%');
            });
        }
        
        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        $passwords = $query->orderBy('created_at', 'desc')->paginate($this->perPage);
        
        $categories = Password::where('team_id', Auth::user()->currentTeam->id)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('livewire.password.password-collection', [
            'passwords' => $passwords,
            'categories' => $categories,
            'sessionDuration' => $this->sessionDuration * 60,
            'sessionExpiryTime' => $this->sessionExpiryTime,
            'sessionStartTime' => $this->sessionStartTime,
            'timerWarning' => $this->timerWarning,
        ])->layout('layouts.app');
    }
}