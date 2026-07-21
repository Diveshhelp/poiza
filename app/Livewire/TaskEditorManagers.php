<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\SubDepartments;
use App\Models\Task;
use App\Models\TaskHistory;
use App\Models\TaskImage;
use App\Models\User;
use App\Traits\HasSubscriptionCheck;
use Auth;
use DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;
use Str;

class TaskEditorManagers extends Component
{
    use WithFileUploads;
    use HasSubscriptionCheck;
    // Module Configuration
    public $moduleTitle = TASK_MANAGER_TITLE;

    public $team_id = '';

    public $user_id;

    // Form Fields
    public $department_id;

    public $assigned_person;

    public $title;

    public $work_detail='';

    public $deadline;

    public $priority;

    public $work_type;

    public $status = 'not_started';

    public $repetition = 'no';

        
    public $weekDays = [];
    public $selectedMonth = 1;
    public $selectedQuarter = 1;
    public $selectedHalfYear = 1;
    
    // Repeat until
    public $repeatUntilType = 'never'; // never, date, occurrences
    public $repeatUntilDate;
    public $maxOccurrences;

    public $notes;

    public $work_image;

    public $security_code;

    // Component States
    public $tasks = [];

    public $isUpdate = false;

    public $update_task_id = '';

    public $showSuccessMessage = false;

    public $showWarningMessage = false;

    public $showErrorMessage = false;

    // Messages
    public $commonCreateSuccess;

    public $commonUpdateSuccess;

    public $commonDeleteSuccess;

    public $errorMessage = '';

    public $usersList;

    public $departmentList;

    public $mediaFiles = [];

    public $media_files;

    public $task_id;

    public $department;

    public $task_uuid;

    public $subDepartmentList = [];
    public $subDepartment=[];
    public $allSelectedSubDepartmentList=[];
    public $repeat_until='';

    protected $rules = [
        'department' => 'required|exists:departments,id',
        'assigned_person' => 'required|exists:users,id',
        'title' => 'required|min:3',
        'work_detail' => 'nullable',
        'deadline' => 'required|date',
        'priority' => 'required|in:highest,high,low,very_low',
        'work_type' => 'required|in:routine,easy,medium,hard',
        'status' => 'required|in:not_started,in_progress,done,delayed',
        'repetition' => 'required|in:no,daily,weekly,monthly,quarterly,half_yearly,yearly',
        'repeat_until' => 'nullable|date|after:deadline',
        'notes' => 'nullable|string',
        'mediaFiles.*' => 'file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:51200'
    ];

    protected $messages = [
        'mediaFiles.*.file' => 'The file must be a valid file upload.',
        'mediaFiles.*.mimes' => 'The file must be a type of: jpg, jpeg, png, gif, pdf, doc',
        'mediaFiles.*.max' => 'The file may not be greater than 50MB.',
    ];



    public function mount($id)
    {
        if (!$this->ensureSubscription()) {
            abort(402, 'Access denied. Subscription is over, Please renew the plan and get the full access!');
        }
        $this->task_uuid = $id;
        $this->user_id = Auth::User()->id;
        $this->team_id = Auth::user()->currentTeam->id;
        $this->team_name = Auth::user()->currentTeam->name;
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->usersList = User::where("current_team_id",$this->team_id)->get();
        $this->departmentList = Department::where("team_id",$this->team_id)->get();
        $this->task_id = $this->editTask();

        
    }

    private function getRepetitionValues()
    {
        switch($this->repetition) {
            case 'weekly':
                return $this->weekDays ?? [];
            case 'monthly':
                return $this->selectedMonth ?? null;
            case 'quarterly':
                return $this->selectedQuarter ?? null;
            case 'half_yearly':
                return $this->selectedHalfYear ?? null;
            default:
                return null;
        }
    }
    public function getWeekDays()
    {
        return [
            0 => 'Sun',
            1 => 'Mon',
            2 => 'Tues',
            3 => 'Wed',
            4 => 'Thur',
            5 => 'Fri',
            6 => 'Sat'
        ];
    }
    
    public function getMonths()
    {
        $months = [];
        for ($i = 1; $i <= 31; $i++) {
            $months[$i] = 'Day ' . $i;
        }
        return $months;
    }
    
    public function getQuarters()
    {
        return [
            1 => 'First Month (Jan/Apr/Jul/Oct)',
            2 => 'Second Month (Feb/May/Aug/Nov)',
            3 => 'Third Month (Mar/Jun/Sep/Dec)'
        ];
    }
    
    public function getHalfYears()
    {
        return [
            1 => 'January/July',
            2 => 'February/August',
            3 => 'March/September',
            4 => 'April/October',
            5 => 'May/November',
            6 => 'June/December'
        ];
    }

