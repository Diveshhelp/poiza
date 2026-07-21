<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;

class MyPasswordResetController extends Controller
{
    public function showResetForm()
    {
        return view('auth.my-password-reset');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        if ($user->password) {
            return back()->withErrors(['email' => 'This account already has a password set. Please use the regular login.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->route('login')
            ->with('status', 'Password has been set successfully. Please login with your new password.');
    }
}