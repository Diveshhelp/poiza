<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\SubDepartments;
use App\Models\Task;
use App\Models\TaskHistory;
use App\Models\TaskImage;
use App\Models\TaskNote;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\ProductTypeManagers as ProductTypeManagersModel;
use App\Models\TitleCollections as TitleCollectionsModels;
use Auth,Str,DB;
use Storage;
use Livewire\WithPagination;
class TaskCollections extends Component
{
    use WithPagination {
        resetPage as protected originalResetPage;
    }

    public $moduleTitle=TASK_MANAGER_TITLE;
    public $moduleInnerTitle=NOTE_TITLE;
    public $title_set_name = '';
    public $team_id='';
    public $data_set_list=[];
    public $showSuccessMessage = false;
    public $showWarningMessage = false;
    public $showErrorMessage=false;

    public $inputs = [];
    public $inputsValues = [];
    public $user_id;
    public $isUpdate = false;
    public $update_attribute_set_id='';
    public $showImportModal = false;

    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;


    public $commonNoteCreateSuccess;
    public $commonNoteUpdateSuccess;
    public $commonNoteDeleteSuccess;
    public $addingNote;

    public $selectedTask = null;
    public $showModal = false;
    public $filters = [
        'search' => '',
        'status' => '',
        'priority' => '',
        'department' => '',
        'dateRange' => '',
        'assignee' => '',
    ];
    public $showFilters = false;

    public $currentTab = 'not_started';

    public $tabs = [
        
        'today' => ['name' => 'Todays Task', 'status' => 'today'],
        'not_started' => ['name' => 'New', 'status' => 'not_started'],
        'in_progress' => ['name' => 'In Progress', 'status' => 'in_progress'],
        'review' => ['name' => 'Under Review', 'status' => 'review'],
        'done' => ['name' => 'Completed', 'status' => 'done'],
        'overdue' => ['name' => 'Overdue', 'status' => 'overdue'],
    ];

    protected $queryString = [
        'filters' => ['except' => ['search' => '', 'status' => '', 'priority' => '', 'department' => '', 'assignee' => '', 'dateRange' => '']],
        'currentTab' => ['except' => 'in_progress']
    ];
    public $perPage=PER_PAGE;

