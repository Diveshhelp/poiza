<?php

// 1. Main Analytics Dashboard Livewire Component
// Create: php artisan make:livewire LoginAnalyticsDashboard

namespace App\Livewire;

use App\Models\User;
use App\Models\LoginLog;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use Livewire\WithPagination;

class LoginAnalyticsDashboard extends Component
{
    use WithPagination;

    public $days = 30;
    public $searchUser = '';
    public $selectedPeriod = '30';
    public $refreshInterval = 30000; // 30 seconds
    public $sortBy;
    public $listeners = ['refreshData' => '$refresh'];

    protected $queryString = [
        'days' => ['except' => 30],
        'searchUser' => ['except' => ''],
    ];

    public function mount()
    {
        $this->days = (int) $this->selectedPeriod;
    }

    public function updatedSelectedPeriod($value)
    {
        $this->days = (int) $value;
        $this->resetPage();
    }

    public function updatedSearchUser()
    {
        $this->resetPage();
    }

    public function refreshData()
    {
        // This method will be called automatically by the polling
        $this->render();
    }

    public function getOverviewStatsProperty()
    {
        $totalUsers = User::count();
        $activeUsers = User::whereHas('loginLogs', function ($query) {
            $query->where('logged_in_at', '>=', Carbon::now()->subDays($this->days));
        })->count();

        $veryActiveUsers = User::whereHas('loginLogs', function ($query) {
            $query->where('logged_in_at', '>=', Carbon::now()->subDays($this->days));
        }, '>=', 10)->count();

        $totalLogins = LoginLog::where('logged_in_at', '>=', Carbon::now()->subDays($this->days))->count();

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'very_active_users' => $veryActiveUsers,
            'total_logins' => $totalLogins,
            'engagement_rate' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0,
            'avg_logins_per_user' => $activeUsers > 0 ? round($totalLogins / $activeUsers, 1) : 0,
        ];
    }

    public function getMostActiveUsersProperty()
    {
        return User::withCount(['loginLogs' => function ($query) {
                $query->where('logged_in_at', '>=', Carbon::now()->subDays($this->days));
            }])
            ->when($this->searchUser, function ($query) {
                $query->where('name', 'like', '%' . $this->searchUser . '%')
                      ->orWhere('email', 'like', '%' . $this->searchUser . '%');
            })
            ->having('login_logs_count', '>', 0)
            ->orderByDesc('login_logs_count')
            ->paginate(10);
    }

    public function getDailyLoginsProperty()
    {
        return LoginLog::where('logged_in_at', '>=', Carbon::now()->subDays($this->days))
            ->selectRaw('DATE(logged_in_at) as date, COUNT(*) as login_count, COUNT(DISTINCT user_id) as unique_users')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getHourlyDistributionProperty()
    {
        return LoginLog::where('logged_in_at', '>=', Carbon::now()->subDays($this->days))
            ->selectRaw('HOUR(logged_in_at) as hour, COUNT(*) as login_count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
    }
    public function  resetSession($user_id){
        $user = User::findOrFail($user_id);
            
        // Delete all sessions for the user
        $deletedCount = DB::table('sessions')->where('user_id', $user_id)->delete();
            
        $this->dispatch('notify-success', "Reset {$deletedCount} session(s) for {$user->name}");
    }
    public function render()
    {
        return view('livewire.login-analytics-dashboard', [
            'overviewStats' => $this->overviewStats,
            'mostActiveUsers' => $this->mostActiveUsers,
            'dailyLogins' => $this->dailyLogins,
            'hourlyDistribution' => $this->hourlyDistribution,
        ])->layout('layouts.app');
    }
}