<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\TeamSubscriptions;
use App\Models\MyReferrals; // Added import for referrals model
use App\Models\User;
use App\Traits\HasSubscriptionCheck;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination; // Added for pagination
use Illuminate\Support\Facades\Auth;

class MyReferral extends Component
{
    use HasSubscriptionCheck;
    use WithPagination; // Use Livewire pagination

    public $user_id;
    public $team_id;
    public $perPage = 10; // Number of records per page
    public $search = ''; // For searching referrals
    public $filter = 'all'; // Filter options: 'all', 'pending', 'completed'
   
   
    public $loading = true;
    public $errorMessage = '';
    public $successMessage = '';
    public $message = '';
    public $bonusDaysEarned = 0;
    public $bonusDaysRemaining = 0;
    public $currentSubscription = null;
    public $default_bonus_days=0;
    
    // Reset pagination when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    // Reset pagination when filter changes
    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->default_bonus_days=env('DEFAULT_DAY_BONUS')??7;

        if (!$this->ensureSubscription()) {
            abort(402, 'Access denied. Subscription is over, Please renew the plan and get the full access!');
        }
       
        $this->user_id = Auth::id();
        $this->team_id = Auth::user()->currentTeam->id;

        
        // Calculate total bonus days earned
        $this->calculateBonusDays();
    }

    public function calculateBonusDays()
    {
        // Total bonus days earned (7 days per successful referral)
        $completedReferrals = MyReferrals::where('refer_by', $this->user_id)
                                        ->where('is_join', 1)
                                        ->count();
        
        // Count only referrals that haven't had their bonus applied yet
        $availableReferrals = MyReferrals::where('refer_by', $this->user_id)
                                        ->where('is_join', 1)
                                        ->where('bonus_applied', false)
                                        ->count();
        
        $this->bonusDaysEarned = $completedReferrals *  $this->default_bonus_days;
        $this->bonusDaysRemaining = $availableReferrals * $this->default_bonus_days;
        
        // Get current subscription
        $this->currentSubscription = TeamSubscriptions::where('team_id', $this->team_id)
            ->whereNotNull('razorpay_subscription_id')
            ->where(function($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', Carbon::now());
            })
            ->orderBy('ends_at', 'desc')
            ->first();
    }
    public function render()
    {
        $this->teamSubscriptionId = TeamSubscriptions::whereNotNull('razorpay_subscription_id')
            ->where("team_id", Auth::user()->currentTeam->id)
            ->pluck('razorpay_subscription_id')
            ->toArray();
            
        return view('livewire.my-referral', [
            'referrals' => $this->loadReferrals()
        ])->layout('layouts.app');
    }

    /**
     * Load referrals with pagination and filtering
     */
    public function loadReferrals()
    {
        $this->loading = true;
        
        // Start query with user's referrals
        $query = MyReferrals::where('refer_by', $this->user_id);
        
        // Apply filter
        if ($this->filter === 'pending') {
            $query->where('is_join', 0);
        } elseif ($this->filter === 'completed') {
            $query->where('is_join', 1);
        }
        
        // Apply search if provided
        if (!empty($this->search)) {
            // Join with users table to search by referred user's name or email
            $query->join('users', 'my_referrals.refer_to', '=', 'users.id')
                  ->where(function($q) {
                      $q->where('users.name', 'like', '%' . $this->search . '%')
                        ->orWhere('users.email', 'like', '%' . $this->search . '%');
                  })
                  ->select('my_referrals.*'); // Make sure to select only from referrals table
        }
        
        // Get referrals with pagination
        $referrals = $query->orderBy('created_at', 'desc')
                           ->paginate($this->perPage);
                           
        // Load referred users for display
        if ($referrals->isNotEmpty()) {
            foreach ($referrals as $referral) {
                if ($referral->refer_to) {
                    $referral->referred_user = User::find($referral->refer_to);
                }
            }
        }
        
        $this->loading = false;
        
        return $referrals;
    }
    
    /**
     * Get referrals statistics
     */
    public function getReferralStats()
    {
        return [
            'total' => MyReferrals::where('refer_by', $this->team_id)->count(),
            'pending' => MyReferrals::where('refer_by', $this->team_id)->where('is_join', 0)->count(),
            'completed' => MyReferrals::where('refer_by', $this->team_id)->where('is_join', 1)->count(),
            'bonus_days_earned' => $this->bonusDaysEarned,
            'bonus_days_remaining' => $this->bonusDaysRemaining,
        ];
    }

    public function applyBonusDays()
    {
        // Check if there's an active subscription
        if (!$this->currentSubscription) {
            $this->errorMessage = "No active subscription found to apply bonus days.";
            return;
        }
        
        // Check if there are available bonus days
        if ($this->bonusDaysRemaining <= 0) {
            $this->errorMessage = "No bonus days available to apply.";
            return;
        }
        
        try {
            // Get all pending referrals that haven't been claimed yet
            $allMyPendingReferrals = MyReferrals::where('refer_by', $this->user_id)
                ->where('is_join', 1)
                ->where(function($query) {
                    $query->where('bonus_applied', false)
                          ->orWhereNull('bonus_applied');
                })
                ->get();
            
            // If there are no pending referrals to apply, show error
            if ($allMyPendingReferrals->count() <= 0) {
                $this->errorMessage = "No new referral bonuses to apply.";
                return;
            }
            
            // Define start date (today or current subscription end date, whichever is later)
            $startDate = now();
            if ($this->currentSubscription->ends_at && Carbon::parse($this->currentSubscription->ends_at)->isAfter($startDate)) {
                $startDate = Carbon::parse($this->currentSubscription->ends_at);
            }
            
            // Calculate total bonus days (7 days per referral)
            $totalBonusDays = $allMyPendingReferrals->count() * $this->default_bonus_days;
            
            // Define end date (start date + bonus days)
            $endDate = $startDate->copy()->addDays($totalBonusDays);
            
            // Create individual bonus subscription records for each referral
            foreach ($allMyPendingReferrals as $referral) {
                // Create a 7-day bonus subscription for each referral
                TeamSubscriptions::create([
                    'team_id' => $this->team_id,
                    'plan_id' => 'Bonus',
                    'status' => 'active',
                    'starts_at' => $startDate,
                    'ends_at' => $startDate->copy()->addDays($this->default_bonus_days), // Each referral adds 7 days
                    'trial_ends_at' => $startDate->copy()->addDays($this->default_bonus_days),
                    'auto_renew' => false, // Bonus subscriptions typically don't auto-renew
                    'razorpay_subscription_id' => 'BONUS-' . $referral->id,
                    'razorpay_subscription_data' => json_encode([
                        'bonus_type' => 'referral',
                        'referral_id' => $referral->id,
                        'days_added' => 7,
                        'applied_at' => now()->toDateTimeString()
                    ])
                ]);
                
                // Mark this referral as having its bonus applied
                $referral->bonus_applied = true;
                $referral->bonus_applied_at = now();
                $referral->save();
                
                // Move start date forward for the next subscription
                $startDate = $startDate->copy()->addDays($this->default_bonus_days);
            }
            
            // Update the team's trial end date
            $teamUpdate = Team::find($this->team_id);
            if ($teamUpdate) {
                $teamUpdate->trial_end_date = $endDate;
                $teamUpdate->save();
            }
            
            // Recalculate bonus days to update the UI
            $this->calculateBonusDays();
            
            // Show success message
            $this->successMessage = "{$totalBonusDays} bonus days have been applied! Your subscription is now extended until " . $endDate->format('M d, Y') . ".";
            
        } catch (\Exception $e) {
            \Log::error("Error applying bonus days: " . $e->getMessage());
            $this->errorMessage = "An error occurred while applying bonus days. Please try again later.";
        }
    }
}