<?php

namespace App\Livewire;

use App\Models\Authority;
use App\Models\Branches;
use App\Models\NatureOfWork;
use App\Models\Ticket;
use App\Models\User;
use Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class TicketManager extends Component
{
    use WithPagination;

    public $search = '';
    public $serverSearch = ''; 
    public $moduleTitle = TICKET_TITLE;
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
    public $ticket_unique_no = '';

    #[Rule('required|exists:branch,id')]
    public $branch_id = '';

    #[Rule('required|string|max:512')]
    public $establish_name = '';

    #[Rule('required|exists:users,id')]
    public $generated_by = '';

    #[Rule('required|exists:users,id')]
    public $work_alloted_to = '';

    #[Rule('required|exists:nature_of_work,id')]
    public $nature_of_work_id = '';

    // Search
    #[Rule('nullable|string|max:255')]
    
    public $branches = [];
    public $users = [];
    public $natureofwork=[];
    public $showModal = false;

    // Status counts (computed properties)
    
    #[Rule('nullable|string')]
    public $statusFilter = 'all';
    public $allTicketsCount = 0;
    public $createdApprovalCount = 0;
    public $closedApprovalCount = 0;
    public $runningTicketsCount = 0;
    public $closedTicketsCount = 0;
    public $transferredRunningCount = 0;
    public $transferredClosedCount = 0;

    // Advanced Filters
    public $showAdvancedFilters = false;
    public $typeFilter = '';
    public $generatedByFilter = '';
    public $allocatedToFilter = '';
    public $natureOfWorkFilter = '';
    public $branchFilter = '';
    public $approvedByFilter = '';
    public $priorityFilter = '';
    public $fromDate = '';
    public $toDate = '';
    public $dateFilter = '';
    public $hasActiveFilters;
    public $activeFiltersCount=0;
    public $user_role;
    public $ticketId;   
    public $showTransferDropdown = false;
    public $selectedTicketId = null;
    public $selectedUserId = null;
    public $availableUsers = [];
    public function mount()
    {
        $this->team_id = Auth::user()->currentTeam->id;
        $this->user_id = Auth::user()->id;
        $this->user_role=Auth::user()->user_role;
        
        $this->resetForm();
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        
        // Load all records for client-side filtering
        $this->loadAllRecords();
        $this->allUsers=User::where("current_team_id",$this->team_id)->get();
        
        $this->loadRelationshipData();
        $this->calculateStatusCounts();
        $this->availableUsers = User::where('id', '!=', auth()->id())
                                   ->where('status', "1") // assuming you have active users
                                   ->where("current_team_id",$this->team_id)
                                   ->orderBy('name')
                                   ->get(['id', 'name']);

    }

    public function hasActiveFilters()
    {
        return !empty($this->search) || 
               !empty($this->typeFilter) || 
               !empty($this->generatedByFilter) || 
               !empty($this->allocatedToFilter) || 
               !empty($this->natureOfWorkFilter) || 
               !empty($this->branchFilter) || 
               !empty($this->approvedByFilter) || 
               !empty($this->priorityFilter) || 
               !empty($this->fromDate) || 
               !empty($this->toDate) ||
               $this->statusFilter !== 'all';
    }
   

    public function setStatusFilter($filter)
    {
        $this->statusFilter = $filter;
        $this->resetPage(); // Reset pagination when filter changes
    }

    public function getFilterTitle()
    {
        return match($this->statusFilter) {
            'all' => 'All Tickets',
            'created_approval' => 'Created Approval Pending',
            'closed_approval' => 'Closed Approval Pending',
            'running' => 'Running Tickets',
            'closed' => 'Closed Tickets',
            'transferred_running' => 'Transferred Running Tickets',
            'transferred_closed' => 'Transferred Closed Tickets',
            default => 'All Tickets'
        };
    }

    public function getFilterDescription()
    {
        return match($this->statusFilter) {
            'all' => 'Comprehensive view of all tickets in the system',
            'created_approval' => 'Tickets waiting for initial approval',
            'closed_approval' => 'Tickets awaiting closure approval',
            'running' => 'Active tickets currently in progress',
            'closed' => 'Completed and approved tickets',
            'transferred_running' => 'Active tickets that have been transferred',
            'transferred_closed' => 'Closed tickets that were transferred',
            default => 'Comprehensive ticket management and tracking'
        };
    }
    private function loadRelationshipData()
    {
        $this->branches = Branches::select('id', 'title')
            ->where('status', "1")
            ->orderBy('title')
            ->get();

        $this->users = User::select('id', 'name', 'email')
            // ->where('status', "1")
            ->where("current_team_id",$this->team_id)
            ->orderBy('name')
            ->get();

        $this->natureofwork = NatureOfWork::select('id', 'title')
        ->where('status', "1")
        ->orderBy('title')
        ->get();

        
       
    }

    public function loadAllRecords()
    {
        $this->allRecords = Authority::where("team_id", $this->team_id)
            ->with('createdUser')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }


   
    public function editTicket($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        
        $this->isUpdate = true;
        $this->ticketId = $ticketId;
        $this->ticket_unique_no = $ticket->ticket_unique_no;
        $this->branch_id = $ticket->branch_id;
        $this->establish_name = $ticket->establish_name;
        $this->generated_by = $ticket->generated_by;
        $this->work_alloted_to = $ticket->work_alloted_to;
        $this->nature_of_work_id = $ticket->nature_of_work_id;
        
        $this->showModal = true;
    }

    public function saveTicket()
    {

        $data = [
            'ticket_unique_no' => $this->ticket_unique_no,
            'branch_id' => $this->branch_id,
            'establish_name' => $this->establish_name,
            'generated_by' => $this->generated_by,
            'work_alloted_to' => $this->work_alloted_to,
            'nature_of_work_id' => $this->nature_of_work_id,
            'status' => '1',
            'created_by' => auth()->id(),
            'team_d' => $this->team_id,
            'user_id' => $this->user_id,
            'uuid'=>Str::uuid()->toString(),
            'created_at'=>now(),
            'updated_at'=>now(),
        ];

        if ($this->isUpdate && $this->ticketId) {
            // Update existing ticket
            $ticket = Ticket::findOrFail($this->ticketId);
            $ticket->update($data);
            $this->dispatch('notify-success', $this->commonUpdateSuccess);
        } else {
            // Create new ticket
            Ticket::create($data);
            $this->dispatch('notify-success', $this->commonCreateSuccess);
        }

        $this->closeModal();
        $this->resetPage(); // Reset pagination to first page
    }

    public function deleteTicket($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->delete();

        session()->flash('message', 'Ticket deleted successfully!');
        $this->resetPage(); // Reset pagination to first page
    }

    public function toggleStatus($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->update([
            'status' => $ticket->status === '1' ? '0' : '1'
        ]);

        session()->flash('message', 'Ticket status updated successfully!');
    }

    public function refreshData()
    {
        $this->loadAllRecords();
        $this->calculateStatusCounts();
        $this->dispatch('data-refreshed');
    }

    public function getFilteredRecordsProperty()
    {
        if (empty($this->serverSearch)) {
            return collect($this->allRecords);
        }

        return collect($this->allRecords)->filter(function ($record) {
            $searchTerm = strtolower($this->serverSearch);
            return str_contains(strtolower($record['title']), $searchTerm) ||
                   (isset($record['created_user']['name']) && str_contains(strtolower($record['created_user']['name']), $searchTerm));
        });
    }
    public function openModal()
    {
        $this->resetForm();
        $this->ticket_unique_no="RAJ-".strtoupper(uniqid());
        $this->generated_by=$this->user_id;
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
        $this->ticketId = null;
        $this->ticket_unique_no = '';
        $this->branch_id = '';
        $this->establish_name = '';
        $this->generated_by = '';
    }

    public function getHasActiveFiltersProperty()
    {
        return $this->search || 
               $this->typeFilter || 
               $this->generatedByFilter || 
               $this->allocatedToFilter || 
               $this->natureOfWorkFilter || 
               $this->branchFilter || 
               $this->approvedByFilter || 
               $this->priorityFilter || 
               $this->fromDate || 
               $this->toDate ||
               $this->statusFilter !== 'all';
    }

    public function getActiveFiltersCountProperty()
    {
        $count = 0;
        if ($this->search) $count++;
        if ($this->typeFilter) $count++;
        if ($this->generatedByFilter) $count++;
        if ($this->allocatedToFilter) $count++;
        if ($this->natureOfWorkFilter) $count++;
        if ($this->branchFilter) $count++;
        if ($this->approvedByFilter) $count++;
        if ($this->priorityFilter) $count++;
        if ($this->fromDate) $count++;
        if ($this->toDate) $count++;
        if ($this->statusFilter !== 'all') $count++;
        
        return $count;
    }

    public function clearAllFilters()
    {
     
        // Reset all filter properties
        $this->reset([
            'search',
            'typeFilter', 
            'generatedByFilter',
            'allocatedToFilter',
            'natureOfWorkFilter',
            'branchFilter',
            'approvedByFilter',
            'priorityFilter',
            'fromDate',
            'toDate',
            'dateFilter',
            'serverSearch'  // Also reset server search
        ]);
        
        // Reset status filter to 'all'
        $this->statusFilter = 'all';
        
        // Close advanced filters panel
        $this->showAdvancedFilters = false;
        
        // Reset pagination
        $this->resetPage();
        
        // Recalculate counts
        $this->calculateStatusCounts();
        
        // Show success message
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

    public function updatedSearch()
    {
        $this->resetPage(); // Reset pagination when search changes
    }
    
    public function updatedStatusFilter()
    {
        $this->resetPage(); // Reset pagination when status filter changes
        $this->calculateStatusCounts(); // Recalculate counts when filter changes
    }

    // Update methods for all filters
    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedGeneratedByFilter()
    {
        $this->resetPage();
    }

    public function updatedAllocatedToFilter()
    {
        $this->resetPage();
    }

    public function updatedNatureOfWorkFilter()
    {
        $this->resetPage();
    }

    public function updatedBranchFilter()
    {
        $this->resetPage();
    }

    public function updatedApprovedByFilter()
    {
        $this->resetPage();
    }

    public function updatedPriorityFilter()
    {
        $this->resetPage();
    }

    public function updatedFromDate()
    {
        $this->resetPage();
        $this->dateFilter = ''; // Clear quick date filter when manual date is set
    }

    public function updatedToDate()
    {
        $this->resetPage();
        $this->dateFilter = ''; // Clear quick date filter when manual date is set
    }

    public function activeFiltersCount()
    {
        $count = 0;
        if (!empty($this->search)) $count++;
        if (!empty($this->typeFilter)) $count++;
        if (!empty($this->generatedByFilter)) $count++;
        if (!empty($this->allocatedToFilter)) $count++;
        if (!empty($this->natureOfWorkFilter)) $count++;
        if (!empty($this->branchFilter)) $count++;
        if (!empty($this->approvedByFilter)) $count++;
        if (!empty($this->priorityFilter)) $count++;
        if (!empty($this->fromDate)) $count++;
        if (!empty($this->toDate)) $count++;
        if ($this->statusFilter !== 'all') $count++;
        
        return $count;
    }


    public function changeApprove($id)
    {
        $ticketData= Ticket::find($id);
        $ticketData->ticket_approve_date=date('Y-m-d H:i:s');
        $ticketData->approve_by=Auth::User()->id;
        $ticketData->save();
    }
    public function closeTicket($id)
    {
        $ticketData= Ticket::find($id);
        $ticketData->ticket_close_by=Auth::User()->id;
        $ticketData->save();
    }
    public function approveTicketClose($id)
    {
        $ticketData= Ticket::find($id);
        $ticketData->tocket_close_approve_by=Auth::User()->id;
        $ticketData->ticket_close_approve_date=date('Y-m-d H:i:s');
        $ticketData->save();
    }
    public function showTransferOptions($ticketId)
    {
        $this->selectedTicketId = $ticketId;
        $this->showTransferDropdown = true;
        $this->selectedUserId = null;
    }

    public function cancelTransfer()
    {
        $this->showTransferDropdown = false;
        $this->selectedTicketId = null;
        $this->selectedUserId = null;
    }

    public function transferTicket($ticketId)
    {
        if (!$this->selectedUserId) {
            return;
        }

        $ticket = Ticket::findOrFail($ticketId);
        
        // Validate user can transfer this ticket
        if ($ticket->work_alloted_to == auth()->id()) {
            $ticket->update([
                'ticket_transfered_to' => $this->selectedUserId,
                'ticket_transfered_date' => now()
            ]);
            
            // Reset the dropdown
            $this->cancelTransfer();
            
            $this->dispatch('notify-success', 'Ticket transferred successfully');
            
        }
    }

    private function calculateStatusCounts()
    {
        $baseQuery = Ticket::query();
        
        if(!in_array($this->user_role,[1,2])){
            $baseQuery->where(function($q) {
                $q->where('work_alloted_to', $this->user_id)
                ->orWhere('ticket_transfered_to', $this->user_id);
            });
        }
        $this->allTicketsCount = $baseQuery->count();
        
        // Created Approval (waiting for approval)
        $this->createdApprovalCount = (clone $baseQuery)
            ->whereNull('approve_by')
            ->whereNull('ticket_approve_date')
            ->count();
       
            // Closed Approval (ticket closed but waiting for close approval)
        $this->closedApprovalCount = (clone $baseQuery)
            ->whereNotNull('ticket_close_by')
            ->whereNull('tocket_close_approve_by')
            ->count();
        
        // Running Tickets (approved and not closed)
        $this->runningTicketsCount = (clone $baseQuery)
            ->whereNotNull('approve_by')
            ->whereNotNull('ticket_approve_date')
            ->whereNull('ticket_close_by')
            ->where('status', '1')
            ->count();
        
        // Closed Tickets (closed and approved)
        $this->closedTicketsCount = (clone $baseQuery)
            ->whereNotNull('ticket_close_by')
            ->whereNotNull('tocket_close_approve_by')
            ->whereNotNull('ticket_close_approve_date')
            ->count();
        
        // Transferred Running (transferred but not closed)
        $this->transferredRunningCount = (clone $baseQuery)
            ->whereNotNull('ticket_transfered_to')
            ->whereNull('ticket_close_by')
            ->where('status', '1')
            ->count();
        
        // Transferred Closed (transferred and closed)
        $this->transferredClosedCount = (clone $baseQuery)
            ->whereNotNull('ticket_transfered_to')
            ->whereNotNull('ticket_close_by')
            ->count();
    }
    public function render()
    {
        $query = Ticket::with([
            'branch', 
            'generatedBy', 
            'createdBy', 
            'workAllottedTo', 
            'natureOfWork',
            'approvedBy',
            'ticketClosedBy',
            'ticketCloseApprovedBy',
            'ticketTransferredTo'
        ]);

        
        if(!in_array($this->user_role,[1,2])){
            $query->where(function($q) {
                $q->where('work_alloted_to', $this->user_id)
                ->orWhere('ticket_transfered_to', $this->user_id);
            });
        }
        // Apply status filter
        switch($this->statusFilter) {
            case 'created_approval':
                $query->whereNull('approve_by')
                ->whereNull('ticket_approve_date')
                ->where('status', '1');
                break;
                
            case 'closed_approval':
                $query->whereNotNull('ticket_close_by')
                      ->whereNull('tocket_close_approve_by');
                break;
                
            case 'running':
                $query->whereNotNull('approve_by')
                      ->whereNotNull('ticket_approve_date')
                      ->whereNull('ticket_close_by')
                      ->where('status', '1');
                break;
                
            case 'closed':
                $query->whereNotNull('ticket_close_by')
                      ->whereNotNull('tocket_close_approve_by')
                      ->whereNotNull('ticket_close_approve_date');
                break;
                
            case 'transferred_running':
                $query->whereNotNull('ticket_transfered_to')
                      ->whereNull('ticket_close_by')
                      ->where('status', '1');
                break;
                
            case 'transferred_closed':
                $query->whereNotNull('ticket_transfered_to')
                      ->whereNotNull('ticket_close_by');
                break;
                
            case 'all':
            default:
                // No additional filtering for 'all'
                break;
        }

         // Apply type filter
         if ($this->typeFilter) {
            
            switch($this->typeFilter) {
                case 'open':
                    $query->whereNull('ticket_close_by')->where('status', '1');
                    break;
                case 'closed':
                    $query->whereNotNull('ticket_close_by');
                    break;
                case 'pending':
                    $query->where(function($q) {
                        $q->whereNull('approve_by')
                          ->orWhereNull('ticket_approve_date')
                          ->orWhere(function($subQ) {
                              $subQ->whereNotNull('ticket_close_by')
                                   ->whereNull('tocket_close_approve_by');
                          });
                    });
                    break;
                case 'transferred':
                    $query->whereNotNull('ticket_transfered_to');
                    break;
            }
        }

        // Apply user filters
        if ($this->generatedByFilter) {
            
            $query->where('generated_by', $this->generatedByFilter);
        }

        if ($this->allocatedToFilter) {
            
            if ($this->allocatedToFilter === 'unassigned') {
                $query->whereNull('work_alloted_to');
            } else {
                $query->where('work_alloted_to', $this->allocatedToFilter);
            }
        }

        if ($this->approvedByFilter) {
            
            if ($this->approvedByFilter === 'pending') {
                $query->whereNull('approve_by');
            } else {
                $query->where('approve_by', $this->approvedByFilter);
            }
        }

        // Apply nature of work filter
        if ($this->natureOfWorkFilter) {
            
            $query->where('nature_of_work_id', $this->natureOfWorkFilter);
        }

        // Apply branch filter
        if ($this->branchFilter) {
            
            $query->where('branch_id', $this->branchFilter);
        }

        // Apply date filters
        if ($this->fromDate) {
            
            $query->whereDate('created_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        // Apply priority filter (if you have a priority field)
        if ($this->priorityFilter) {
            
            $query->whereJsonContains('json_data->priority', $this->priorityFilter);
        }

        // Apply search filter
        if ($this->search) {
            
            $query->where(function ($q) {
                $q->where('ticket_unique_no', 'like', '%' . $this->search . '%')
                  ->orWhere('establish_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('branch', function ($branchQuery) {
                      $branchQuery->where('title', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('natureOfWork', function ($natureQuery) {
                      $natureQuery->where('title', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('generatedBy', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('workAllottedTo', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('approvedBy', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);

        // Recalculate status counts for real-time updates
        $this->calculateStatusCounts();
        $this->hasActiveFilters();

        return view('livewire.ticket.data-collections', [
              'tickets' => $tickets,
            'branches' => $this->branches,
            'users' => $this->users,
            'natureofwork' => $this->natureofwork,
        ])->layout('layouts.app');
    }
}