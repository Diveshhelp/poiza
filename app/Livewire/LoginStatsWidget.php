<?php


namespace App\Livewire;

use App\Models\LoginLog;
use Carbon\Carbon;
use Livewire\Component;

class LoginStatsWidget extends Component
{
    public $title;
    public $value;
    public $change;
    public $changeType;
    public $icon;
    public $color;

    public function mount($type = 'today')
    {
        $this->setupWidget($type);
    }

    private function setupWidget($type)
    {
        switch ($type) {
            case 'today':
                $this->title = 'Today\'s Logins';
                $this->value = LoginLog::whereDate('logged_in_at', Carbon::today())->count();
                $yesterday = LoginLog::whereDate('logged_in_at', Carbon::yesterday())->count();
                $this->change = $yesterday > 0 ? round((($this->value - $yesterday) / $yesterday) * 100, 1) : 0;
                $this->changeType = $this->change >= 0 ? 'increase' : 'decrease';
                $this->icon = 'calendar';
                $this->color = 'blue';
                break;

            case 'week':
                $this->title = 'This Week';
                $this->value = LoginLog::where('logged_in_at', '>=', Carbon::now()->startOfWeek())->count();
                $lastWeek = LoginLog::whereBetween('logged_in_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ])->count();
                $this->change = $lastWeek > 0 ? round((($this->value - $lastWeek) / $lastWeek) * 100, 1) : 0;
                $this->changeType = $this->change >= 0 ? 'increase' : 'decrease';
                $this->icon = 'calendar-week';
                $this->color = 'green';
                break;

            case 'month':
                $this->title = 'This Month';
                $this->value = LoginLog::where('logged_in_at', '>=', Carbon::now()->startOfMonth())->count();
                $lastMonth = LoginLog::whereBetween('logged_in_at', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ])->count();
                $this->change = $lastMonth > 0 ? round((($this->value - $lastMonth) / $lastMonth) * 100, 1) : 0;
                $this->changeType = $this->change >= 0 ? 'increase' : 'decrease';
                $this->icon = 'calendar-month';
                $this->color = 'purple';
                break;

            case 'active_users':
                $this->title = 'Active Users (30d)';
                $this->value = LoginLog::where('logged_in_at', '>=', Carbon::now()->subDays(30))
                    ->distinct('user_id')->count('user_id');
                $previous = LoginLog::whereBetween('logged_in_at', [
                    Carbon::now()->subDays(60),
                    Carbon::now()->subDays(30)
                ])->distinct('user_id')->count('user_id');
                $this->change = $previous > 0 ? round((($this->value - $previous) / $previous) * 100, 1) : 0;
                $this->changeType = $this->change >= 0 ? 'increase' : 'decrease';
                $this->icon = 'users';
                $this->color = 'orange';
                break;
        }
    }

    public function render()
    {
        return view('livewire.login-stats-widget')->layout('layouts.app');
    }
}
