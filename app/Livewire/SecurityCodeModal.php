<?php
namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;

class SecurityCodeModal extends Component
{
    public bool $show = false;
    
    #[Rule('required')]
    public string $code = '';
    
    #[Rule('required|email|exists:users,email')]
    public string $email= '';
    public function mount($show = false)
    {
        $this->show = $show;
        // $this->show = false;
    }
    
    public function verifyCode()
    {
        
        $this->validate();
        // Find the user with the matching security code
        if(session('does_secrate_login')==true){
            
            $user = User::where('email', session('session_email'))->first();
        }else{
            $user = User::whereEncrypted('security_code', $this->code)->where('email', $this->email)->first();
        }
        if ($user) {
            // Generate session data
            session(['security_verified' => true]);
            session(['security_code' => $user->security_code]);
            session(['session_user_id' => $user->id]);
            session(['session_name' => $user->name]);
            session(['session_email' => $user->email]);
            session(['security_batch_code' => $user->id]);
            session(['security_verified_at' => now()]);
            
            // Log the successful verification and login
            \Log::info("User {$user->id} authenticated via security code");
            
            // Close the modal
            $this->show = false;
            
            // Redirect to dashboard or intended URL
            $intendedUrl = session('intended_url', route('dashboard'));
            return $this->redirect($intendedUrl);
        }
        
        // If no user found with that code
        $this->addError('code', 'Invalid security code');
        $this->code = ''; // Clear invalid input
    }
    
    public function render()
    {
        return view('livewire.security-code-verification');
    }
}