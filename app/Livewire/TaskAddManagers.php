<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\SubDepartments;
use App\Models\Task;
use App\Models\TaskImage;
use App\Models\User;
use App\Traits\HasSubscriptionCheck;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Str;

class TaskAddManagers extends Component
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

    public $priority='highest';

    public $work_type='routine';

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

    public $repeat_until;

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
    public $subDepartmentList = [];
    public $subDepartment=[];
    public $is_master_task=1;
    public $create_before_days=10;
    protected $rules = [
        'department' => 'required|exists:departments,id',
        'assigned_person' => 'required|exists:users,id',
        'title' => 'required|min:3',
        'work_detail' => 'nullable',
        'deadline' => 'required|date|after:yesterday',
        'priority' => 'required|in:highest,high,low,very_low',
        'work_type' => 'required|in:routine,easy,medium,hard',
        'status' => 'required|in:not_started,in_progress,done,delayed',
        'repetition' => 'required|in:no,daily,weekly,monthly,quarterly,half_yearly,yearly',
        'repeat_until' => 'nullable|date',
        'notes' => 'nullable|string',
        // 'mediaFiles.*' => 'file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:51200',
    ];

    protected $messages = [
        'mediaFiles.*.file' => 'The file must be a valid file upload.',
        'mediaFiles.*.mimes' => 'The file must be a type of: jpg, jpeg, png, gif, pdf, doc',
        'mediaFiles.*.max' => 'The file may not be greater than 50MB.',
    ];

    public $department;
    public function mount()
    {
        if (!$this->ensureSubscription()) {
            abort(402, 'Access denied. Subscription is over, Please renew the plan and get the full access!');
        }

        $this->user_id = session('session_user_id')??Auth::User()->id;
        $this->team_id = Auth::user()->currentTeam->id;
        $this->team_name = Auth::user()->currentTeam->name;
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->usersList = User::where("current_team_id",$this->team_id)->get();
        $this->departmentList = Department::where("team_id",$this->team_id)->get();
        $this->deadline=date('Y-m-d');

        $this->repeatUntilDate = Carbon::now()->addMonth()->format('Y-m-d');
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
    public function saveDataObject()
    {
        $this->validate();
        
        try {

            DB::beginTransaction();
            
           
            $repetitionDetails = null;
            $isMaster=0;
            $createBeforeDays=0;
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
                $isMaster=1;
                $createBeforeDays=7;
            }

             // Prepare repetition details
             $repetitionDetails = [
                'type' => $this->repetition,
                'values' => $repetitionDetails
            ];
            
            $task = Task::create([
                'id'=>rand('100', '999') . time(),
                'uuid' => Str::uuid()->toString(),
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
                'created_by' => $this->user_id,
                'repetition_details' => json_encode($repetitionDetails),
                'sub_departments'=>$this->subDepartment,
                'is_master_task'=>$isMaster,
                'create_before_days'=>$createBeforeDays,
                'team_id'=>$this->team_id

            ]);

            // Handle new image upload
            if ($this->mediaFiles) {
                try {
                    $this->task_id = $task->id;
                    $this->uploadMedia();
                } catch (\Exception $e) {
                    $this->dispatch('notify-error', 'Error updating task: ' . $e->getMessage());
                    throw new \Exception('Error uploading image: ' . $e->getMessage());
                }
            }

            DB::commit();
            $this->resetForm();
            $this->dispatch('notify-success', $this->commonUpdateSuccess);
            redirect(route('task-collections'));

        } catch (\Exception $e) {
            Log::info('Call');
            DB::rollBack();
            $this->dispatch('notify-error', 'Error updating task: ' . $e->getMessage());
            session()->flash('message', 'Error updating task: ' . $e->getMessage());
            $this->showErrorMessage = true;
        }
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

    public function resetErrors()
    {
        $this->errorMessage = '';
        $this->showErrorMessage = false;
        $this->resetErrorBag();
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

    public function getTasks()
    {
        return Task::with(['department', 'assignedUser', 'images'])
            ->where('created_by', $this->user_id)
            ->orderBy('created_at', 'DESC')
            ->get();
    }


    public function updatedDepartment($value)
    {
        if (!empty($value)) {
            $this->subDepartmentList = SubDepartments::where('department_id', $value)->get();
        } else {
            $this->subDepartmentList = [];
        }
        $this->subDepartment = ''; // Reset sub-department when department changes
    }

    public function render()
    {
        $departments = Department::where("team_id",$this->team_id)->get();
        $users = User::where("current_team_id",$this->team_id)->get();

        return view('livewire.task-managers.task-add', [
            'departments' => $departments,
            'users' => $users,
        ])->layout('layouts.app');
    }
}