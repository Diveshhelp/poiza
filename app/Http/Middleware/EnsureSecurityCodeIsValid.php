<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSecurityCodeIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $user = auth()->user();
         if ($user && $user->security_code && session('security_verified')==false) {
            session(['intended_url' => $request->url()]);
            return response()->view('layouts.app', [
                'showSecurityModal' => true
            ]);
            
        }else if ($user && $user->security_code==""){
            return response()->view('layouts.app', [
                'showSecurityModal' => true
            ]);
        }
        if (auth()->check() && !auth()->user()->password) {
            return redirect()->route('my-password-reset');
        }
        
        return $next($request);
    }
    
}