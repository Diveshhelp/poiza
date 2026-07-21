<?php

namespace App\Livewire;

use App\Models\TeamSubscriptions;
use App\Traits\HasSubscriptionCheck;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
class MySubscription extends Component
{
    use HasSubscriptionCheck;
    
    public $user_id;
    public $team_id;
    
    public $subscriptions = [];
    public $loading = true;
    public $errorMessage = '';
    public $successMessage = '';
    
    // For cancellation modal
    public $showCancelModal = false;
    public $subscriptionToCancel;
    public $subscriptionIdToCancel ;  // Use simple string to store ID
    public $subscriptionPlanToCancel;  // Store plan ID as string
    public $cancelAtCycleEnd = true;
    public $razorpay;
    public $mySubs;
    public $api;
    public $key_id;
    public $secret;

    public $teamSubscriptionId;
    public $cancelStatus = null;
    public $message = '';
    protected $listeners = ['refresh' => '$refresh'];
    public function mount()
    {
        if (!$this->ensureSubscription()) {
            abort(402, 'Access denied. Subscription is over, Please renew the plan and get the full access!');
        }
       
        $this->user_id = Auth::id();
        $this->team_id = Auth::user()->currentTeam->id;
    }

    public function render()
    {
        $this->teamSubscriptionId=TeamSubscriptions::whereNotNull('razorpay_subscription_id')->where("team_id",Auth::user()->currentTeam->id)->pluck('razorpay_subscription_id')->toArray();
        return view('livewire.my-subscriptions', [
            'customerSubscriptions' =>  $this->loadSubscription(),
            'customerTrial' =>  $this->loadTrial()
        ])->layout('layouts.app');
    }
    public function loadTrial(){
        return TeamSubscriptions::whereNotNull('razorpay_subscription_id')->where("team_id",Auth::user()->currentTeam->id)->where('razorpay_subscription_id', 'like', 'TRIAL-%')
            ->get();
    }

    public function loadSubscription(){
        $customerSubscriptions=[];
        foreach ($this->teamSubscriptionId as $key=>$subscription) {
        
            $customerSubscriptions[$key] = $this->getSubscription($subscription,true);
        }
        return $customerSubscriptions;
    }

    public function getSubscription($subscriptionId, $includeInvoices = false)
{
    try {
        // Initialize Razorpay API
        $key_id = env('RAZORPAY_KEY');
        $secret = env('RAZORPAY_SECRET');    
        $api = new Api($key_id, $secret);
        
        // Fetch the specific subscription directly by ID
        $subscription = $api->subscription->fetch($subscriptionId);
        
        // Verify this subscription belongs to the current user (security check)
        $userId = Auth::id();
        if (isset($subscription->notes) &&
            isset($subscription->notes->user_id) &&
            $subscription->notes->user_id == $userId) {
            
            // Convert to array for easier manipulation
            $subscriptionData = [
                'id' => $subscription->id,
                'plan_id' => $subscription->plan_id,
                'status' => $subscription->status,
                'quantity' => $subscription->quantity ?? 1,
                'created_at' => $subscription->created_at,
                'current_start' => $subscription->current_start ?? null,
                'current_end' => $subscription->current_end ?? null,
                'ended_at' => $subscription->ended_at ?? null,
                'notes' => $subscription->notes,
                'payment_method'=>$subscription->payment_method,
            ];
            
            // Include invoices if requested
            if ($includeInvoices) {
                $subscriptionData['invoices'] = $this->showSubscriptionInvoices($subscriptionId);
            }
            
            return [
                'success' => true,
                'subscription' => $subscriptionData
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Unauthorized access to this subscription'
            ];
        }
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => 'Error fetching subscription: ' . $e->getMessage()
        ];
    }
}


    public function showSubscriptionInvoices($subscriptionId)
    {
    
        
        try {
            // Fetch all invoices for this subscription
            $options = [
                'subscription_id' => $subscriptionId
            ];
            $key_id =env('RAZORPAY_KEY');
            $secret = env('RAZORPAY_SECRET');    
            $api = new Api($key_id, $secret);
    
    
            $invoices = $api->invoice->all($options);
            
            // Prepare invoice data for view
            $invoiceData = [];
            foreach ($invoices->items as $invoice) {
                $invoiceData[] = [
                    'id' => $invoice->id,
                    'amount' => $invoice->amount,
                    'currency' => $invoice->currency,
                    'status' => $invoice->status,
                    'date' => date('Y-m-d', $invoice->date),
                    'invoice_url' => $invoice->short_url, // Invoice link
                    'invoice_number' => $invoice->invoice_number
                ];
            }
            
            return  $invoiceData;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription($subscriptionId, $reason = null)
    {
        $this->cancelStatus = 'processing';
        try {
            $key_id =env('RAZORPAY_KEY');
            $secret = env('RAZORPAY_SECRET');    
            $api = new Api($key_id, $secret);

            $subscription = $api->subscription->fetch($subscriptionId)->cancel([
                'cancel_at_cycle_end' => 1  // Set to 0 for immediate cancellation
            ]);
            
            $CancelSubscription=TeamSubscriptions::where('razorpay_subscription_id',$subscriptionId)->where("team_id",Auth::user()->currentTeam->id)->first();
            $CancelSubscription->canceled_at=now();
            $CancelSubscription->cancellation_reason=$reason;
            $CancelSubscription->save();

            $this->cancelStatus = 'success';
            $this->message = 'Subscription cancelled successfully!';

        } catch (\Exception $e) {
            $this->cancelStatus = 'failed';
            $this->message = 'Failed to cancel: ' . ($errorData['error']['description'] ?? 'Unknown error');
        }
    }
}