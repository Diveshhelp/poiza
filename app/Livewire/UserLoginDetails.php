<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\LoginLog;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class UserLoginDetails extends Component
{
    use WithPagination;

    public User $user;
    public $days = 30;
    public $selectedPeriod = '30';
    public $filterByIp = '';
    public $sortBy = 'logged_in_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'days' => ['except' => 30],
        'filterByIp' => ['except' => ''],
        'sortBy' => ['except' => 'logged_in_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->days = (int) $this->selectedPeriod;
    }

    public function updatedSelectedPeriod($value)
    {
        $this->days = (int) $value;
        $this->resetPage();
    }

    public function updatedFilterByIp()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function getUserStatisticsProperty()
    {
        return [
            'total_logins' => $this->user->getLoginFrequency($this->days),
            'avg_logins_per_day' => $this->user->getAverageLoginsPerDay($this->days),
            'last_login' => $this->user->getLastLogin(),
            'first_login_in_period' => $this->user->loginLogs()
                ->where('logged_in_at', '>=', Carbon::now()->subDays($this->days))
                ->oldest('logged_in_at')
                ->first()?->logged_in_at,
            'unique_ips' => $this->user->loginLogs()
                ->where('logged_in_at', '>=', Carbon::now()->subDays($this->days))
                ->distinct('ip_address')
                ->count('ip_address'),
        ];
    }

    public function getLoginLogsProperty()
    {
        return $this->user->loginLogs()
            ->where('logged_in_at', '>=', Carbon::now()->subDays($this->days))
            ->when($this->filterByIp, function ($query) {
                $query->where('ip_address', 'like', '%' . $this->filterByIp . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(20);
    }

    public function getDailyPatternProperty()
    {
        return $this->user->getDailyLoginPattern($this->days);
    }

    public function getIpAddressesProperty()
    {
        return $this->user->loginLogs()
            ->where('logged_in_at', '>=', Carbon::now()->subDays($this->days))
            ->selectRaw('ip_address, COUNT(*) as login_count, MAX(logged_in_at) as last_used')
            ->groupBy('ip_address')
            ->orderByDesc('login_count')
            ->get();
    }

    public function render()
    {
        return view('livewire.user-login-details', [
            'userStatistics' => $this->userStatistics,
            'loginLogs' => $this->loginLogs,
            'dailyPattern' => $this->dailyPattern,
            'ipAddresses' => $this->ipAddresses,
        ])->layout('layouts.app');
    }
}