<?php
namespace App\Livewire;
use App\Models\Team;
use App\Models\TeamSubscriptions;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use App\Models\Role;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Log;

class UserManager extends Component
{
    use WithPagination;
    
    public $search = '';
    public $statusFilter = '1'; // Default to active users only ('1' = active, '2' = inactive, 'all' = all users)
    public $roles = [];
    
    use WithFileUploads;
    public $csvFile;
    public $importedCount = 0;
    public $errorCount = 0;
    public $processingComplete = false;
    public $errors = [];
    public $mappings = [
        'name' => 'name',
        'email' => 'email',
        'mobile' => 'mobile',
        'security_code' => 'security_code'
    ];
    public $headers = [];
    public $previewData = [];
    public $showMappingStep = false;
    public $confirmingUserDeletion = false;
    public $userIdBeingDeleted;
    
    protected $rules = [
        'csvFile' => 'required|file|mimes:csv,txt|max:5120', // 5MB max
    ];
    
    public $team_id;
    public $activePlans=[];
    public $pendingUsers=0;
    public $totalUsers=0;
    public $quota=0;
    public $useActivePlanData;
    public $loading=false;
    public $selectedStatus;
    public function mount()
    {
        $this->team_id=Auth::user()->currentTeam->id;
        // Load roles from database
        $this->roles = Role::select('id', 'name')->get()->toArray();
        $this->activePlans=[
            'Bonus' => env('PLAN_TRIAL_ALLOW'),
            'Subscription Trial' => env('PLAN_TRIAL_ALLOW'),
            env('PLAN_ONE') => env('PLAN_ONE_ALLOW'),
            env('PLAN_TWO') => env('PLAN_TWO_ALLOW'),
            env('PLAN_THREE') => env('PLAN_THREE_ALLOW'),
        ];
       
        $this->getQuota();
    }

    public function getQuota(){
        $this->useActivePlanData = TeamSubscriptions::where('team_id', auth()->user()->currentTeam->id)
        ->where('status', 'active')
        ->where('plan_id',"!=",'Bonus')
        ->where('ends_at', '>', Carbon::now())
        ->latest('starts_at')
        ->first();
       
        $planType = $this->useActivePlanData->plan_id??'trial';
        $this->quota =$this->activePlans[$planType]??0;
        $this->totalUsers = User::where("current_team_id",$this->team_id)->count();
        $this->pendingUsers=$this->quota-$this->totalUsers;
    }

