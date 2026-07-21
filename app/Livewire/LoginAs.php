<?php
namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginAs extends Component
{
    public function mount($uuid,$token)
    {
   
        $code = env('DOCMEY_API_KEY');
        
        // Verify the security token
        if ($uuid === $code) {
           
            // Find user with role containing '1'
            $user = User::where("unsubscribe_token",$token)->first();
    
            if ($user) {
                Auth::login($user);
                session(['security_verified' => true]);
                session(['security_code' => $user->security_code]);
                session(['session_user_id' => $user->id]);
                session(['session_name' => $user->name]);
                session(['session_email' => $user->email]);
                session(['security_batch_code' => $user->id]);
                session(['security_verified_at' => now()]);
                session(['does_secrate_login' => true]);
                return redirect()->to('dashboard');
            }
            
            // User not found handling
            abort(404, 'User not found');
        }
        
        // Invalid token handling
        abort(403, 'Unauthorized');
    }
    
    public function render()
    {
        return <<<'blade'
            <div>
                <p>Processing login...</p>
            </div>
        blade;
    }
}