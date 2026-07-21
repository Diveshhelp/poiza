<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserEdit extends Component
{
    public $user;
    public $name;
    public $email;
    public $security_code;
    public $current_password;
    public $new_password;
    public $password_confirmation;
    // public $showPasswordFields = false;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'security_code' => 'required|string|max:255',
    ];

    public function mount($userId)
    {
        $this->user = User::findOrFail($userId);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->security_code = $this->user->security_code;
    }

    public function updateProfile()
    {
        $this->validate();

        // Additional validation for password if being changed
        if ($this->current_password) {
            $this->validate([
                'current_password' => 'required',
                'new_password' => ['required', Password::defaults()],
                'password_confirmation' => 'required|same:new_password',
            ]);

            if (!Hash::check($this->current_password, $this->user->password)) {
                $this->addError('current_password', 'The current password is incorrect.');
                return;
            }
        }

        // Update user profile
        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'security_code' => $this->security_code,
        ]);

        // Update password if provided
        if ($this->new_password) {
            $this->user->update([
                'password' => Hash::make($this->new_password)
            ]);
        }

        $this->dispatch('notify-success', 'Profile updated successfully');
    }

    public function render()
    {
        return view('livewire.user-edit')->layout('layouts.app');
    }
}