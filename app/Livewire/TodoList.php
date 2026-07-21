<?php
namespace App\Livewire;

use App\Models\Todo;
use App\Models\TodoAttachment;
use App\Models\TodoNote;
use Auth;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;

    public $moduleTitle = TODO_TITLE;

    public $searchTitle = '';
    public $filterPriority = '';
    public $filterStatus = '';
    public $filterDueDate = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = PER_PAGE;

    public $commonCreateSuccess;

    public $commonUpdateSuccess;

    public $commonDeleteSuccess;

    public $commonNotDeleteSuccess;
    public $commonStatusUpdateSuccess;
    public $todo;

    public $selectedTodo;
    public $newNote = '';
    public $showNoteModal = false;
    public $team_id;
    protected $queryString = [
        'filters' => ['except' => ['searchTitle' => '', 'filterPriority' => '', 'filterStatus' => '', 'filterDueDate' => '', 'sortField' => '', 'sortDirection' => '']],
        'currentTab' => ['except' => 'not_started']
    ];

    public $currentTab = 'not_started';
    public $tabs = [
        'not_started' => ['name' => 'Not Started', 'status' => 'pending'],
        'in_progress' => ['name' => 'In Progress', 'status' => 'in_progress'],
        'completed' => ['name' => 'Completed', 'status' => 'completed'],
        'cancelled' => ['name' => 'Cancelled', 'status' => 'cancelled']
    ];
    public $filters = [
        'search' => '',
        'date' => '',
        'priority' => ''
    ];
    public function mount()
    {

        $this->user_id = Auth::User()->id;
        $this->team_id = Auth::user()->currentTeam->id;
        $this->team_name = Auth::user()->currentTeam->name;

        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonStatusUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_STATUS_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
        
    }

    

    public function updatingSearchTitle()
    {
        $this->resetPage();
    }

    public function updateStatus($uuid, $status)
    {
        $this->loading = true;
        try {
            $todo = Todo::where('uuid', $uuid)->firstOrFail();
            $todo->update(['status' => $status]);
            $this->status = $status;
            $this->dispatch('notify-success', $this->commonStatusUpdateSuccess);
            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to update status');
        }
        $this->render();  // This will refresh the component
        $this->loading = false;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteTodo($uuid)
    {
        try {
            $todo = Todo::where('uuid', $uuid)->firstOrFail();
            $todo->delete();
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        } catch (\Exception $e) {
            $this->dispatch('notify-error', $this->commonNotDeleteSuccess);
        }
    }

    public function showTaskDetails($id)
    {
        $todo = Todo::with(['created_user', 'notes'])->where('uuid', $id)->firstOrFail();
        // Add time remaining calculation
        $dueDate = Carbon::parse($todo->due_date);
        $todo->is_overdue = $dueDate->isPast();
        $todo->time_remaining = $dueDate->diffForHumans([
            'parts' => 1,
            'short' => true,
        ]);

        // Format attachments if any
        if ($todo->attachments) {
            $todo->attachments = $todo->attachments->map(function ($attachment) {
                $filePath = storage_path('app/public/'.$attachment->file_path);

                return [
                    'id' => $attachment->id,
                    'file_name' => $attachment->name,
                    'size' => $this->formatFileSize($attachment->file_size),
                    'file_path' => asset('storage/'.$attachment->file_path), // For public URL
                    'mime_type' => mime_content_type($filePath),
                ];
            });
        }
        $this->dispatch('show-todo-details', ['todoData' => $todo]);
    }


    private function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;

        return number_format($size / pow(1024, $power), 2, '.', ',').' '.$units[$power];
    }

    public function editTodo($uuid)
    {
        // Redirect to the edit route with the todo UUID
        return $this->redirect(route('todos.edit', ['uuid' => $uuid]));
    }
    public function download($attachmentId)
    {
        $attachment = TodoAttachment::findOrFail($attachmentId);
        
        if (!$attachment) {
            session()->flash('error', 'Attachment not found.');
            return;
        }
        try {
            return response()->download(storage_path('app/public/' . $attachment->file_path));
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to download file.');
        }
    }
    public function openNoteModal($todoId)
    {
        $this->selectedTodo = Todo::find($todoId);
        $this->showNoteModal = true;
    }

    public function addNote()
    {
        $this->validate([
            'newNote' => 'required|string|max:1000',
        ]);

        TodoNote::create([
            'todo_id' => $this->selectedTodo->id,
            'user_id' => auth()->id(),
            'content' => $this->newNote
        ]);

        $this->newNote = '';
        $this->dispatch('note-added');    }

    public function deleteNote($noteId)
    {
        $note = TodoNote::find($noteId);
        
        if ($note && ($note->user_id === auth()->id() || auth()->user()->isAdmin())) {
            $note->delete();
        }
    }
    #[On('note-added')] 
    public function refreshNotes()
    {
        // The list will be refreshed automatically
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchTitle', 'filterPriority', 'filterStatus', 'filterDueDate']);
        $this->resetPage();
    }
    
    public function setTab($tab)
    {
        $this->resetPage(); // Reset pagination when tab is set
        $this->currentTab = $tab;
        
        if ($tab === 'overdue') {
            $this->filters['dateRange'] = 'overdue';
            $this->filters['status'] = '';
        } else {
            $this->filters['dateRange'] = '';
            $this->filters['status'] = $this->tabs[$tab]['status'];
        }
        
        $this->dispatch('tabChanged', $tab);
    }

   
    public function getTabCount($status)
    {
        $query = $this->getFilteredQuery();

        // Apply tab-based filtering
        if ($status === 'overdue') {
            $query->where('due_date', '<', Carbon::today())
                ->where('status', '!=', 'completed');
        } else {
            $query->where('status', $status);
        }

        // Apply other filters
        $query->when($this->searchTitle, function ($query) {
            $query->where('title', 'like', '%'.$this->searchTitle.'%');
        })
        ->when($this->filterPriority, function ($query) {
            $query->where('priority', $this->filterPriority);
        })
        ->when($this->filterDueDate, function ($query) {
            switch ($this->filterDueDate) {
                case 'today':
                    $query->whereDate('due_date', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('due_date', [
                        Carbon::now()->startOfWeek(), 
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('due_date', Carbon::now()->month);
                    break;
                case 'overdue':
                    $query->where('due_date', '<', Carbon::today())
                        ->where('status', '!=', 'completed');
                    break;
            }
        });

        return $query->count();
    }
    
    public function updatedCurrentTab($value)
    {
        $this->resetPage(); // Reset pagination when tab changes
        
        if ($value === 'overdue') {
            $this->filters['dateRange'] = 'overdue';
            $this->filters['status'] = '';
        } else {
            $this->filters['dateRange'] = '';
            $this->filters['status'] = $this->tabs[$value]['status'];
        }
    }
    public function getTabCounts()
    {
        return collect($this->tabs)->mapWithKeys(function ($tab, $key) {
            return [$key => $this->getTabCount($tab['status'])];
        });
    }

    public function getFilteredQuery()
    {
        $query = Todo::query()->with(['created_user', 'notes']);
        
        // Get current user and their roles
        $user = Auth::user();
        $userRoles = explode(',', $user->user_role);
        $query->where("team_id",$this->team_id);
        // If not Super Admin (1) or Admin (2), only show user's todos
        if (!in_array('1', $userRoles) && !in_array('2', $userRoles)) {
            $query->where('user_id', $user->id);
        }
        
        return $query;
    }
    
    public function render()
    {
        $tabCounts = $this->getTabCounts();
        $query = $this->getFilteredQuery();

        // Apply tab-based filtering
        if ($this->currentTab === 'overdue') {
            $query->where('due_date', '<', Carbon::today())
                ->where('status', '!=', 'completed');
        } else {
            $query->where('status', $this->tabs[$this->currentTab]['status']);
        }

        // Apply other filters
        $query->when($this->searchTitle, function ($query) {
            $query->where('title', 'like', '%'.$this->searchTitle.'%');
        })
        ->when($this->filterPriority, function ($query) {
            $query->where('priority', $this->filterPriority);
        })
        ->when($this->filterDueDate, function ($query) {
            switch ($this->filterDueDate) {
                case 'today':
                    $query->whereDate('due_date', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('due_date', [
                        Carbon::now()->startOfWeek(), 
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('due_date', Carbon::now()->month);
                    break;
                case 'overdue':
                    $query->where('due_date', '<', Carbon::today())
                        ->where('status', '!=', 'completed');
                    break;
            }
        });

        $todos = $query->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage);

        return view('livewire.todos.todos-list', [
            'todos' => $todos,
            'tabCounts' => $tabCounts
        ])->layout('layouts.app');
    }

}