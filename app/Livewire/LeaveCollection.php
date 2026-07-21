<?php

namespace App\Livewire;

use App\Models\Leave;
use App\Models\LeaveAttachment;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;

class LeaveCollection extends Component
{
    use WithPagination;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $searchQuery = '';
    public $moduleTitle = LEAVE_TITLE;
    public $perPage = PER_PAGE;

    public $filterEmployee = '';
    public $filterStartDate = '';
    public $filterEndDate = '';
    public $filterStatus = '';
    public $filterReason = '';
    public $filterTotalDays = '';
    public $rejectionReason = '';
    public $showRejectionModal = false;
    public $selectedLeaveUuid = null;
    
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonNotDeleteSuccess;
    public $commonStatusUpdateSuccess;
    
    public $showModal = false;
    public $selectedLeave;

    protected $listeners = ['leaveDeleted' => '$refresh'];
    public $status_list = ['pending', 'approved', 'rejected', 'cancelled'];
    protected $leaves = [];
    public $userList;
    public $user_id;
    public $team_id;
    public $team_name;

    public function mount()
    {
        $user = Auth::user();
        $userRoles = explode(',', $user->user_role);
        $isAdmin = in_array('1', $userRoles) || in_array('2', $userRoles);
            
        $this->user_id = session('session_user_id');//Auth::User()->id;
        $this->team_id = Auth::user()->currentTeam->id;
        $this->team_name = Auth::user()->currentTeam->name;
        $this->userList = $isAdmin ? User::all() : collect([Auth::user()]);
        
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonStatusUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_STATUS_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
        $this->userList=User::all();
        $this->loadLeaves();
    }

    public function downloadAttachment($attachmentId)
    {
        try {
            $attachment = LeaveAttachment::findOrFail($attachmentId);
            if (!Storage::disk('public')->exists($attachment->file_path)) {
                $this->dispatch('notify-error', 'File not found');
                return;
            }
    
            return response()->streamDownload(function () use ($attachment) {
                echo Storage::disk('public')->get($attachment->file_path);
            }, $attachment->original_file_name);
            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to download file: ' . $e->getMessage());
            return null;
        }
    }

    public function showLeaveDetails($uuid)
    {
        $this->selectedLeave = Leave::with(['attachments', 'user', 'approver'])->where('uuid', $uuid)->first();
        $this->dispatch('show-leave-modal', ['leave' => $this->selectedLeave]);
        $this->loadLeaves();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedLeave = null;
    }

