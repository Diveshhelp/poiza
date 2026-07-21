<?php

namespace App\Livewire;

use App\Models\Learning;
use App\Models\LearningUser;
use App\Models\User;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use DB;

class LearningManager extends Component
{
    use WithPagination;

    // Form properties
    public $learning_title;
    public $learning_description;
    public $comment;
    public $sort_order;
    
    // Search and filters
    public $search = '';
    public $serverSearch = '';
    public $filterByStatus = '';
    public $filterByUser = '';
    
    // Component state
    public $moduleTitle = 'Learning Program';
    public $isUpdate = false;
    public $editUuid;
    public $selectedLearningId;
    public $selectedUserId;
    public $viewMode = 'admin'; // 'admin' or 'user'
    public $showEditModal = false;
    public $showAssignModal = false;
    
    // Data collections
    public $allRecords = [];
    public $userList = [];
    public $adminList = [];
    public $programArray = [];
    public $sortableItems = [];
    
    // Messages
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    
    public string $filterByCompletion = '';
    public string $filterByUserProgress = '';
    // Properties
    public $commentText = '';
    public $showCommentModal = false;

    protected $queryString = [
        'serverSearch' => ['except' => ''],
        'filterByStatus' => ['except' => ''],
        'filterByCompletion' => ['except' => ''],
        'viewMode' => ['except' => 'admin'],
    ];
    // Reset pagination when filters change
    public function updatedServerSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterByStatus(): void
    {
        $this->resetPage();
    }

    public function updatedFilterByCompletion(): void
    {
        $this->resetPage();
    }

    public function updatedFilterByUser(): void
    {
        $this->resetPage();
    }

    public function updatedFilterByUserProgress(): void
    {
        $this->resetPage();
    }

    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

   
    public function clearAllFilters(): void
    {
        $this->serverSearch = '';
        $this->filterByStatus = '';
        $this->filterByCompletion = '';
        $this->filterByUser = '';
        $this->filterByUserProgress = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->sortBy = 'title_asc';
        $this->resetPage();
    }