    // Reset pagination when search or filter changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    // Method to set status filter
    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    // Get filtered user counts for the UI
    public function getFilteredCounts()
    {
        $baseQuery = User::where("current_team_id", $this->team_id);
        
        if (!empty($this->search)) {
            $baseQuery->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        return [
            'pending' => (clone $baseQuery)->where('status', '0')->count(),
            'active' => (clone $baseQuery)->where('status', '1')->count(),
            'blocked' => (clone $baseQuery)->where('status', '2')->count(),
            'rejected' => (clone $baseQuery)->where('status', '3')->count(),
            'all' => $baseQuery->count()
        ];

    }

    public function updateUserRoles($userId, $roleIds)
    {
        $user = User::find($userId);
        if ($user) {
            // Convert array to comma-separated string
            $roleString = !empty($roleIds) ? implode(',', $roleIds) : null;
            $user->update(['user_role' => $roleString]);
           
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'User roles updated successfully'
            ]);
        }
    }

    public function getUserRoles($userRoleString)
    {
        return $userRoleString ? explode(',', $userRoleString) : [];
    }

    public function updatedCsvFile()
    {
        $this->validate();
        $this->resetImport();
        $this->processHeaders();
    }

    public function resetImport()
    {
        $this->importedCount = 0;
        $this->errorCount = 0;
        $this->processingComplete = false;
        $this->errors = [];
        $this->headers = [];
        $this->previewData = [];
        $this->showMappingStep = false;
    }

    public function processHeaders()
    {
        try {
            $path = $this->csvFile->getRealPath();
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
           
            $this->headers = $csv->getHeader();
            $records = $csv->getRecords();
           
            // Get first 5 rows for preview
            $this->previewData = [];
            $counter = 0;
            foreach ($records as $record) {
                if ($counter >= 5) break;
                $this->previewData[] = $record;
                $counter++;
            }
           
            $this->showMappingStep = true;
        } catch (\Exception $e) {
            $this->addError('csvFile', 'Error processing CSV: ' . $e->getMessage());
        }
    }

    public function import()
    {
        $this->validate();
        try {
            $path = $this->csvFile->getRealPath();
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();
            $this->importedCount = 0;
            $this->errorCount = 0;
            $this->errors = [];
           
            // Get current team and its plan
            $teamPlan = TeamSubscriptions::where('team_id', auth()->user()->currentTeam->id)
            ->where('status', 'active')
            ->where('plan_id',"!=",'Bonus')
            ->where('ends_at', '>', Carbon::now())
            ->latest('starts_at')
            ->first();
            $planType = $teamPlan->plan_id??'trial';
           
            // Get max users allowed based on plan type from environment variables
            $maxUsers =$this->activePlans[$planType];
            // Get current user count for this team
            $currentUserCount = User::where('current_team_id', $this->team_id)->count();
           
            // Calculate how many more users can be added
            $remainingSlots = $maxUsers - $currentUserCount;
         
            foreach ($records as $index => $record) {
                $rowIndex = $index + 2;
                $userData = $this->mapRow($record);
               
                if ($this->validateRow($userData, $rowIndex)) {
                    // Check if we've exceeded the plan limit
                    if ($remainingSlots <= 0) {
                        $this->errors['import']=["Your plan allows a maximum of {$maxUsers} users. Please upgrade your plan to add more users."];
                        $this->errorCount++;
                        continue;
                    }
                   
                    $user = new User();
                    $user->uuid = Str::uuid()->toString();
                    $user->name = $userData['name'];
                    $user->email = $userData['email'];
                    $user->security_code = $userData['security_code'];
                    $user->current_team_id = $this->team_id;
                    $user->email_verified_at = date('Y-m-d H:i:s');
                    $user->password = Hash::make($userData['security_code']);
                    $user->user_role=Role::select('id')->where("slug",5)->orderBy("id","DESC")->first()->id;
                    $user->unsubscribe_token = Str::random(32);
                    $user->status=1;
                    $user->save();
                   
                    $remainingSlots--;
                    $this->importedCount++;
                } else {
                    $this->errorCount++;
                }
            }
           
            $this->processingComplete = true;
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error creating users : ' . $e->getMessage());
            Log::error($e->getMessage());
        }
    }

    protected function mapRow($record)
    {
        $result = [];
       
        foreach ($this->mappings as $userField => $csvField) {
            $result[$userField] = $csvField && isset($record[$csvField]) ? $record[$csvField] : null;
        }
       
        return $result;
    }

    protected function validateRow($userData, $rowIndex)
    {
        $validator = Validator::make($userData, [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
            ],
            'security_code' => [
                'required',
            ],
            'mobile' => 'nullable|string|max:20'
        ]);
       
        if ($validator->fails()) {
            $rowErrors = [];
            foreach ($validator->errors()->toArray() as $field => $errors) {
                foreach ($errors as $error) {
                    $rowErrors[] = "Field '{$field}': {$error}";
                }
            }
            $this->errors[$rowIndex] = $rowErrors;
            return false;
        }
       
        return true;
    }
   
    public function downloadTemplate()
    {
        $headers = [
            'name', 'email', 'mobile', 'security_code'
        ];
       
        $sampleData = [
            ['John Doe', 'john@example.com', '1234567890', '111111'],
            ['Jane Smith', 'jane@example.com', '9876543210', '222222']
        ];
       
        $csv = \League\Csv\Writer::createFromString();
        $csv->insertOne($headers);
        $csv->insertAll($sampleData);
       
        return response()->streamDownload(function () use ($csv) {
            echo $csv->toString();
        }, 'user_import_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function confirmUserDeletion($userId)
    {
        $this->confirmingUserDeletion = true;
        $this->userIdBeingDeleted = $userId;
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->userIdBeingDeleted);
        $user->delete();
        $this->confirmingUserDeletion = false;
        $this->dispatch('notify-success', "User successfully deleted");
    }
   
    public function toggleUserStatus($userId)
    {
        try {
            $user = User::findOrFail($userId);
           
            // Toggle status: if active, make inactive; if inactive (or null), make active
            $newStatus = $user->status === '1' ? '2' : '1';
           
            $user->update(['status' => $newStatus]);
           
            // Show success message
            $statusText = $newStatus === '1' ? 'activated' : 'deactivated';
            session()->flash('message', "User {$user->name} has been {$statusText} successfully.");
           
            // Optional: Emit event for other components
            $this->emit('userStatusUpdated', $userId, $newStatus);
           
        } catch (\Exception $e) {
            // Handle error
            session()->flash('error', 'Failed to update user status. Please try again.');
           
            // Log the error for debugging
            \Log::error('Failed to toggle user status: ' . $e->getMessage(), [
                'user_id' => $userId,
                'error' => $e
            ]);
        }
    }

    public function updateUserStatus($userId)
    {
        if (empty($this->selectedStatus)) {
            return;
        }

        try {
            $user = User::findOrFail($userId);
            $user->update(['status' => $this->selectedStatus]);
            
            // Refresh the user model
            $this->user = $user->fresh();
            
            // Reset dropdown to show current status
            $this->selectedStatus = $this->user->status;
            
            // Optional: Show success message
            session()->flash('message', 'Status updated successfully');
            
        } catch (\Exception $e) {
            // Handle error
            session()->flash('error', 'Failed to update status');
            
            // Reset dropdown
            $this->selectedStatus = $this->user->status;
        }
    }

    public function getStatusOptions()
    {
        return [
            0 => 'Pending',
            1 => 'Active', 
            2 => 'Blocked',
            3 => 'Rejected'
        ];
    }

    public function getStatusLabel($status)
    {
        return $this->getStatusOptions()[$status] ?? 'Unknown';
    }
    public function render()
    {
        // Build the query with filters
        $query = User::where("current_team_id", $this->team_id);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $users = $query->orderBy("created_at", "DESC")->paginate(100);

        // Get counts for filter buttons
        $filterCounts = $this->getFilteredCounts();

        
        return view('livewire.user-content', [
            'users' => $users,
            'filterCounts' => $filterCounts,
        ])->layout('layouts.app');
    }
}