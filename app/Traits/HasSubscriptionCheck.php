<?php

namespace App\Traits;

use Auth;
use Carbon\Carbon;

trait HasSubscriptionCheck
{
    public function ensureSubscription()
    {
        $team = Auth::user()->currentTeam;
         
        if (!$team) {
            return false;
        }
        
        // Check if the user is within trial period
        if (isset($team['trial_start_date']) && isset($team['trial_end_date'])) {
            $trialStartDate = Carbon::parse($team['trial_start_date']);
            $trialEndDate = Carbon::parse($team['trial_end_date']);
            $currentDate = Carbon::now();
            
            // Calculate days left in trial
            $daysLeft = $currentDate->diffInDays($trialEndDate, false);
            
            // If trial is still active (days left > 0), return true
            if ($daysLeft > 0) {
                return true;
            }
        }
        
    }
}