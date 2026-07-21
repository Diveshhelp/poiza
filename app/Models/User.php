<?php
// File:- app/Models/User.php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use ESolution\DBEncryption\Traits\EncryptedAttribute;

class User extends Authenticatable implements MustVerifyEmail

{

    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use EncryptedAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'current_team_id',
        'profile_photo_path',
        'created_at',
        'updated_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'uuid',
        'security_code',
        'user_role',
        // Add compatibility with source table
        'mobile',
        'status',
        'customer_id',
        'subscribed_to_emails',
        'unsubscribe_token',
        'unsubscribed_at'
    ];

    protected $encryptable = [
        'security_code'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        // This is just an example. Adjust according to your admin detection logic
        // return $this->role === 'admin' || $this->is_admin;
        return true;    
    }

    /**
     * Get array of role IDs assigned to the user
     *
     * @return array
     */
    public function getRoleIds(): array
    {
        return !empty($this->user_role) ? explode(',', $this->user_role) : [];
    }

    /**
     * Check if user has a specific role
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        $roleIds = $this->getRoleIds();
        return Role::whereIn('id', $roleIds)
                  ->where('name', $roleName)
                  ->exists();
    }

    public function hasAdminAccess(): bool
    {
        $userRoles = explode(',', $this->user_role);
        return in_array('1', $userRoles) || in_array('2', $userRoles); // Super Admin or Admin
    }

    public function hasOwnerAccess(): bool
    {
        return $this->email==env('ADMIN_EMAIL');
    }
    public function hasRajAccess(): bool
    {
        return $this->email==env('RAJ_MENU_EMAIL');
    }
    
    public function isEmployee(): bool
    {
        $userRoles = explode(',', $this->user_role);
        return in_array('5', $userRoles); // Employee
    }
    public function isOnlyDocument(): bool
    {
        $userRoles = explode(',', $this->user_role);
        return in_array('6', $userRoles); // Employee
    }

    public function getRoleNameAttribute()
    {
        $userRoles = explode(',', $this->user_role);
        $roleMap = [
            '1' => 'Super Admin',
            '2' => 'Admin',
            '3' => 'Department Head',
            '4' => 'Department Work',
            '5' => 'Employee'
        ];

        $roles = array_map(function($roleId) use ($roleMap) {
            return $roleMap[$roleId] ?? '';
        }, $userRoles);

        return implode(', ', array_filter($roles));
    }
    
    public function loginLogs(): HasMany
    {
        return $this->hasMany(LoginLog::class);
    }
     // Helper methods for login frequency
     public function getLoginFrequency($days = 30)
     {
         return $this->loginLogs()
             ->where('logged_in_at', '>=', Carbon::now()->subDays($days))
             ->count();
     }
 
     public function getLastLogin()
     {
         return $this->loginLogs()
             ->latest('logged_in_at')
             ->first()?->logged_in_at;
     }
 
     public function getAverageLoginsPerDay($days = 30)
     {
         $loginCount = $this->getLoginFrequency($days);
         return round($loginCount / $days, 2);
     }
 
     public function getDailyLoginPattern($days = 30)
     {
         return $this->loginLogs()
             ->where('logged_in_at', '>=', Carbon::now()->subDays($days))
             ->selectRaw('DATE(logged_in_at) as date, COUNT(*) as login_count')
             ->groupBy('date')
             ->orderBy('date')
             ->get();
     }
}