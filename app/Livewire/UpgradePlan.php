<?php

namespace App\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Log;
use Razorpay\Api\Api;
use App\Models\Team;
class UpgradePlan extends Component
{
    public $selectedPlan = null;
   
    public $plans;

    public $rozerPlans=[];
  
    
    public function __construct(){
    
        $this->plans= config('plans');
        $this->rozerPlans=["starter"=>env("PLAN_ONE"),"professional"=>env("PLAN_TWO"),"enterprise"=>env("PLAN_THREE")];
    }
    
    public function selectPlan($plan)
    {
       
        $this->selectedPlan = $plan;
        $this->dispatch('planSelected', $plan);
    }

    public function checkout()
    {
      
        if (!$this->selectedPlan || !isset($this->plans[$this->selectedPlan])) {
            session()->flash('error', 'Please select a valid plan');
            return;
        }

        try {
            // Get plan details
            $plan = $this->plans[$this->selectedPlan];
            
            // Initialize Razorpay API (you need to configure this in .env)
            $key_id =env('RAZORPAY_KEY');
            $secret = env('RAZORPAY_SECRET');    
            $api = new Api($key_id, $secret);
            
            // Create order
            $orderData = [
                'receipt' => 'order_' . time(),
                'amount' => $plan['GSTprice'] * 100, // Amount in paise
                'currency' => $plan['currency'],
                'notes' => [
                    'plan_name' => $plan['name'],
                    'user_id' => Auth::id(), // Assuming user is authenticated
                    'customer_id'=>Auth::user()->customer_id??$this->createCustomerFirst()
                ]
            ];
             // Create subscription
             $subscriptionData = [
                'customer_id' => Auth::user()->customer_id, // Add the customer ID here
                 'plan_id' => $this->rozerPlans[$this->selectedPlan],
                 'customer_notify' => 1,
                 'total_count' => 24, // Number of billing cycles
                 'notes' => [
                     'user_id' => Auth::id(),
                     'customer_id'=>Auth::user()->customer_id??''
                 ]
             ];
             
             $razorpaySubscription = $api->subscription->create($subscriptionData);
            
             
            $order = $api->order->create($orderData);
            
            // Pass order details to frontend
            $this->dispatch('checkoutReady', rzpdata:[
                'subscription_id' => $razorpaySubscription->id,
                'order_id' => $order->id,
                'amount' => $plan['GSTprice'],
                'currency' => $plan['currency'],
                'plan_name' => $plan['name'],
                'key' => $key_id
            ]);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating checkout: ' . $e->getMessage());
        }
    }
    #[On('sub-created')]
    public function paymentSuccessful($response)
    {        
        Log::info("Response Received");
        try {
            // Initialize Razorpay API
            $key_id = env('RAZORPAY_KEY');
            $secret = env('RAZORPAY_SECRET');    
            $api = new Api($key_id, $secret);
           
            // Verify the signature
            $attributes = [
                'razorpay_payment_id' => $response['razorpay_payment_id'],
                'razorpay_subscription_id' => $response['razorpay_subscription_id'],
                'razorpay_signature' => $response['razorpay_signature']
            ];
            Log::info(json_encode($response));
           
            $api->utility->verifyPaymentSignature($attributes);
           
            // Payment is verified, update user subscription
            $user = auth()->user();
            $user->update([
                'plan' => $this->selectedPlan,
                'plan_expires_at' => now()->addMonth(),
            ]);
           
            $duration = 30;
            \App\Models\TeamSubscriptions::create([
                'team_id' => Auth::user()->currentTeam->id,
                'plan_id' => $this->rozerPlans[$this->selectedPlan],
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addDays($duration),
                'trial_ends_at' => now()->addDays($duration),
                'auto_renew' => true,
                'razorpay_subscription_id' => $response['razorpay_subscription_id'],
                'razorpay_subscription_data' => json_encode($response)
            ]);
            
            $teamUpdate=Team::find(Auth::user()->currentTeam->id);
            $teamUpdate->is_trial_mode="no";

            // If team already has a trial_end_date, add 30 days to that date
            if ($teamUpdate->trial_end_date) {
                // Parse the existing end date and add 30 days
                $currentEndDate = Carbon::parse($teamUpdate->trial_end_date);
                $teamUpdate->trial_end_date = $currentEndDate->addDays($duration);
            } else {
                // Otherwise, set trial dates as before
                $teamUpdate->trial_start_date = now();
                $teamUpdate->trial_end_date = now()->addDays($duration);
            }

            $teamUpdate->save();
           
            $this->dispatch('swal:modal', detail:[
                'type' => 'success',
                'title' => 'Payment Successful',
                'message' => 'Your subscription has been upgraded successfully!'
            ]);
            $this->selectedPlan = null; // Reset the selection
           
        } catch (\Exception $e) {
            Log::info("Response error");
            Log::error($e->getMessage());
            $this->dispatch('swal:modal', detail:[
                'type' => 'error',
                'title' => 'Payment Failed',
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ]);
        }
    }
    #[On('payment-cancelled')]
    public function paymentCancelled()
    {
        $this->dispatch('swal:modal', detail:[
            'type' => 'error',
            'title' => 'Payment Cancelled',
            'message' => 'Payment cancelled! Try again later.'
        ]);
    }    
    public function createCustomerFirst(){
        $key_id =env('RAZORPAY_KEY');
        $secret = env('RAZORPAY_SECRET');
        $api = new Api($key_id, $secret);
        
        // Create customer
       $customer = $api->customer->create([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'contact'=>''
        ]);

        $userUpdate=User::find(Auth::user()->id);
        $userUpdate->customer_id=$customer->id;
        $userUpdate->save();
        
        return $customer->id;
    }
    public function render()
    {
        return view('livewire.upgrade-index')->layout('layouts.app');
    }
}