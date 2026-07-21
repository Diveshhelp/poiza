<?php

namespace App\Actions\Fortify;

use App\Models\Department;
use App\Models\DocCategory;
use App\Models\MyReferrals;
use App\Models\Ownership;
use App\Models\Role;
use App\Models\SubDepartments;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Log;
use Razorpay\Api\Api;
use Str;
use App\Rules\ExcludeTestingDomains; // Import your custom rule
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'numeric'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users',  new ExcludeTestingDomains],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

       
        return DB::transaction(function () use ($input) {
            
           
            return tap(User::create([
                'name' => $input['name'],
                'mobile' => $input['mobile'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'security_code' => $input['password'],
                'user_role'=>Role::select('id', 'name')->first()->id,
                'customer_id'=>'',
                'unsubscribe_token' => Str::random(32)
            ]), function (User $user) use ($input) {
                $this->createTeam($user);
                $customerId=$this->newUserRozerpay($input);
                $user->customer_id=$customerId??'';
                $user->save();
                if(isset($input['referral_email']) && $input['referral_email']!=""){
                    $this->setupReferralLink($user,$input);
                }
        
            });
        });
    }
    public function setupReferralLink(User $user,$input){
            // Find the referrer user by email
        $referrer = User::where("email", $input['referral_email'])->first();
        // Only create the referral if we found a matching user
        if ($referrer) {
            MyReferrals::create([
                'refer_by' => $referrer->current_team_id,
                'refer_to' => $user->id,
                'is_join' => 1
            ]);
        }
        // Optionally, you might want to log if the referrer wasn't found
        else {
            Log::info("Referral email not found: " . $input['referral_email']);
        }
        
    }
    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $duration = (int) env('TRIAL_DAYS', 120); 
        $team = $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
            'uuid' => Str::uuid()->toString(),
            'is_trial_mode'=>'yes',
            'trial_start_date'=>now(),
            'trial_end_date' => now()->addDays($duration),
        ]));
        $this->createInitialSubscription($team->id);
        $this->createDefaultRecords($team->id);
    }

    protected function newUserRozerpay($user){
        
        $key_id =env('RAZORPAY_KEY');
        $secret = env('RAZORPAY_SECRET');
        $api = new Api($key_id, $secret);
        
        // Create customer
       $customer = $api->customer->create([
            'name' => $user['name'],
            'email' => $user['email'],
            'contact'=>''
        ]);
        return $customer->id;
    
    }

    protected function createInitialSubscription($teamId)
    {
        $duration = (int) env('TRIAL_DAYS', 120); 
        return \App\Models\TeamSubscriptions::create([
            'team_id' => $teamId,
            'plan_id' => 'Subscription Trial',
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($duration),
            'trial_ends_at' => now()->addDays($duration),
            'auto_renew' => true,
            'razorpay_subscription_id' => 'TRIAL-'.time(),
            'razorpay_subscription_data' => ''
        ]);
    }

    public function createDefaultRecords($team_id){
       DocCategory::create([
            'uuid' => Str::uuid(),
            'category_title' => "Default Category",
            'row_status' => 1,
            'created_by' => auth()->id(),
            'team_id'=>$team_id
        ]);

        Ownership::create([
            'owner_title' => "Default Owner",
            'team_id'=>$team_id
        ]);

        $department=Department::create([
            'uuid' => Str::uuid(),
            'department_name' => "Default Department",
            'description' => "System created department",
            'row_status' => 1,
            'created_by' => auth()->id(),
            'team_id'=>$team_id
        ]);
        SubDepartments::create([
            'department_id' => $department->id,
            'name' => "Default Sub-department",
            'is_active' => 1,
            'created_by' => auth()->id(),
            'team_id'=>$team_id
        ]);

    }
}
