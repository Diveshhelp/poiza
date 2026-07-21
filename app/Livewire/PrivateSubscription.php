<?php

namespace App\Livewire;

use App\Models\Team;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PrivateSubscription extends Component
{    
    public $user_id;
    public $team_id;
    
    private $teamList=[];
    public $isModalOpen = false;
    public $team;
    public $startDate;
    public $endDate;
    public $selectedTeam;
    protected $rules = [
        'startDate' => 'required|date',
        'endDate' => 'required|date|after:startDate',
    ];

    public function mount()
    {
        $this->user_id = Auth::id();
        $this->team_id = Auth::user()->currentTeam->id;
    }

    public function openModal($teamId)
    {
        
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->addDays(30)->format('Y-m-d');
        $this->selectedTeam = Team::find($teamId);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['startDate','endDate']);
    }

    public function extendSubscription()
    {
        $this->validate();
        
        if (!$this->selectedTeam) {
            session()->flash('error', 'No active subscription found for this team.');
            return;
        }
        
        try {
            $duration = 30;
            \App\Models\TeamSubscriptions::create([
                'team_id' => $this->selectedTeam->id,
                'plan_id' =>'trial',
                'status' => 'active',
                'starts_at' => $this->startDate,
                'ends_at' => $this->endDate,
                'trial_ends_at' => $this->endDate,
                'auto_renew' => true,
                'razorpay_subscription_id' => 'TRIAL-'.time(),
                'razorpay_subscription_data' => ''
            ]);
            
            $teamUpdate=Team::find($this->selectedTeam->id);
            $teamUpdate->is_trial_mode="no";
            $teamUpdate->trial_start_date = $this->startDate;
            $teamUpdate->trial_end_date = $this->endDate;
            $teamUpdate->save();
            session()->flash('message', "Subscription for {$this->selectedTeam->name} has been extended by {$duration} days.");
            $this->closeModal();
            // Emit an event to refresh the parent component
            $this->dispatch('subscription-extended');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to extend subscription: ' . $e->getMessage());
        }
    }
    public function render()
    {

        $this->teamList=Team::get();
        return view('livewire.private-subscription', [
            'teamList' => $this->teamList
        ])->layout('layouts.app');
    }

   
}