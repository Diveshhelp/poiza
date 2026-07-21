<?php
namespace App\Livewire;

use Auth;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use App\Models\Notification;
use App\Models\NotificationAction;
use Illuminate\Support\Facades\Cache;
use Razorpay\Api\Api;

class Header extends Component
{
    public $teamBalance = 0;
    public $notificationText;
    public $notifications;
    public $unreadCount = 0;

    protected $listeners = ['refresh-notifications' => 'loadNotifications'];
    public $daysLeft;
    public $progressPercentage;
    public $is_trial;
    public $allData;
    public function mount()
    {
        $team = Auth::user()->currentTeam;
        $this->teamBalance = 0;
        $this->loadNotifications();
        
        
        if($team['trial_start_date']==NULL && $team['trial_end_date']==NULL){
            $trialStartDate = '';
            $trialEndDate = '';
            $currentDate = Carbon::now();
            $daysLeft =0;
            $progressPercentage=0;
            $team['is_trial_mode']="yes";
            
        }else{
            // Parse dates
            $trialStartDate = Carbon::parse($team['trial_start_date']);
            $trialEndDate = Carbon::parse($team['trial_end_date']);
            $currentDate = Carbon::now();
                   
            // Calculate total trial duration in days
            $totalTrialDays = $trialStartDate->diffInDays($trialEndDate);
            
            // Calculate days left in trial
            $daysLeft = $currentDate->diffInDays($trialEndDate, false);
            // If trial has expired, return 0 days
            if ($daysLeft < 0) {
                $daysLeft = 0;
            }
            
            // Calculate percentage completed
            $daysCompleted = $totalTrialDays - $daysLeft;
            $progressPercentage = ($daysCompleted / $totalTrialDays) * 100;
            
            // Round to whole number
            $progressPercentage = round($progressPercentage);
        }

        
        $this->allData= [
            'is_trial' => $team['is_trial_mode'],
            'daysLeft' => $daysLeft,
            'progressPercentage' => $progressPercentage,
            'trialStartDate'=>$trialStartDate,
            'trialEndDate'=>$trialEndDate
        ];
        
    }

    public function loadNotifications()
    {
        $query = Notification::with(['reads.reader', 'createdBy'])
            ->whereHas('createdBy', function ($query) {
                $query->where(function ($q) {
                    // Get notifications created by Super Admin (1) or Admin (2)
                    $q->where('user_role', '1')
                        ->orWhere('user_role', '2')
                        ->orWhere('user_role', 'LIKE', '1,%')
                        ->orWhere('user_role', 'LIKE', '%,1,%')
                        ->orWhere('user_role', 'LIKE', '%,1')
                        ->orWhere('user_role', 'LIKE', '2,%')
                        ->orWhere('user_role', 'LIKE', '%,2,%')
                        ->orWhere('user_role', 'LIKE', '%,2');
                });
            })
            ->latest();
        $this->notifications= $query->get();
        $this->dispatch('notification-count-updated', count: $this->unreadCount);
    }

    public function updateUnreadCount()
    {
        $userId = Auth::id();
        $this->unreadCount = DB::table('notifications')
            ->leftJoin('notification_actions', function ($join) use ($userId) {
                $join->on('notifications.id', '=', 'notification_actions.notification_id')
                     ->where('notification_actions.read_by', '=', $userId);
            })
            ->whereNull('notification_actions.id')
            ->count();
            ;
    }
    public function canCreateNotifications()
    {
        $user = Auth::user();
        // return $user && ($user->hasRole('Super Admin') || $user->hasRole('Admin'));
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        $userRoles = explode(',', $user->user_role);
        // Check if user has role with slug '1' (Super Admin) or '2' (Admin)
        return in_array('1', $userRoles) || in_array('2', $userRoles);
    }

    public function createNotification()
    {
        if (!$this->canCreateNotifications()) {
            $this->dispatch('notify-error', 'You do not have permission to create notifications.');
            return;
        }

        $this->validate([
            'notificationText' => 'required|string'
        ]);

        $notification = Notification::create([
            'notification' => $this->notificationText,
            'created_by' => Auth::id()
        ]);

        $this->notificationText = '';
        $this->clearNotificationCache();
        $this->loadNotifications();
        
        $this->dispatch('notification-updated');
        $this->dispatch('notify-success', 'Notification created successfully.');
    }

    public function markAsRead($notificationId)
    {
        NotificationAction::firstOrCreate([
            'notification_id' => $notificationId,
            'read_by' => Auth::id(),
        ], [
            'read_at' => now(),
        ]);

        $this->clearNotificationCache();
        $this->loadNotifications();

        $this->dispatch('notify-success', 'Marked as read.');
    }

    private function clearNotificationCache()
    {
        Cache::forget('notifications_' . Auth::id());
    }

    public function removeCode()
    {
        session(['security_verified' => false]);
        session(['security_code' => '']);
        session(['security_batch_code' => '']);
        session(['session_name' => '']);
        session(['session_email' => '']);
        session(['session_user_id' => '']);
        
        redirect('/dashboard');
    }

    public function render()
    {
        $this->updateUnreadCount();
        return view('livewire.common.header');
    }
}