    public function mount()
    {
        $this->user_id = Auth::User()->id;
        $this->team_id=Auth::user()->currentTeam->id;
        $this->team_name=Auth::user()->currentTeam->name;
        $this->commonCreateSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_DELETE_SUCCESS);
        $this->commonNoteCreateSuccess=str_replace("{module-name}",$this->moduleInnerTitle,COMMON_NOTE_CREATE_SUCCESS);
        $this->commonNoteUpdateSuccess=str_replace("{module-name}",$this->moduleInnerTitle,COMMON_NOTE_UPDATE_SUCCESS);
        $this->commonNoteDeleteSuccess=str_replace("{module-name}",$this->moduleInnerTitle,COMMON_NOTE_DELETE_SUCCESS);
        // Set up filters based on user role
        $userRoles = explode(',', Auth::user()->user_role);
        if (!in_array('1', $userRoles) && !in_array('2', $userRoles)) {
            // For non-admin users, remove the assignee filter
            unset($this->filters['assignee']);
        }
    }

    public function validateBeforeSave()
    {

        $validationRule = [
            'title_set_name' => 'required|min:3',
        ];
        try {
            $this->validate($validationRule);
        } catch (ValidationException $validationException) {
            throw $validationException;
        }
    }


    public function saveDataObject(){

        $this->validateBeforeSave();

        $this->saveCollectionSetData();

        $this->resetForm();
        session()->flash('message', $this->commonCreateSuccess);
        $this->showSuccessMessage = true;
    }
    public function resetForm(){
        $this->reset(['title_set_name']);
        $this->isUpdate=false;
    }


    public function saveCollectionSetData(){
        TitleCollectionsModels::create([
            'title_set_name' => $this->title_set_name,
            'team_id' => $this->team_id,
            'title_set_status'=>STATUS_ACTIVE,
            'created_by'=>$this->user_id,
            'uuid'=>Str::uuid()->toString()
        ]);
        return true;
    }

    public function getTaskObject(){
        $this->data_set_list= Task::where("assigned_to",$this->user_id)
        ->orderBy("id","DESC")->get();
    }

    public function addNote($taskUuid, $content)
    {
        $task = Task::where('uuid', $taskUuid)->firstOrFail();
        TaskNote::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'content' => $content
        ]);

        
        $this->dispatch('notify-success', $this->commonNoteCreateSuccess);
    }

    public function deleteNote($noteId)
    {
        $note = TaskNote::findOrFail($noteId);
        
        if ($note->user_id === auth()->id() || auth()->user()->can('delete-any-note')) {
            $note->delete();
            $this->dispatch('notify-success',  $this->commonNoteDeleteSuccess);
        } else {
            $this->dispatch('notify-error', 'You cannot delete this note');
        }
    }

    public function updateStatus($uuid, $status)
    {
        try {
            $task = Task::where('uuid', $uuid)->firstOrFail();
            $oldStatus = $task->status;
            $task->status = $status;
            $task->save();


            // Create history entry
            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => auth()->id(),
                'field_name' => 'status',
                'old_value' => $task->getOriginal('status'),
                'new_value' => $status
            ]);

            

            $task->statusHistories()->create([
                'old_status' => $oldStatus,
                'new_status' => $status,
                'changed_by' => auth()->id(),
                'remarks' => ''
            ]);
            

            $this->dispatch('notify-success', 'Status updated successfully');
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error updating status');
        }
    }
    public function showImportModalLink()
    {
        $this->reset(['csvFile']);
        $this->csvData=[];
        $this->showImportModal = true;
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function resetAllMessages()
    {
        $this->showErrorMessage = false;
        $this->showWarningMessage = false;
        $this->showSuccessMessage = false;
    }

    public function editTask($uuid){
        try {
            $this->resetAllMessages();
            $this->resetForm();
            $this->isUpdate=true;
            $attributeSetModelData = Task::where("uuid", $uuid)->firstOrFail();
            $this->title_set_name= $attributeSetModelData->title_set_name;

            $this->update_attribute_set_id=$uuid;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateDataObject(){
       
        $uuid=$this->update_attribute_set_id;
        $attributeSetModelData = TitleCollectionsModels::where("uuid", $uuid)->firstOrFail();
        $attributeSetModelData->title_set_name = $this->title_set_name;
        $attributeSetModelData->save();
        session()->flash('message', $this->commonUpdateSuccess);
        $this->showSuccessMessage = true;
        $this->resetForm();

    }
    public function showTaskDetails($id)
    {
        $task = Task::with([
            'department_object',
            'assign_to',
            'created_user',
            'notes.user',
            'task_images',
            'notes',
            'statusHistories'
        ])->find($id);

        if ($task) {
            // Convert dates to string format to avoid JSON serialization issues
            $taskData = array_merge($task->toArray(), [
                'created_at' => $task->created_at->format('Y-m-d h:i A'),
            ]);

            if ($task->sub_departments) {
                $subDeptIds = explode(",",$task->sub_departments);
                if($subDeptIds!=[]){
                    $subDepartments = SubDepartments::whereIn('id', $subDeptIds)
                    ->get()
                    ->map(function($subDept) {
                        return [
                            'id' => $subDept->id,
                            'name' => $subDept->name
                        ];
                    });
                }
            } else {
                $subDepartments = collect([]);
            }
    
             // Process repetition details for display
             $repetitionData = [];
             if ($task->repetition_details) {
                 $repetitionData = $this->formatRepetitionForDisplay(json_decode($task->repetition_details,true));
             } else {
                 $repetitionData = [
                     'frequency_label' => 'One Time',
                     'frequency' => 'no'
                 ];
             }
            // Convert dates to string format to avoid JSON serialization issues
            $taskData = array_merge($task->toArray(), [
                'created_at' => $task->created_at->format('Y-m-d h:i A'),
                'sub_departments_list' => $subDepartments,
                'repetition_data'=>$repetitionData
            ]);

            $this->dispatch('show-task-details', ['taskData' => $taskData]);
        }
    }
    protected function formatRepetitionForDisplay($repetitionDetails)
    {
        $data = [];
        
        // Set frequency labels
        $frequencyLabels = [
            'no' => 'One Time',
            'daily' => 'Every Day',
            'weekly' => 'Every Week',
            'monthly' => 'Every Month',
            'quarterly' => 'Every Quarter',
            'half_yearly' => 'Every Half Year',
            'yearly' => 'Every Year'
        ];
        $frequency = $repetitionDetails['values']['frequency'] ?? 'no';
        $data['frequency'] = $frequency;
        $data['frequency_label'] = $frequencyLabels[$frequency] ?? $frequency;
        
        // Process specific frequency details
        switch ($frequency) {
            case 'weekly':
                if (isset($repetitionDetails['values']['repeat_days']) && is_array($repetitionDetails['values']['repeat_days'])) {
                    $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    $days = [];
                    foreach ($repetitionDetails['values']['repeat_days'] as $dayNum) {
                        $days[] = $dayNames[$dayNum] ?? "Day $dayNum";
                    }
                    $data['days'] = $days;
                }
                break;
                
            case 'monthly':
                if (isset($repetitionDetails['values']['day_of_month'])) {
                    $data['day'] = $repetitionDetails['values']['day_of_month'];
                }
                break;
                
            case 'quarterly':
                if (isset($repetitionDetails['values']['quarter_month'])) {
                    $quarterLabels = [
                        1 => 'First Month (Jan/Apr/Jul/Oct)',
                        2 => 'Second Month (Feb/May/Aug/Nov)',
                        3 => 'Third Month (Mar/Jun/Sep/Dec)'
                    ];
                    $data['quarter'] = $quarterLabels[$repetitionDetails['values']['quarter_month']] ?? "Month {$repetitionDetails['values']['quarter_month']}";
                    $data['quarter_value'] = $repetitionDetails['values']['quarter_month'];
                }
                break;
                
            case 'half_yearly':
                if (isset($repetitionDetails['values']['half_year_month'])) {
                    $halfYearLabels = [
                        1 => 'January/July',
                        2 => 'February/August',
                        3 => 'March/September',
                        4 => 'April/October',
                        5 => 'May/November',
                        6 => 'June/December'
                    ];
                    $data['half'] = $halfYearLabels[$repetitionDetails['values']['half_year_month']] ?? "Month {$repetitionDetails['values']['half_year_month']}";
                    $data['half_value'] = $repetitionDetails['values']['half_year_month'];
                }
                break;
        }
        
        // Handle repeat until settings
        if (isset($repetitionDetails['values']['repeat_until'])) {
            $data['until_type'] = 'date';
            $data['until_date'] = $repetitionDetails['values']['repeat_until'];
            $data['until_text'] = 'Until ' . $repetitionDetails['values']['repeat_until'];
        } elseif (isset($repetitionDetails['values']['max_occurrences'])) {
            $data['until_type'] = 'occurrences';
            $data['max_occurrences'] = $repetitionDetails['values']['max_occurrences'];
            $data['until_text'] = "For {$repetitionDetails['values']['max_occurrences']} occurrences";
            
            if (isset($repetitionDetails['values']['current_occurrence'])) {
                $data['current_occurrence'] = $repetitionDetails['values']['current_occurrence'];
                $data['progress'] = "{$repetitionDetails['values']['current_occurrence']}/{$repetitionDetails['values']['max_occurrences']}";
            }
        } else {
            $data['until_type'] = 'never';
            $data['until_text'] = 'Repeats forever';
        }
        
        return $data;
    }
    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function applyFilters()
    {
        $this->showFilters = false;
    }
    public function download($attachmentId)
    {
        $attachment = TaskImage::findOrFail($attachmentId);
        
        if (!$attachment) {
            session()->flash('error', 'Attachment not found.');
            return;
        }

        try {
            return response()->download(storage_path('app/public/'.$attachment->image_path));

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to download file.');
        }
    }
    public function deleteTask($uuid)
    {
        try {
            $this->resetAllMessages();
            
            DB::beginTransaction();
            
            // Find the task with its relations
            $task = Task::with(['task_images', 'notes'])->where('uuid', $uuid)->firstOrFail();
            
            if($task->images!=[]){
            // Delete physical image files first
                foreach ($task->images as $image) {
                    if ($image->image_path && Storage::exists($image->image_path)) {
                        Storage::delete($image->image_path);
                    }
                }
            }
            // Delete related notes
            $task->notes()->delete();
            // Delete related images (database records)
            $task->task_images()->delete();
            // Finally delete the task
            $task->delete();
            DB::commit();
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting task: ' . $e->getMessage());
            $this->dispatch('notify-error', 'Error deleting task: ' . $e->getMessage());
            return false;
        }
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


    public function getFilteredTasksProperty()
    {
        try {
            $query = Task::query()
                ->with(['created_user', 'department_object', 'assign_to', 'notes', 'statusHistories']);

            // Get current user and their roles
            $user = Auth::user();
            $userRoles = explode(',', $user->user_role);
            $query->where("team_id",$this->team_id);
            // If not Super Admin (1) or Admin (2), only show assigned tasks
            if (!in_array('1', $userRoles) && !in_array('2', $userRoles)) {
                // Show only tasks assigned to the logged-in user
                $query->where('assigned_to', $user->id);
            }

            // Handle tab-based filtering
            if ($this->currentTab === 'overdue') {
                $query->whereDate('deadline', '<', Carbon::today())
                    ->where('status', '!=', 'done');
            }else if ($this->currentTab === 'today') {
                $query->whereDate('deadline', '=', Carbon::today())
                    ->where('status', '!=', 'done');
            } else {
                // Filter by status based on current tab
                $query->where('status', $this->tabs[$this->currentTab]['status']);
            }

            // Apply other filters
            if (!empty($this->filters['search'])) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhere('work_detail', 'like', '%' . $this->filters['search'] . '%');
                });
            }

            if (!empty($this->filters['priority'])) {
                $query->where('priority', $this->filters['priority']);
            }

            if (!empty($this->filters['department'])) {
                $query->where('department_id', $this->filters['department']);
            }

            // Only allow assignee filter for admin users
            if (!empty($this->filters['assignee']) && (in_array('1', $userRoles) || in_array('2', $userRoles))) {
                $query->where('assigned_to', $this->filters['assignee']);
            }

            if (!empty($this->filters['dateRange']) && $this->currentTab !== 'overdue') {
                switch ($this->filters['dateRange']) {
                    case 'today':
                        $query->whereDate('deadline', Carbon::today());
                        break;
                    case 'this_week':
                        $query->whereBetween('deadline', [
                            Carbon::now()->startOfWeek(),
                            Carbon::now()->endOfWeek()
                        ]);
                        break;
                    case 'next_week':
                        $query->whereBetween('deadline', [
                            Carbon::now()->addWeek()->startOfWeek(),
                            Carbon::now()->addWeek()->endOfWeek()
                        ]);
                        break;
                }
            }

            // Order by priority and creation date
            $query->orderBy('created_at', 'desc');

            return $query->paginate($this->perPage);
            
        } catch (\Exception $e) {
            \Log::error('Error in getFilteredTasksProperty: ' . $e->getMessage());
            return collect([])->paginate($this->perPage);
        }
    }

    public function getTabCount($status)
    {
        $query = Task::query();
        
        // Get current user and their roles
        $user = Auth::user();
        $userRoles = explode(',', $user->user_role);
        $query->where("team_id",$this->team_id);
        // If not Super Admin or Admin, only show assigned tasks
        if (!in_array('1', $userRoles) && !in_array('2', $userRoles)) {
            $query->where('assigned_to', $user->id);
        }
        
        // Apply status/tab filtering
        if ($status === 'overdue') {
            $query->whereDate('deadline', '<', Carbon::today())
                ->where('status', '!=', 'done');
        }else if ($this->currentTab === 'today') {
            $query->whereDate('deadline', '=', Carbon::today())
                ->where('status', '!=', 'done');
        } else {
            $query->where('status', $status);
        }

        // Apply filters
        if (!empty($this->filters['search'])) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->filters['search'] . '%')
                ->orWhere('work_detail', 'like', '%' . $this->filters['search'] . '%');
            });
        }

        if (!empty($this->filters['priority'])) {
            $query->where('priority', $this->filters['priority']);
        }

        if (!empty($this->filters['department'])) {
            $query->where('department_id', $this->filters['department']);
        }

        return $query->count();
    }

    public function getTabCounts()
    {
        return collect($this->tabs)->mapWithKeys(function ($tab, $key) {
            return [$key => $this->getTabCount($tab['status'])];
        });
    }

    public function render()
    {
        return view('livewire.task-managers.task-list', [
            'departments' => Department::all(),
            'users' => User::all(),
            'tasks' => $this->filteredTasks,
            'tabCounts' => $this->getTabCounts()
        ])->layout('layouts.app');
    }
}