    public function uploadMedia()
    {

        foreach ($this->mediaFiles as $file) {
            $fileName = Str::uuid().'.'.$file->getClientOriginalExtension();
            $path = 'tasks/'.$this->task_id.'/media';

            // Store the file
            $filePath = $file->storeAs($path, $fileName, 'public');

            TaskImage::create([
                'task_id' => $this->task_id,
                'image_path' => $filePath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }
        $this->reset('mediaFiles');
    }

    public function removeFile($index)
    {
        array_splice($this->mediaFiles, $index, 1);
    }

    // Add this method to reset error messages
    public function resetErrors()
    {
        $this->errorMessage = '';
        $this->showErrorMessage = false;
        $this->resetErrorBag();
    }

    public function editTask()
    {
        try {
            $this->isUpdate = true;
            
            // Find task with related images
            $task = Task::with(['task_images'])
                        ->where('uuid', $this->task_uuid)
                        ->firstOrFail();  // Using firstOrFail instead of first()
    
            // Basic task data assignment
            $this->populateTaskData($task);


            
            // Handle repetition details
            $this->handleRepetitionDetails($task);
    
            // Return task ID for further processing if needed
            return $this->task_id;
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('error', 'Task not found.');
            return false;
        } catch (\Exception $e) {
            session()->flash('error', 'Error loading task: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Populate basic task data
     * 
     * @param Task $task
     * @return void
     */
    private function populateTaskData($task)
    {
        $this->task_id = $task->id;
        $this->update_task_id = $task->id;
        $this->department = $task->department_id;
        $this->assigned_person = $task->assigned_to;
        $this->title = $task->title;
        $this->work_detail = $task->work_detail;
        $this->deadline = $task->deadline;
        $this->priority = $task->priority;
        $this->work_type = $task->work_type;
        $this->status = $task->status;
        $this->repeat_until = $task->repeat_until;
        $this->notes = $task->notes;
        $this->media_files = $task->task_images;
        $this->repetition = $task->repetition;

        $this->subDepartmentList = SubDepartments::where('department_id', $task->department_id)->get();
        $this->allSelectedSubDepartmentList=SubDepartments::where("id", $task->sub_departments)->pluck('id')->toArray();
        $this->department = $task->department_id;
        
        // Load subdepartments for this department
        $this->loadSubDepartments($this->department);
        
        $this->subDepartment = $task->sub_departments;
        
    }
    
    /**
     * Handle repetition details assignment
     * 
     * @param Task $task
     * @return void
     */
    private function handleRepetitionDetails($task)
    {
        // Reset all repetition-related properties
        $this->resetRepetitionValues();
    
        // If no repetition details, return early
        if (!$task->repetition_details) {
            return;
        }
    
        // Decode JSON if it's a string
        $repetitionDetails = is_string($task->repetition_details) 
            ? json_decode($task->repetition_details, true) 
            : $task->repetition_details;
    
        // If no values in repetition details, return early
        if (!isset($repetitionDetails['values'])) {
            return;
        }
        // Assign repetition values based on type
        // Set type-specific values
        if ($this->repetition === 'weekly' && isset($repetitionDetails['values']['repeat_days'])) {
            $this->weekDays = $repetitionDetails['values']['repeat_days'];
        }
        
        if ($this->repetition === 'monthly' && isset($repetitionDetails['values']['day_of_month'])) {
            $this->selectedMonth = $repetitionDetails['values']['day_of_month'];
        }
        
        if ($this->repetition === 'quarterly' && isset($repetitionDetails['values']['quarter_month'])) {
            $this->selectedQuarter = $repetitionDetails['values']['quarter_month'];
        }
        
        if ($this->repetition === 'half_yearly' && isset($repetitionDetails['values']['half_year_month'])) {
            $this->selectedHalfYear = $repetitionDetails['values']['half_year_month'];
        }
        // Set repeat until properties
        if (isset($repetitionDetails['values']['repeat_until'])) {
            $this->repeatUntilType = 'date';
            $this->repeatUntilDate = $repetitionDetails['values']['repeat_until'];
        } elseif (isset($repetitionDetails['values']['max_occurrences'])) {
            $this->repeatUntilType = 'occurrences';
            $this->maxOccurrences = $repetitionDetails['values']['max_occurrences'];
        } else {
            $this->repeatUntilType = 'never';
        }
    }
    
    /**
     * Reset all repetition-related values
     * 
     * @return void
     */
    private function resetRepetitionValues()
    {
        $this->weekDays = [];
        $this->selectedMonth = null;
        $this->selectedQuarter = null;
        $this->selectedHalfYear = null;
    }

    public function updateDataObject()
    {
        $this->validate();
        
        try {

            DB::beginTransaction();
            $repetitionDetails = null;
            if ($this->repetition !== 'no') {
                $repetitionDetails = [
                    'frequency' => $this->repetition,
                    'current_occurrence' => 1,
                ];
                
                // Handle specific repetition types
                switch ($this->repetition) {
                    case 'weekly':
                        $repetitionDetails['repeat_days'] = $this->weekDays;
                        break;
                        
                    case 'monthly':
                        $repetitionDetails['day_of_month'] = $this->selectedMonth;
                        break;
                        
                    case 'quarterly':
                        $repetitionDetails['quarter_month'] = $this->selectedQuarter;
                        break;
                        
                    case 'half_yearly':
                        $repetitionDetails['half_year_month'] = $this->selectedHalfYear;
                        break;
                }
                
                // Handle repeat until settings
                if ($this->repeatUntilType === 'date') {
                    $repetitionDetails['repeat_until'] = $this->repeatUntilDate;
                } elseif ($this->repeatUntilType === 'occurrences') {
                    $repetitionDetails['max_occurrences'] = $this->maxOccurrences;
                }
            }

            $repetitionDetails = [
                'type' => $this->repetition,
                'values' => $repetitionDetails
            ];
            $task = Task::findOrFail($this->update_task_id);
            $task->update([
                'department_id' => $this->department,
                'assigned_to' => $this->assigned_person,
                'title' => $this->title,
                'work_detail' => $this->work_detail,
                'deadline' => $this->deadline,
                'priority' => $this->priority,
                'work_type' => $this->work_type,
                'status' => $this->status,
                'repetition' => $this->repetition,
                'repeat_until' => $this->repeat_until,
                'notes' => $this->notes,
                'repetition_details' => json_encode($repetitionDetails),
                'sub_departments'=>$this->subDepartment,

            ]);

            // Handle new image upload
            if ($this->mediaFiles) {
                try {
                    $this->task_id = $this->update_task_id;
                    $this->uploadMedia();
                } catch (\Exception $e) {
                    $this->dispatch('notify-error', 'Error updating task: ' . $e->getMessage());
                    throw new \Exception('Error uploading image: ' . $e->getMessage());
                }
            }

            DB::commit();
            $this->dispatch('notify-success', $this->commonUpdateSuccess);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify-error', 'Error updating task: ' . $e->getMessage());
            $this->showErrorMessage = true;
        }
    }

    public function removeUploadedFile($id)
    {
        try {

            $id = base64_decode($id);
            DB::beginTransaction();
            $taskImage = TaskImage::findOrFail($id);

            if (Storage::disk('public')->exists($taskImage->image_path)) {
                Storage::disk('public')->delete($taskImage->image_path);
            }
            $taskImage->delete();

            TaskHistory::create([
                'task_id' => $taskImage->task_id,
                'user_id' => $this->user_id,
                'field_name' => 'attachment removed',
                'old_value' => json_encode($taskImage),
                'new_value' => '',
            ]);

            DB::commit();
            $this->dispatch('notify-success', 'File deleted successfully');

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify-error', 'Error deleting image: '.$e->getMessage());

            return false;
        }
    }

    public function resetForm()
    {
        $this->reset([
            'department_id',
            'assigned_person',
            'title',
            'work_detail',
            'deadline',
            'priority',
            'work_type',
            'status',
            'repetition',
            'repeat_until',
            'notes',
            'work_image',
            'security_code',
        ]);
        $this->isUpdate = false;
    }

    public function resetAllMessages()
    {
        $this->showErrorMessage = false;
        $this->showWarningMessage = false;
        $this->showSuccessMessage = false;
    }

    public function updatedDepartment($value)
    {
        if (!empty($value)) {
            $this->subDepartmentList = SubDepartments::where('department_id', $value)->get();
        } else {
            $this->subDepartmentList = [];
        }
        $this->loadSubDepartments($value);
    }


  

    public function loadSubDepartments($departmentId = null)
    {
        $departmentId = $departmentId ?: $this->department;
        
        if (!empty($departmentId)) {
            $this->subDepartmentList = SubDepartments::where('department_id', $departmentId)
                ->select('id', 'name')
                ->get()
                ->toArray();
        } else {
            $this->subDepartmentList = [];
        }
    }
    public function render()
    {

        $departments = Department::where("team_id",$this->team_id)->get();
        $users = User::where("current_team_id",$this->team_id)->get();

        return view('livewire.task-managers.task-edit', [
            'departments' => $departments,
            'users' => $users,
        ])->layout('layouts.app');
    }
}
