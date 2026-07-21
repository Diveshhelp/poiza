<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    public $user;
    public $team_id;
    public function __construct()
    {
        
    }
    public function login(Request $request)
    {
        $request->validate([
            'security_code' => 'required|string',
            'email' => 'required|email'
        ]);
        // $user = User::whereEncrypted('security_code', $request->security_code)->first();
        $user = User::whereEncrypted('security_code', $request->security_code)->where('email', $request->email)->first();

        if ($user) {
            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        }
        
        return response()->json([
            'status' => 'error',
            'message' => 'The provided security code is incorrect.'
        ], 401);
    }

    public function getUserInfo(Request $request)
    {
        // The user is already authenticated through the Sanctum middleware
        $user = $request->user();
        
        // You can customize what user data you want to return
        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                // Add any other user fields you want to expose
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ]);
    }
    public function logout(Request $request)
    {
        // Check if user is authenticated
        if ($request->user()) {
            // Revoke all tokens
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
        
        return response()->json([
            'message' => 'No authenticated user found'
        ], 401);
    }
}