    public function mount()
    {
        $this->resetForm();
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS ?? 'Learning Program created successfully');
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS ?? 'Learning Program updated successfully');
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS ?? 'Learning Program deleted successfully');
        
        $this->loadAllRecords();
        $this->loadUsers();
        $this->loadSortableItems();
          // Get user roles and handle as strings (since explode returns strings)
        $userRoles = explode(',', Auth::user()->user_role);
        
        // Check for admin role (1) - compare as string since explode returns strings
        if (in_array('1', $userRoles) || in_array(1, $userRoles)) {
            $this->viewMode = "admin";
        } else {
            $this->viewMode = "user";
        }
    }

    public function loadAllRecords()
    {
        if ($this->viewMode === 'user') {
            // Load only programs assigned to current user
            $userProgram = LearningUser::select('learning_id')
                ->where("user_id", Auth::user()->id)
                ->get()
                ->pluck('learning_id');
            
            $this->allRecords = Learning::whereIn("id", $userProgram)
                ->orderBy("sort_order", "ASC")
                ->get()
                ->toArray();
        } else {
            // Load all programs for admin view
            $this->allRecords = Learning::orderBy("sort_order", "ASC")
                ->get()
                ->toArray();
        }
        
        $this->buildProgramArray();
    }

    public function loadUsers()
    {
        $this->userList = User::select('name', 'id')
            // ->where("program_right", "0")
            ->orderBy("id", 'ASC')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
            
        $this->adminList = User::select('name', 'id')
            // ->where("program_right", "1")
            ->orderBy("id", 'ASC')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function buildProgramArray()
    {
        $this->programArray = [];
        
        foreach ($this->allRecords as $key => $learning) {
            $this->programArray[$key] = [
                'learning_id' => $learning['id'],
                'learning_title' => $learning['learning_title'],
                'learning_description' => $learning['learning_description'] ?? '',
                'status' => $learning['status'],
                'sort_order' => $learning['sort_order']
            ];
            
            // Get user assignments
            $userProgram = LearningUser::select('learning_id', 'user_id', 'admin_id', 'created_at')
                ->where("learning_id", $learning['id'])
                ->get()
                ->pluck("user_id", "user_id")
                ->toArray();
            $this->programArray[$key]['user'] = $userProgram;

            // Get detailed completion data with admin information
            $adminProgram = LearningUser::leftjoin('users as a', 'a.id', '=', 'learning_user.admin_id')
                ->leftjoin('users as u', 'u.id', '=', 'learning_user.user_id')
                ->select(
                    'a.name as admin_first_name', 
                    'u.name as user_first_name',
                    'learning_user.complete_on', 
                    'learning_user.learning_id', 
                    'learning_user.user_id', 
                    'learning_user.admin_id', 
                    'learning_user.comment', 
                    'learning_user.status',
                    'learning_user.created_at',
                    'learning_user.updated_at'
                )
                ->where("learning_user.learning_id", $learning['id'])
                ->get()
                ->keyBy("user_id");
            $this->programArray[$key]['admin'] = $adminProgram;
        }
    }

    public function updatingServerSearch()
    {
        $this->resetPage();
    }

    public function performServerSearch($searchTerm)
    {
        $this->serverSearch = $searchTerm;
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->serverSearch = '';
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->learning_title = '';
        $this->learning_description = '';
        $this->comment = '';
        $this->sort_order = '';
        $this->isUpdate = false;
        $this->editUuid = null;
        $this->selectedLearningId = null;
        $this->selectedUserId = null;
    }

    // Create modal handled by Alpine.js

    public function saveDataObject()
    {
        $this->validate([
            'learning_title' => 'required|string|max:255'
        ]);

        try {
            $learning = Learning::create([
                'learning_title' => trim($this->learning_title),
                'learning_description' => trim($this->learning_description),
                'status' => 1,
                'sort_order' => Learning::max('sort_order') + 1,
                'created_by' => auth()->id()
            ]);

            $this->dispatch('notify-success', $this->commonCreateSuccess);
            $this->loadAllRecords();
            $this->loadSortableItems();
            
            // Reset form
            $this->resetForm();
            
            // Dispatch event to close modal
            $this->dispatch('learning-created');
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error creating ' . $this->moduleTitle . ': ' . $e->getMessage());
        }
    }

    public function editLearning($learningId)
    {
        $learning = Learning::find($learningId);
        
        if ($learning) {
            $this->learning_title = $learning->learning_title;
            $this->learning_description = $learning->learning_description;
            $this->sort_order = $learning->sort_order;
            $this->editUuid = $learningId;
            $this->isUpdate = true;
            $this->showEditModal = true;
        } else {
            $this->dispatch('notify-error', $this->moduleTitle . ' not found.');
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function updateDataObject()
    {
        $this->validate([
            'learning_title' => 'required|string|max:255'
        ]);

        try {
            $learning = Learning::find($this->editUuid);
            
            if ($learning) {
                $learning->update([
                    'learning_title' => trim($this->learning_title),
                    'learning_description' => trim($this->learning_description),
                    'sort_order' => $this->sort_order,
                    'updated_by' => auth()->id()
                ]);

                $this->dispatch('notify-success', $this->commonUpdateSuccess);
                $this->loadAllRecords();
                $this->loadSortableItems();
                $this->closeEditModal();
            } else {
                $this->dispatch('notify-error', $this->moduleTitle . ' not found.');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to update ' . $this->moduleTitle . '. ' . $e->getMessage());
        }
    }

    public function deleteLearning($learningId)
    {
        try {
            $learning = Learning::find($learningId);
            
            if ($learning) {
                // Delete related learning_user records
                LearningUser::where("learning_id", $learningId)->delete();
                
                // Delete the learning record
                $learning->delete();

                $this->dispatch('notify-success', $this->commonDeleteSuccess);
            } else {
                $this->dispatch('notify-error', $this->moduleTitle . ' not found.');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to delete ' . $this->moduleTitle . '. ' . $e->getMessage());
        }
    }

    public function toggleStatus($learningId)
    {
        try {
            $learning = Learning::find($learningId);
            
            if ($learning) {
                $newStatus = $learning->status == 1 ? 0 : 1;
                $learning->update([
                    'status' => $newStatus,
                    'updated_by' => auth()->id()
                ]);

                $statusText = $newStatus == 1 ? 'activated' : 'deactivated';
                $this->dispatch('notify-success', $this->moduleTitle . ' ' . $statusText . ' successfully.');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to update status. ' . $e->getMessage());
        }
    }

    public function markAsCompleted($learningId, $userId)
    {
        try {
            // Check if record already exists
            $existingRecord = LearningUser::where('learning_id', $learningId)
                ->where('user_id', $userId)
                ->first();
                
            if (!$existingRecord) {
                LearningUser::create([
                    'learning_id' => $learningId,
                    'user_id' => $userId,
                    'admin_id' => Auth::user()->id,
                    'status' => 1,
                    'complete_on' => now(),
                    'created_by' => Auth::user()->id
                ]);
            } else {
                $existingRecord->update([
                    'status' => 1,
                    'complete_on' => now(),
                    'admin_id' => Auth::user()->id,
                    'updated_by' => Auth::user()->id
                ]);
            }

            $this->dispatch('notify-success', 'Program marked as completed successfully.');
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to mark as completed. ' . $e->getMessage());
        }
    }

    public function markAsIncomplete($learningId, $userId)
    {
        try {
            $existingRecord = LearningUser::where('learning_id', $learningId)
                ->where('user_id', $userId)
                ->first();
                
            if ($existingRecord) {
                $existingRecord->update([
                    'status' => 0,
                    'complete_on' => null,
                    'admin_id' => Auth::user()->id,
                    'updated_by' => Auth::user()->id
                ]);
                
                $this->dispatch('notify-success', 'Program marked as incomplete successfully.');
                $this->loadAllRecords();
            } else {
                $this->dispatch('notify-error', 'Learning assignment not found.');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to mark as incomplete. ' . $e->getMessage());
        }
    }

    public function openCommentModal($learningId)
    {
        $this->selectedLearningId = $learningId;
        
        // Load existing comment
        $learningUser = LearningUser::where('learning_id', $learningId)
            ->where('user_id', Auth::user()->id)
            ->first();
            
        $this->comment = $learningUser ? $learningUser->comment : '';
    }

    // Comment modal close handled by Alpine.js

    public function saveComment()
    {
        try {
            $learningUser = LearningUser::where('learning_id', $this->selectedLearningId)
                ->where('user_id', Auth::user()->id)
                ->first();
                
            if ($learningUser) {
                $learningUser->update([
                    'comment' => $this->comment,
                    'updated_by' => Auth::user()->id
                ]);
                
                $this->dispatch('notify-success', 'Comment saved successfully.');
                $this->loadAllRecords();
                
                // Reset form data
                $this->comment = '';
                $this->selectedLearningId = null;
                
                // Dispatch event to close modal
                $this->dispatch('comment-saved');
            } else {
                $this->dispatch('notify-error', 'Learning assignment not found.');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to save comment. ' . $e->getMessage());
        }
    }

    // Sort modal handled by Alpine.js, just need the data loading
    public function loadSortableItems()
    {
        $this->sortableItems = Learning::orderBy('sort_order', 'ASC')->get()->toArray();
    }

    public function updateSortOrder($orderedIds)
    {
        try {
            foreach ($orderedIds as $index => $id) {
                Learning::where('id', $id)->update(['sort_order' => $index + 1]);
            }
            
            $this->dispatch('notify-success', 'Sort order updated successfully.');
            $this->loadAllRecords();
            // Modal close handled by Alpine.js
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to update sort order. ' . $e->getMessage());
        }
    }

    public function assignUserToLearning($learningId, $userId)
    {
        try {
            // Check if already assigned
            $existing = LearningUser::where('learning_id', $learningId)
                ->where('user_id', $userId)
                ->first();
                
            if (!$existing) {
                LearningUser::create([
                    'learning_id' => $learningId,
                    'user_id' => $userId,
                    'admin_id' => Auth::user()->id,
                    'status' => 0,
                    'created_by' => Auth::user()->id
                ]);
                
                $this->dispatch('notify-success', 'User assigned to learning program successfully.');
                $this->loadAllRecords();
            } else {
                $this->dispatch('notify-warning', 'User is already assigned to this learning program.');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to assign user. ' . $e->getMessage());
        }
    }

    public function removeUserFromLearning($learningId, $userId)
    {
        try {
            LearningUser::where('learning_id', $learningId)
                ->where('user_id', $userId)
                ->delete();
                
            $this->dispatch('notify-success', 'User removed from learning program successfully.');
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to remove user. ' . $e->getMessage());
        }
    }

    public function getFilteredRecordsProperty()
    {
        $records = collect($this->allRecords);

        if (!empty($this->serverSearch)) {
            $searchTerm = strtolower($this->serverSearch);
            $records = $records->filter(function ($record) use ($searchTerm) {
                return str_contains(strtolower($record['learning_title']), $searchTerm) ||
                       str_contains(strtolower($record['learning_description'] ?? ''), $searchTerm);
            });
        }

        if (!empty($this->filterByStatus)) {
            $records = $records->where('status', $this->filterByStatus);
        }

        return $records;
    }
    
    public function toggleCompletion($learningId, $userId)
    {
        $learningUser = LearningUser::where('learning_id', $learningId)
                                   ->where('user_id', $userId)
                                   ->firstOrFail();

          
        if ($learningUser->complete_on || $learningUser->status === '1') {
            // Mark as incomplete
            $learningUser->update([
                'complete_on' => null,
                'status' => '0',
                'updated_by'=>Auth::user()->id
                
            ]);
            
            $message = 'User marked as incomplete';
        } else {
            // Mark as complete
            $learningUser->update([
                'complete_on' => now(),
                'status' => '1',
                'updated_by'=>Auth::user()->id
            ]);
            
            $message = 'User marked as completed';
        }

        
        $message = 'User marked as completed';

        $this->dispatch('notify-success',$message);
        
    }
    public function render()
        {
            $query = Learning::query()
            ->with(['users' => function($query) {
                $query->withPivot(['complete_on', 'status', 'comment', 'created_at'])
                    ->orderBy('name');
            }]);

        if ($this->viewMode === 'user') {
            $query->whereHas('users', function($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }

        if (!empty($this->serverSearch)) {
            $searchTerm = '%' . trim($this->serverSearch) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('learning_title', 'like', $searchTerm)
                ->orWhere('learning_description', 'like', $searchTerm);
            });
        }

        if (!empty($this->filterByStatus)) {
            $query->where('status', $this->filterByStatus);
        }

        if (!empty($this->filterByCompletion)) {
            if ($this->filterByCompletion === 'completed') {
                $query->whereHas('users', function($q) {
                    $q->whereNotNull('learning_user.complete_on')
                    ->orWhere('learning_user.status', 'completed');
                });
            } elseif ($this->filterByCompletion === 'incomplete') {
                $query->whereHas('users', function($q) {
                    $q->whereNull('learning_user.complete_on')
                    ->where(function($subQ) {
                        $subQ->where('learning_user.status', '!=', 'completed')
                            ->orWhereNull('learning_user.status');
                    });
                });
            }
        }

        $learnings = $query->orderBy('sort_order', 'ASC')
            ->paginate(10);

        // Calculate completion statistics for each learning
        $learnings->getCollection()->transform(function ($learning) {
            $totalUsers = $learning->users->count();
            $completedUsers = $learning->users->filter(function($user) {
                return $user->pivot->complete_on || $user->pivot->status === 'completed';
            })->count();
            
            $learning->total_users = $totalUsers;
            $learning->completed_users = $completedUsers;
            $learning->incomplete_users = $totalUsers - $completedUsers;
            $learning->completion_percentage = $totalUsers > 0 ? round(($completedUsers / $totalUsers) * 100, 2) : 0;
            
            return $learning;
        });

        return view('livewire.learning.data-collections', [
            'learnings' => $learnings,
            'user_list' => User::pluck('name', 'id')->toArray(),
            'view_mode' => $this->viewMode
        ])->layout('layouts.app');
    }
}