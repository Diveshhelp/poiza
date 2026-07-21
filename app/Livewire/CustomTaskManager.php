<?php

namespace App\Livewire;

use App\Models\CustomTask;
use App\Models\CustomTaskLog;
use App\Models\CustomTaskMaster;
use App\Models\CustomTaskUser;
use App\Models\User;
use Auth;
use DB;
use JsonSchema\Exception\ValidationException;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Log;

class CustomTaskManager extends Component
{
    use WithPagination;

    public $search = '';
    public $serverSearch = ''; 
    public $moduleTitle = 'Custom Task';
    public $isUpdate = false;
    public $editUuid;    
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $team_id;
    public $user_id;
    public $allRecords = []; 
    public $enableServerSearch = true; 
    protected $queryString = [
        'serverSearch' => ['except' => ''],
    ];
    public $allUsers;
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'performServerSearch' => 'performServerSearch',
        'toggleSearchMode' => 'toggleSearchMode'
    ];

    #[Rule('required|string|max:255')]
    public $title = '';

    #[Rule('required|in:onetime,repeat')]
    public $task_type = 'onetime';

    #[Rule('required|integer|min:0')]
    public $repeat_on_day = 1;

    #[Rule('nullable|date')]
    public $task_trigger_date = '';

    #[Rule('required|integer|min:0')]
    public $task_due_day = 1;

    #[Rule('required|exists:users,id')]
    public $assigned_to = '';

    // Search and Filters
    #[Rule('nullable|string|max:255')]
    public $statusFilter = 'all';
    
    public $allTasksCount = 0;
    public $activeTasksCount = 0;
    public $inactiveTasksCount = 0;
    public $onetimeTasksCount = 0;
    public $repeatTasksCount = 0;
    public $completedTasksCount = 0;
    public $pendingTasksCount = 0;

    public $transferTasksCount=0;
    public $myTasksCount=0;

    // Advanced Filters
    public $showAdvancedFilters = false;
    public $typeFilter = '';
    public $assignedToFilter = '';
    public $createdByFilter = '';
    public $fromDate = '';
    public $toDate = '';
    public $dateFilter = '';
    public $hasActiveFilters;
    public $activeFiltersCount = 0;
    public $showModal = false;
    public $customTaskId = null;
    public $user_role;

    // Action Modal Properties
    public $showActionModal = false;
    public $actionTaskMasterId = null;
    public $actionType = '';
    public $actionComment = '';
    public $transferToUserId = '';

    #[Rule('required|string|max:500')]
    public $actionCommentRule = '';

    #[Rule('required_if:actionType,transfer|exists:users,id')]
    public $transferToUserRule = '';
  
    public function mount()
    {
        $this->team_id = Auth::user()->currentTeam->id;
        $this->user_id = Auth::user()->id;
        $this->user_role=Auth::user()->user_role;
        
        $this->resetForm();
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, 'Custom Task created successfully');
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, 'Custom Task updated successfully');
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, 'Custom Task deleted successfully');
        
        $this->loadAllUsers();
        $this->calculateStatusCounts();
    }

    public function hasActiveFilters()
    {
        return !empty($this->search) || 
               !empty($this->typeFilter) || 
               !empty($this->assignedToFilter) || 
               !empty($this->createdByFilter) || 
               !empty($this->fromDate) || 
               !empty($this->toDate) ||
               $this->statusFilter !== 'all';
    }

    private function calculateStatusCounts()
    {
        $baseQuery = CustomTaskMaster::query();

        if(!in_array($this->user_role,[1,2])){
            $baseQuery->where('assign_to', $this->user_id);
            $baseQuery->where('status', "1");
        }

        $this->allTasksCount = $baseQuery->count();
        
        // Active Tasks
        $this->activeTasksCount = (clone $baseQuery)
            ->where('status', CustomTask::STATUS_ACTIVE)
            ->count();
    
        // Inactive Tasks
        $this->inactiveTasksCount = (clone $baseQuery)
            ->where('status', CustomTask::STATUS_INACTIVE)
            ->count();
        
       

        $this->transferTasksCount=(clone $baseQuery)
            ->transferred()
            ->count();


        $this->myTasksCount=(clone $baseQuery)
            ->myTask()
            ->count();

            
        // Completed Tasks (from task masters)
        $this->completedTasksCount = (clone $baseQuery)
            ->completed()
            ->count();
        
        // Pending Tasks (from task masters)
        $this->pendingTasksCount = CustomTaskMaster::pending()->count();
    }

    private function loadAllUsers()
    {
        $this->allUsers = User::where("current_team_id", $this->team_id)
            ->orderBy('name')
            ->get();
    }

    public function setStatusFilter($filter)
    {
        $this->statusFilter = $filter;
        $this->resetPage();
    }

    public function getFilterTitle()
    {
        return match($this->statusFilter) {
            'all' => 'All Tasks',
            'active' => 'Active Tasks',
            'inactive' => 'Inactive Tasks',
            'transfer' => 'Transfered Tasks',
            'completed' => 'Completed Tasks',
            'pending' => 'Pending Tasks',
            'mytask' => 'My Tasks',
            default => 'All Tasks'
        };
    }

    public function getFilterDescription()
    {
        return match($this->statusFilter) {
            'all' => 'Comprehensive view of all custom tasks in the system',
            'active' => 'Currently active custom tasks',
            'inactive' => 'Inactive custom tasks',
            'onetime' => 'Tasks that run only once',
            'repeat' => 'Tasks that repeat periodically',
            'completed' => 'Tasks that have been completed',
            'pending' => 'Tasks awaiting completion',
            'transfer' => 'Tasks forwarded from another employee',
            'mytask' => 'All my tasks and need to be done as per dead line ',
            default => 'Comprehensive custom task management and tracking'
        };
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->isUpdate = false;
        $this->customTaskId = null;
        $this->title = '';
        $this->task_type = 'onetime';
        $this->repeat_on_day = 0;
        $this->task_trigger_date = '';
        $this->task_due_day = 0;
        $this->assigned_to = '';
    }
    public function editTask($taskId)
    {
        $task = CustomTask::with('users')->findOrFail($taskId);
       
        $this->isUpdate = true;
        $this->customTaskId = $taskId;
        $this->title = $task->title;
        $this->task_type = $task->task_type;
        $this->repeat_on_day = $task->repeat_on_day;
        $this->task_trigger_date = $task->task_trigger_date ? $task->task_trigger_date->format('Y-m-d') : '';
        $this->task_due_day = $task->task_due_day;
        $this->assigned_to = $task->users->pluck('id')->toArray();
        $this->showModal = true;
    }   


    private function logUserAction($action, $details = [])
    {
        Log::info("User action: {$action}", array_merge([
            'user_id' => $this->user_id,
            'user_name' => Auth::user()->name ?? 'Unknown',
            'team_id' => $this->team_id,
            'timestamp' => now()->toISOString(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ], $details));
    }
    
    /**
     * Handle database transaction with proper error handling
     */
    private function executeInTransaction(callable $callback, $errorMessage = 'Database operation failed')
    {
        try {
            DB::beginTransaction();
            
            $result = $callback();
            
            DB::commit();
            return $result;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error($errorMessage, [
                'user_id' => $this->user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Validate task data before processing
     */
    private function validateTaskData()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'task_type' => 'required|in:onetime,repeat',
            'assigned_to' => 'required|array|min:1',
            'assigned_to.*' => 'required|exists:users,id',
        ];
    
        if ($this->task_type === 'onetime') {
            $rules['task_trigger_date'] = 'required|date|after_or_equal:today';
        } else {
            $rules['repeat_on_day'] = 'required|integer|min:1|max:31';
            $rules['task_due_day'] = 'required|integer|min:1|max:31';
        }
    
        $messages = [
            'title.required' => 'Task title is required.',
            'task_type.required' => 'Please select a task type.',
            'assigned_to.required' => 'Please assign at least one user to this task.',
            'task_trigger_date.required' => 'Task trigger date is required for one-time tasks.',
            'task_trigger_date.after_or_equal' => 'Task trigger date cannot be in the past.',
            'repeat_on_day.required' => 'Repeat day is required for recurring tasks.',
            'task_due_day.required' => 'Due day is required for recurring tasks.',
        ];
    
        return $this->validate($rules, $messages);
    }
    
    /**
     * Alternative saveTask method using helper methods
     */
    public function saveTask()
    {
        try {
            // Validate input
            $this->validateTaskData();
    
            // Execute in transaction
            $this->executeInTransaction(function () {
                $data = [
                    'title' => $this->title,
                    'task_type' => $this->task_type,
                    'repeat_on_day' => $this->repeat_on_day,
                    'task_trigger_date' => $this->task_trigger_date ?: null,
                    'task_due_day' => $this->task_due_day,
                    'status' => CustomTask::STATUS_ACTIVE,
                    'created_by' => $this->user_id,
                    'updated_by' => $this->user_id,
                    'team_id' => $this->team_id,
                    'uuid' => Str::uuid()->toString(),
                ];
    
                if ($this->isUpdate && $this->customTaskId) {
                    return $this->updateExistingTask($data);
                } else {
                    return $this->createNewTask($data);
                }
            }, 'Failed to save task');
    
            $this->closeModal();
            $this->resetPage();
            $this->calculateStatusCounts();
    
        } catch (ValidationException $e) {
            $this->logUserAction('Task validation failed', ['errors' => $e->errors()]);
            throw $e;
            
        } catch (\Exception $e) {
            $this->logUserAction('Task save failed', ['error' => $e->getMessage()]);
            $this->dispatch('notify-error', 'Failed to save task: ' . $e->getMessage());
        }
    }
    
    /**
     * Update existing task
     */
    private function updateExistingTask($data)
    {
        $task = CustomTask::findOrFail($this->customTaskId);
        $task->update($data);
    
        $this->logUserAction('Task updated', [
            'task_id' => $task->id,
            'title' => $this->title
        ]);
    
        $this->dispatch('notify-success', $this->commonUpdateSuccess);
        return $task;
    }
    
    /**
     * Create new task with assignments
     */
    private function createNewTask($data)
    {
        $task = CustomTask::create($data);
    
        $this->logUserAction('Task created', [
            'task_id' => $task->id,
            'title' => $this->title,
            'assigned_users' => count($this->assigned_to)
        ]);
    
        // Create assignments
        $this->createTaskAssignments($task);
    
        $this->dispatch('notify-success', $this->commonCreateSuccess);
        return $task;
    }
    
    /**
     * Create task assignments with error handling
     */
    private function createTaskAssignments($task)
    {
        if (empty($this->assigned_to)) {
            return;
        }
    
        foreach ($this->assigned_to as $userId) {
            try {
                // Create user assignment
                CustomTaskUser::create([
                    'custom_task_id' => $task->id,
                    'user_id' => $userId,
                    'created_by' => $this->user_id
                ]);
    
                // Create task log
                CustomTaskLog::create([
                    'custom_task_id' => $task->id,
                    'assign_to' => $userId,
                    'created_by' => $this->user_id
                ]);
    
                // Create task master for onetime tasks
                if ($this->task_type === "onetime") {
                    $this->createTaskMasterForUser($task, $userId);
                }
    
                $this->logUserAction('User assigned to task', [
                    'task_id' => $task->id,
                    'assigned_user_id' => $userId
                ]);
    
            } catch (\Exception $e) {
                Log::error('Failed to assign user to task', [
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
    
                throw new \Exception("Failed to assign user ID {$userId}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Create task master for a specific user
     */
    private function createTaskMasterForUser($task, $userId)
    {
        $taskDate = $this->task_trigger_date ? 
            date('Y-m-d', strtotime(str_replace("/", "-", $this->task_trigger_date))) :
            date('Y-m-' . $this->task_due_day);
    
        $taskMaster = CustomTaskMaster::create([
            'custom_task_id' => $task->id,
            'assign_to' => $userId,
            'task_date' => $taskDate,
            'created_by' => $this->user_id,
            'team_id' => $this->team_id,
            'uuid' => Str::uuid()->toString()
        ]);
    
        $this->logUserAction('Task master created', [
            'task_id' => $task->id,
            'task_master_id' => $taskMaster->id,
            'assigned_user_id' => $userId,
            'task_date' => $taskDate
        ]);
    
        return $taskMaster;
    }
    
    /**
     * Handle errors gracefully and show user-friendly messages
     */
    private function handleError(\Exception $e, $context = 'operation')
    {
        $userMessage = match(true) {
            $e instanceof ValidationException => 'Please check your input and try again.',
            $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException => 'The requested item was not found.',
            $e instanceof \Illuminate\Database\QueryException => 'A database error occurred. Please try again.',
            str_contains($e->getMessage(), 'duplicate') => 'This item already exists.',
            str_contains($e->getMessage(), 'foreign key') => 'This item is being used elsewhere and cannot be modified.',
            default => 'An unexpected error occurred. Please contact support if this persists.'
        };
    
        Log::error("Error during {$context}", [
            'user_id' => $this->user_id,
            'error_type' => get_class($e),
            'error_message' => $e->getMessage(),
            'context' => $context
        ]);
    
        $this->dispatch('notify-error', $userMessage);
    }

    public function deleteTask($taskId)
    {
        $task = CustomTask::findOrFail($taskId);
        $task->delete();

        $this->dispatch('notify-success', $this->commonDeleteSuccess);
        $this->resetPage();
        $this->calculateStatusCounts();
    }

    public function toggleStatus($taskId)
    {
        $task = CustomTaskMaster::findOrFail($taskId);
        $task->update([
            'status' => $task->status === CustomTask::STATUS_ACTIVE ? CustomTask::STATUS_INACTIVE : CustomTask::STATUS_ACTIVE,
            'updated_by' => $this->user_id
        ]);

        $this->dispatch('notify-success', 'Task status updated successfully!');
        $this->calculateStatusCounts();
    }

    public function assignUser($taskId, $userId)
    {
        // Check if user is already assigned
        $existingAssignment = CustomTaskUser::where('custom_task_id', $taskId)
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->first();

        if (!$existingAssignment) {
            CustomTaskUser::create([
                'custom_task_id' => $taskId,
                'user_id' => $userId,
                'created_by' => $this->user_id
            ]);

            CustomTaskLog::create([
                'custom_task_id' => $taskId,
                'assign_to' => $userId,
                'created_by' => $this->user_id
            ]);

            $this->dispatch('notify-success', 'User assigned to task successfully!');
        } else {
            $this->dispatch('notify-error', 'User is already assigned to this task!');
        }
    }

    public function createTaskMaster($taskId, $assignToUserId, $taskDate = null)
    {
        CustomTaskMaster::create([
            'custom_task_id' => $taskId,
            'task_date' => $taskDate ?: now()->format('Y-m-d'),
            'assign_to' => $assignToUserId,
            'status' => CustomTaskMaster::STATUS_ACTIVE,
            'created_by' => $this->user_id
        ]);

        $this->dispatch('notify-success', 'Task instance created successfully!');
    }

    public function clearAllFilters()
    {
        $this->reset([
            'search',
            'typeFilter', 
            'assignedToFilter',
            'createdByFilter',
            'fromDate',
            'toDate',
            'dateFilter',
            'serverSearch'
        ]);
        
        $this->statusFilter = 'all';
        $this->showAdvancedFilters = false;
        $this->resetPage();
        $this->calculateStatusCounts();
        
        $this->dispatch('notify-success', 'All filters cleared successfully!');
    }

    public function clearDateFilters()
    {
        $this->reset(['fromDate', 'toDate', 'dateFilter']);
        $this->resetPage();
    }

    public function setDateFilter($period)
    {
        $this->dateFilter = $period;
        
        switch($period) {
            case 'today':
                $this->fromDate = now()->format('Y-m-d');
                $this->toDate = now()->format('Y-m-d');
                break;
            case 'yesterday':
                $this->fromDate = now()->subDay()->format('Y-m-d');
                $this->toDate = now()->subDay()->format('Y-m-d');
                break;
            case 'this_week':
                $this->fromDate = now()->startOfWeek()->format('Y-m-d');
                $this->toDate = now()->endOfWeek()->format('Y-m-d');
                break;
            case 'last_week':
                $this->fromDate = now()->subWeek()->startOfWeek()->format('Y-m-d');
                $this->toDate = now()->subWeek()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->fromDate = now()->startOfMonth()->format('Y-m-d');
                $this->toDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->fromDate = now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->toDate = now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
        }
        
        $this->resetPage();
    }

    // Update methods for filters
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedStatusFilter()
    {
        $this->resetPage();
        $this->calculateStatusCounts();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedAssignedToFilter()
    {
        $this->resetPage();
    }

    public function updatedCreatedByFilter()
    {
        $this->resetPage();
    }

    public function updatedFromDate()
    {
        $this->resetPage();
        $this->dateFilter = '';
    }

    public function updatedToDate()
    {
        $this->resetPage();
        $this->dateFilter = '';
    }

    public function activeFiltersCount()
    {
        $count = 0;
        if (!empty($this->search)) $count++;
        if (!empty($this->typeFilter)) $count++;
        if (!empty($this->assignedToFilter)) $count++;
        if (!empty($this->createdByFilter)) $count++;
        if (!empty($this->fromDate)) $count++;
        if (!empty($this->toDate)) $count++;
        if ($this->statusFilter !== 'all') $count++;
        
        return $count;
    }


    // Action Modal Methods
    public function openActionModal($taskMasterId, $actionType)
    {
        $this->actionTaskMasterId = $taskMasterId;
        $this->actionType = $actionType;
        $this->actionComment = '';
        $this->transferToUserId = '';
        $this->showActionModal = true;
        $this->resetValidation(['actionComment', 'transferToUserId']);
    }

    public function closeActionModal()
    {
        $this->showActionModal = false;
        $this->actionTaskMasterId = null;
        $this->actionType = '';
        $this->actionComment = '';
        $this->transferToUserId = '';
        $this->resetValidation(['actionComment', 'transferToUserId']);
    }

    public function submitAction()
    {
        // Validate based on action type
        $rules = [
            'actionComment' => 'required|string|max:500'
        ];

        if ($this->actionType === 'transfer') {
            $rules['transferToUserId'] = 'required|exists:users,id';
        }

        $this->validate($rules, [
            'actionComment.required' => 'Comment is required',
            'actionComment.max' => 'Comment must be less than 500 characters',
            'transferToUserId.required' => 'Please select a user to transfer to',
            'transferToUserId.exists' => 'Selected user is invalid'
        ]);

        try {
            $taskMaster = CustomTaskMaster::find($this->actionTaskMasterId);
            
            if (!$taskMaster) {
                $this->dispatch('notify-error', 'Task not found.');
                return;
            }

            if ($this->actionType === 'done') {
                $this->markTaskAsDone($taskMaster);
            } elseif ($this->actionType === 'transfer') {
                $this->transferTask($taskMaster);
            }

        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'An error occurred while processing the task: ' . $e->getMessage());
        }
    }

    private function markTaskAsDone($taskMaster)
    {  
        $CustomTaskMaster=CustomTaskMaster::find($taskMaster->id);
        $CustomTaskMaster->task_done_on=now();
        $CustomTaskMaster->comment=$this->actionComment;
        $CustomTaskMaster->save();

        
        $this->dispatch('notify-success', 'Task marked as done successfully.');
        $this->closeActionModal();
        $this->calculateStatusCounts();
    }
    public function getTaskCompletedDate($taskId)
    {
        return CustomTaskMaster::where('custom_task_id', $taskId)
            ->with(['assignedUser'])
            ->get()
            ->map(function ($taskMaster) {
                return [
                    'id' => $taskMaster->id,
                    'task_done_on' => $taskMaster->task_done_on??NULL,
                    'formatted_date' => $taskMaster->task_done_on ? $taskMaster->task_done_on->format('M d, Y h:i A'): NULL,
                ];
            });
    }
    private function transferTask($taskMaster)
    {
        $CustomTaskMaster=CustomTaskMaster::find($taskMaster->id);
        $CustomTaskMaster->assign_to=$this->transferToUserId;
        $CustomTaskMaster->is_transfer="1";
        $CustomTaskMaster->comment=$this->actionComment;
        $CustomTaskMaster->save();

        // Log the transfer action
        CustomTaskLog::create([
            'custom_task_id' => $taskMaster->custom_task_id,
            'assign_to' => $this->transferToUserId,
            'created_by' => $this->user_id
        ]);

        $transferredToUser = User::find($this->transferToUserId);
        $this->dispatch('notify-success', "Task transferred to {$transferredToUser->name} successfully.");
        $this->closeActionModal();
        $this->calculateStatusCounts();
    }

    public function render()
    {
        $query = CustomTaskMaster::with([
            'creator', 
            'updater',
            'assignedUser',
            'task'
        ]);

        // // Apply status filter
        switch($this->statusFilter) {
            case 'active':
                $query->active();
                break;
            case 'inactive':
                $query->inactive();
                break;
            case 'onetime':
                $query->onetime();
                break;
            case 'repeat':
                $query->repeat();
                break;
            case 'completed':
                $query->completed();
                break;
            case 'pending':
                $query->pending();
                break;
            case 'transfer':
                $query->transferred();
                break;
            case 'mytask':
                $query->myTask();
                break;
            case 'all':
            default:
                // No additional filtering
                break;
        }

        // // Apply type filter
        if ($this->typeFilter) {
            switch($this->typeFilter) {
                case 'active':
                    $query->active();
                    break;
                case 'inactive':
                    $query->inactive();
                    break;
                case 'onetime':
                    $query->onetime();
                    break;
                case 'repeat':
                    $query->repeat();
                case 'completed':
                    $query->completed();
                    break;
                case 'transfer':
                    $query->transferred();
                    break;
            }
        }

        // // Apply assigned to filter
        if ($this->assignedToFilter) {
            if ($this->assignedToFilter === 'unassigned') {
                $query->whereDoesntHave('users');
            } else {
                $query->where('assign_to', $this->assignedToFilter);
            }
        }

        // Apply created by filter
        if ($this->createdByFilter) {
            $query->where('created_by', $this->createdByFilter);
        }

        // Apply date filters
        if ($this->fromDate) {
            $query->whereDate('created_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('creator', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('users', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if(!in_array($this->user_role,[1,2])){
            $query->where('assign_to', $this->user_id);
            $query->where('status', "1");
            $query->whereHas('task', function($q) {
                $q->where('task_type', 'onetime');
            });
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(10);

        // Recalculate status counts for real-time updates
        $this->calculateStatusCounts();

        return view('livewire.custom-task.data-collections', [
            'tasks' => $tasks,
            'users' => $this->allUsers,
        ])->layout('layouts.app');
    }
}