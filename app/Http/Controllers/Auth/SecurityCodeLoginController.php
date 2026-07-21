<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LocationTrace;
use App\Models\LoginLog;
use App\Models\User;
use Cookie;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class SecurityCodeLoginController extends Controller
{
    public function showLoginForm()
    {
        
        // Get email from cookie
        $email = request()->cookie('user_email') ? request()->cookie('user_email') : '';
        
        // Pass it to the view
        return view('auth.login-by-code', ['email' => $email]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'security_code' => 'required|string',
            'email' => 'required|string|email',
        ]);

        $user = User::whereEncrypted('security_code', $request->security_code)->where('email', $request->email)->first();

        if ($user) {
            //Test Login
            //info@deltantec.com
            //Test@12345
            Cookie::queue('user_email', $request->email, 43200);
            Auth::login($user);
            $this->logData($request);
            session(['security_verified' => true]);

            LoginLog::create([
                'user_id' => $user->id,
                'logged_in_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'security_code' => 'The provided security code is incorrect.'
        ]);
    }


    public function logData(Request $request){
        
        $locationData=$this->getLocationFromIp($request->ip());
        LocationTrace::create([
            // IP and location data
            'user_id'=>Auth::user()->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'country' => $locationData['country_code'],
            'city' => $locationData['city'],
            'region' => $locationData['region'],
            'location_data' => json_encode($locationData),
        ]);
        return true;
    }
   
    public function getLocationFromIp($ipAddress)
    {
        // Skip lookup for local/private IPs
        if ($this->isPrivateIp($ipAddress) || $ipAddress == '127.0.0.1') {
            return [
                'country' => null,
                'country_code' => null,
                'region' => null,
                'city' => null,
                'latitude' => null,
                'longitude' => null,
                'timezone' => null,
                'location_data' => null
            ];
        }

        try {
            // Option 1: Use ipinfo.io service (requires API key for production use)
            // $response = Http::get("https://ipinfo.io/{$ipAddress}/json?token=" . config('services.ipinfo.token'));
            
            // Option 2: Use ip-api.com (free for non-commercial use, no API key required)
            $response = Http::get("http://ip-api.com/json/{$ipAddress}?fields=status,message,country,countryCode,region,regionName,city,lat,lon,timezone,isp,org,query");
            
            if ($response->successful() && $response->json('status') !== 'fail') {
                $data = $response->json();
                
                return [
                    'country' => $data['country'] ?? null,
                    'country_code' => $data['countryCode'] ?? null,
                    'region' => $data['regionName'] ?? null,
                    'city' => $data['city'] ?? null,
                    'latitude' => $data['lat'] ?? null,
                    'longitude' => $data['lon'] ?? null,
                    'timezone' => $data['timezone'] ?? null,
                    'location_data' => $data // Store the complete response
                ];
            }
            
            return [
                'country' => null,
                'country_code' => null,
                'region' => null,
                'city' => null,
                'latitude' => null,
                'longitude' => null,
                'timezone' => null,
                'location_data' => null
            ];
        } catch (\Exception $e) {
            Log::error('IP Geolocation error: ' . $e->getMessage());
            
            return [
                'country' => null,
                'country_code' => null,
                'region' => null,
                'city' => null,
                'latitude' => null,
                'longitude' => null,
                'timezone' => null,
                'location_data' => null
            ];
        }
    }
    
    /**
     * Check if IP address is private
     * 
     * @param string $ipAddress
     * @return bool
     */
    private function isPrivateIp($ipAddress)
    {
        $privateRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
            'fd00::/8',    // IPv6 private range
            'fe80::/10',   // IPv6 link-local addresses
        ];
        
        foreach ($privateRanges as $range) {
            if ($this->ipInRange($ipAddress, $range)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if IP is in range
     * 
     * @param string $ip
     * @param string $range
     * @return bool
     */
    private function ipInRange($ip, $range)
    {
        // Simple implementation - for production, consider using a dedicated IP library
        if (strpos($range, '/') !== false) {
            list($subnet, $bits) = explode('/', $range);
            $ip = ip2long($ip);
            $subnet = ip2long($subnet);
            $mask = -1 << (32 - $bits);
            $subnet &= $mask;
            return ($ip & $mask) == $subnet;
        }
        
        return false;
    }
}