    public function deleteLeave($uuid)
    {
        try {
            $leave = Leave::where('uuid', $uuid)->firstOrFail();
            
            // Delete associated attachments
            if ($leave->attachments) {
                foreach ($leave->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment->file_path);
                    $attachment->delete();
                }
            }
            
            $leave->delete();
            
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        } catch (\Exception $e) {
            $this->dispatch('notify-error', $this->commonNotDeleteSuccess);
        }
    }

    
    public function updateStatus($uuid, $status, $rejectionReason = null)
    {
        try {
            $leave = Leave::where('uuid', $uuid)->firstOrFail();
            $leave->status = $status;
            
            // Reset previous status data
            $leave->approved_by = null;
            $leave->approved_at = null;
            $leave->rejected_by = null;
            $leave->rejection_reason = null;
            
            // Set new status data based on the status
            switch ($status) {
                case 'approved':
                    $leave->approved_by = Auth::id();
                    $leave->approved_at = Carbon::now();
                    break;
                    
                case 'rejected':
                    if (empty($rejectionReason)) {
                        throw new \Exception('Rejection reason is required');
                    }
                    $leave->rejected_by = Auth::id();
                    $leave->rejection_reason = $rejectionReason;
                    break;
            }
            
            $leave->save();
            
            // Dispatch events for frontend updates
            $this->dispatch('status-updated', [
                'uuid' => $uuid,
                'status' => $status
            ]);
            $this->dispatch('notify-success', $this->commonStatusUpdateSuccess);
            
            // Reset rejection modal state
            $this->showRejectionModal = false;
            $this->rejectionReason = '';
            $this->selectedLeaveUuid = null;
            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error updating status: ' . $e->getMessage());
        }
    }

    public function openRejectionModal($uuid)
    {
        $this->selectedLeaveUuid = $uuid;
        $this->showRejectionModal = true;
        $this->rejectionReason = '';
    }

    public function closeRejectionModal()
    {
        $this->showRejectionModal = false;
        $this->rejectionReason = '';
        $this->selectedLeaveUuid = null;
    }

    public function submitRejection()
    {
        $this->validate([
            'rejectionReason' => 'required|min:3|max:500',
            'selectedLeaveUuid' => 'required'
        ]);

        $this->updateStatus($this->selectedLeaveUuid, 'rejected', $this->rejectionReason);
    }

    #[On('status-updated')] 
    public function refresh()
    {
        $this->loadLeaves();
    }
    protected function loadLeaves()
    {
        try {
            $query = Leave::with(['attachments', 'user', 'approver']);
            
            // Get current user and their roles
            $user = Auth::user();
            $userRoles = explode(',', $user->user_role);
            $isAdmin = in_array('1', $userRoles) || in_array('2', $userRoles);
            $query->where('team_id', $this->team_id);
            // If not Super Admin or Admin, only show user's leaves
            if (!$isAdmin) {
                $query->where('user_id', $user->id);
            }

            // Apply filters
            $query->when($this->filterEmployee, function ($query) use ($isAdmin) {
                // Only allow employee filter for admin users
                    $query->where('user_id', $this->filterEmployee);
            })
            ->when($this->filterStartDate, function ($query) {
                $query->whereDate('start_date', '>=', $this->filterStartDate);
            })
            ->when($this->filterEndDate, function ($query) {
                $query->whereDate('end_date', '<=', $this->filterEndDate);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterReason, function ($query) {
                $query->where('reason', 'like', '%' . $this->filterReason . '%');
            })
            ->when($this->filterTotalDays, function ($query) {
                $query->where('total_days', $this->filterTotalDays);
            });

            // Apply sorting
            $query->orderBy($this->sortField, $this->sortDirection);

            // Get paginated results
            $this->leaves = $query->paginate($this->perPage);

        } catch (\Exception $e) {
            \Log::error('Error in loadLeaves: ' . $e->getMessage());
            $this->leaves = collect([])->paginate($this->perPage);
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadLeaves(); // Reload leaves with new sorting
    }

    public function updatedPage()
    {
        $this->loadLeaves(); // Reload leaves when page changes
    }
    
    #[On('filter-applied')]
    public function handleFilter($filters)
    {
        $this->filterEmployee = $filters['employee'];
        $this->filterStartDate = $filters['startDate'];
        $this->filterEndDate = $filters['endDate'];
        $this->filterStatus = $filters['status'];
        $this->filterReason = $filters['reason'];
        $this->filterTotalDays = $filters['totalDays'];

        $this->resetPage();
        $this->loadLeaves();
    }

    #[On('filter-reset')]
    public function handleFilterReset()
    {
        $this->reset([
            'filterEmployee',
            'filterStartDate',
            'filterEndDate',
            'filterStatus',
            'filterReason',
            'filterTotalDays'
        ]);

        $this->resetPage();
        $this->loadLeaves();
    }
    public function searchLeaves()
    {
        $this->validate([
            'filterStartDate' => 'nullable|date',
            'filterEndDate' => 'nullable|date|after_or_equal:filterStartDate',
            'filterTotalDays' => 'nullable|numeric|min:0'
        ]);

        $filters = [
            'employee' => $this->filterEmployee,
            'startDate' => $this->filterStartDate,
            'endDate' => $this->filterEndDate,
            'status' => $this->filterStatus,
            'reason' => $this->filterReason,
            'totalDays' => $this->filterTotalDays
        ];

        $this->dispatch('filter-applied', $filters);
    }

    public function resetSearch()
    {
        $this->reset([
            'filterEmployee',
            'filterStartDate',
            'filterEndDate',
            'filterStatus',
            'filterReason',
            'filterTotalDays'
        ]);

        $this->dispatch('filter-reset');
    }

    public function render()
    {
        $this->loadLeaves();
        return view('livewire.Leave.leave-collection', [
            'leaves' => $this->leaves            
        ])->layout('layouts.app');
    }
}