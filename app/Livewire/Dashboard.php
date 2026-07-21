<?php

namespace App\Livewire;

use App\Mail\ContactFormMail;
use App\Models\Leave;
use App\Models\Task;
use App\Models\Todo;
use Auth;
use Carbon\Carbon;
use Livewire\Component;
use Mail;
use Session;

class Dashboard extends Component
{
    public $stats = [];
    public $team_id;
    public $contact_name;
    public $email;
    public $phone;
    public $company;
    public $requirements;
    
    public $captchaAnswer;
    
    // Math CAPTCHA properties
    public $captchaQuestion;
    public $firstNumber;
    public $secondNumber;
    public $operation;
    
    // Success message flag
    public $showSuccessMessage = false;
    
    // Validation rules
    public $allData;
    protected function rules()
    {
        return [
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'requirements' => 'required|string',
            'captchaAnswer' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $correctAnswer = Session::get('captcha_answer');
                    if ((int) $value !== (int) $correctAnswer) {
                        $fail('The math problem answer is incorrect.');
                    }
                },
            ]
        ];
    }
        
        // Custom error messages
        protected $messages = [
            'contact_name.required' => 'Please provide your full name.',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'phone.required' => 'Please provide your phone number.',
            'requirements.required' => 'Please tell us about your business requirements.',
            'captchaAnswer.required' => 'Please solve the math problem.',
            'captchaAnswer.numeric' => 'The answer should be a number.',
        ];
    public function mount()
    {

        $this->contact_name=Auth::user()->name;
        $this->email=Auth::user()->email;
        $this->phone=Auth::user()->mobile??'';
        $this->company=Auth::user()->currentTeam->name??'';

        $this->generateMathCaptcha();
        
        $this->team_id = Auth::user()->currentTeam->id;
        $this->loadStats();

        
        $team = Auth::user()->currentTeam;
        if($team['trial_start_date']==NULL && $team['trial_end_date']==NULL){
            $trialStartDate = '';
            $trialEndDate = '';
            $currentDate = Carbon::now();
            $daysLeft =0;
            $progressPercentage=0;
            $team['is_trial_mode']="yes";
            
        }else{
            // Parse dates
            $trialStartDate = Carbon::parse($team['trial_start_date']);
            $trialEndDate = Carbon::parse($team['trial_end_date']);
            $currentDate = Carbon::now();
                   
            // Calculate total trial duration in days
            $totalTrialDays = $trialStartDate->diffInDays($trialEndDate);
            
            // Calculate days left in trial
            $daysLeft = $currentDate->diffInDays($trialEndDate, false);
            // If trial has expired, return 0 days
            if ($daysLeft < 0) {
                $daysLeft = 0;
            }
            
            // Calculate percentage completed
            $daysCompleted = $totalTrialDays - $daysLeft;
            $progressPercentage = ($daysCompleted / $totalTrialDays) * 100;
            
            // Round to whole number
            $progressPercentage = round($progressPercentage);
        }

        
        $this->allData= [
            'is_trial' => $team['is_trial_mode'],
            'daysLeft' => $daysLeft,
            'progressPercentage' => $progressPercentage,
            'trialStartDate'=>$trialStartDate,
            'trialEndDate'=>$trialEndDate
        ];
    }
    
    // Generate a new math CAPTCHA
    public function generateMathCaptcha()
    {
        $this->firstNumber = rand(1, 20);
        $this->secondNumber = rand(1, 10);
        
        // Choose a random operation: 1 = addition, 2 = subtraction, 3 = multiplication
        $operations = [1, 2, 3];
        $this->operation = $operations[array_rand($operations)];
        
        // Ensure subtraction doesn't result in negative numbers
        if ($this->operation == 2 && $this->firstNumber < $this->secondNumber) {
            // Swap the numbers
            $temp = $this->firstNumber;
            $this->firstNumber = $this->secondNumber;
            $this->secondNumber = $temp;
        }
        
        // Create the captcha question
        $operationSymbol = $this->getOperationSymbol($this->operation);
        $this->captchaQuestion = "What is {$this->firstNumber} {$operationSymbol} {$this->secondNumber}?";
        
        // Store correct answer in session
        $this->storeCorrectAnswer();
    }
    
    // Get operation symbol
    private function getOperationSymbol($operation)
    {
        switch ($operation) {
            case 1:
                return '+';
            case 2:
                return '-';
            case 3:
                return '×';
            default:
                return '+';
        }
    }
    
    // Calculate correct answer
    private function calculateCorrectAnswer()
    {
        switch ($this->operation) {
            case 1:
                return $this->firstNumber + $this->secondNumber;
            case 2:
                return $this->firstNumber - $this->secondNumber;
            case 3:
                return $this->firstNumber * $this->secondNumber;
            default:
                return 0;
        }
    }
    
    // Store correct answer in session
    private function storeCorrectAnswer()
    {
        $correctAnswer = $this->calculateCorrectAnswer();
        Session::put('captcha_answer', $correctAnswer);
    }

    public function loadStats()
{
    $today = Carbon::today();
    $user = Auth::user();
    $userRoles = explode(',', $user->user_role);
    $isAdmin = in_array('1', $userRoles) || in_array('2', $userRoles);

    // Base query for tasks
    $taskQuery = Task::query();
    
    // If not admin, only show assigned tasks
    if (!$isAdmin) {
        $taskQuery->where('assigned_to', $user->id);
    }
    $taskQuery->where('team_id', $this->team_id);
    $startOfWeek = Carbon::now()->startOfWeek();
    $startOfMonth = Carbon::now()->startOfMonth();

    
    // Base query for todos
    $todoQuery = Todo::query();
        
    // If not admin, only show user's todos
    if (!$isAdmin) {
        $todoQuery->where('user_id', $user->id);
    }

    // Base query for leaves
    $leaveQuery = Leave::query();
        
    // If not admin, only show user's leaves
    if (!$isAdmin) {
        $leaveQuery->where('user_id', $user->id);
    }
    $leaveQuery->where('team_id', $this->team_id);
    // Calculate todo counts
    $totalTodos = (clone $todoQuery)->count();
    $todayTodos = (clone $todoQuery)->whereDate('created_at', $today)->count();
    $completedTodos = (clone $todoQuery)->where('status', 'completed')->count();
   
    // Calculate todo percentages
    $todoPercentage = $totalTodos > 0 ? ($todayTodos / $totalTodos) * 100 : 0;
    $completedTodoPercentage = $totalTodos > 0 ? ($completedTodos / $totalTodos) * 100 : 0;

       
    // Calculate task counts first
    $totalTasks = (clone $taskQuery)->count();
    $todayTasks = (clone $taskQuery)->whereDate('created_at', $today)->count();
    $pendingTasks = (clone $taskQuery)->where('status', 'not_started')->count();
    $inProgressTasks = (clone $taskQuery)->where('status', 'in_progress')->count();
    $completedTasks = (clone $taskQuery)->where('status', 'done')->count();

    // Calculate task percentages
    $taskPercentage = $totalTasks > 0 ? ($todayTasks / $totalTasks) * 100 : 0;
    $activeTasksPercentage = $totalTasks > 0 ? (($pendingTasks + $inProgressTasks) / $totalTasks) * 100 : 0;
    $completedPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
    $pendingPercentage = $totalTasks > 0 ? ($pendingTasks / $totalTasks) * 100 : 0;    
    $todoPercentage = $totalTodos > 0 ? ($todayTodos / $totalTodos) * 100 : 0;
    

    // Calculate task status counts
    $pendingTasks = (clone $taskQuery)
        ->where('status', 'not_started')
        ->count();
    
    $inProgressTasks = (clone $taskQuery)
        ->where('status', 'in_progress')
        ->count();
    
    $completedTasks = (clone $taskQuery)
        ->where('status', 'done')
        ->count();
    
    
    // Get today's leaves
    $usersOnLeaveToday = (clone $leaveQuery)
    ->whereDate('start_date', '<=', $today)
    ->whereDate('end_date', '>=', $today)
    ->where('status', 'approved')
    ->count();

    // Get yesterday's leaves for comparison
    $yesterday = Carbon::yesterday();
    $usersOnLeaveYesterday = (clone $leaveQuery)
        ->whereDate('start_date', '<=', $yesterday)
        ->whereDate('end_date', '>=', $yesterday)
        ->where('status', 'approved')
        ->count();

    // Calculate today's percentage
    $leaveTodayPercentage = $usersOnLeaveYesterday > 0 
        ? round((($usersOnLeaveToday - $usersOnLeaveYesterday) / $usersOnLeaveYesterday) * 100, 1) 
        : ($usersOnLeaveToday > 0 ? 100 : 0);

    // Get this week's leaves
    $startOfWeek = Carbon::now()->startOfWeek();
    $usersOnLeaveThisWeek = (clone $leaveQuery)
        ->where(function($query) use ($startOfWeek, $today) {
            $query->whereDate('start_date', '>=', $startOfWeek)
                ->whereDate('start_date', '<=', $today)
                ->orWhere(function($q) use ($startOfWeek) {
                    $q->whereDate('start_date', '<', $startOfWeek)
                    ->whereDate('end_date', '>=', $startOfWeek);
                });
        })
        ->where('status', 'approved')
        ->count();

    // Get previous week's leaves
    $previousWeekStart = Carbon::now()->subWeek()->startOfWeek();
    $previousWeekEnd = Carbon::now()->subWeek()->endOfWeek();
    $usersOnLeavePreviousWeek = (clone $leaveQuery)
        ->where(function($query) use ($previousWeekStart, $previousWeekEnd) {
            $query->whereDate('start_date', '>=', $previousWeekStart)
                ->whereDate('start_date', '<=', $previousWeekEnd)
                ->orWhere(function($q) use ($previousWeekStart) {
                    $q->whereDate('start_date', '<', $previousWeekStart)
                    ->whereDate('end_date', '>=', $previousWeekStart);
                });
        })
        ->where('status', 'approved')
        ->count();

    // Calculate weekly percentage
    $leaveWeekPercentage = $usersOnLeavePreviousWeek > 0 
        ? round((($usersOnLeaveThisWeek - $usersOnLeavePreviousWeek) / $usersOnLeavePreviousWeek) * 100, 1) 
        : ($usersOnLeaveThisWeek > 0 ? 100 : 0);

    // Get this month's leaves
    $startOfMonth = Carbon::now()->startOfMonth();
    $usersOnLeaveThisMonth = (clone $leaveQuery)
        ->where(function($query) use ($startOfMonth, $today) {
            $query->whereDate('start_date', '>=', $startOfMonth)
                ->whereDate('start_date', '<=', $today)
                ->orWhere(function($q) use ($startOfMonth) {
                    $q->whereDate('start_date', '<', $startOfMonth)
                    ->whereDate('end_date', '>=', $startOfMonth);
                });
        })
        ->where('status', 'approved')
        ->count();

    // Get previous month's leave count
    $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();
    $usersOnLeavePreviousMonth = (clone $leaveQuery)
        ->where(function($query) use ($previousMonthStart, $previousMonthEnd) {
            $query->whereDate('start_date', '>=', $previousMonthStart)
                ->whereDate('start_date', '<=', $previousMonthEnd)
                ->orWhere(function($q) use ($previousMonthStart) {
                    $q->whereDate('start_date', '<', $previousMonthStart)
                    ->whereDate('end_date', '>=', $previousMonthStart);
                });
        })
        ->where('status', 'approved')
        ->count();
    
    // Calculate this month's leave percentage change
    $leaveMonthPercentage = $usersOnLeavePreviousMonth > 0 
        ? round((($usersOnLeaveThisMonth - $usersOnLeavePreviousMonth) / $usersOnLeavePreviousMonth) * 100, 1) 
        : ($usersOnLeaveThisMonth > 0 ? 100 : 0);

    
    $this->stats = [
        // Today's Tasks
        [
            'title' => "Today's Tasks",
            'subtitle' => $isAdmin ? 'All tasks created today' : 'Your tasks created today',
            'count' => $todayTasks,
            'percentage' => round($taskPercentage, 1),
            'trend' => $taskPercentage >= 0 ? 'up' : 'down',
            'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
            'color' => '#3b82f6',
            'route' => route('task-collections') . 'filters[status]=in_progress&filters[dateRange]=today'
        ],
        // Active Tasks
        [
            'title' => 'Active Tasks',
            'subtitle' => $isAdmin ? 'Total ongoing tasks' : 'Your ongoing tasks',
            'count' => $pendingTasks + $inProgressTasks,
            'percentage' => round($activeTasksPercentage, 1),
            'trend' => 'neutral',
            'icon' => 'M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z',
            'color' => '#3b82f6',
            'route' => route('task-collections') . '?filters[search]=&filters[status]=in_progress&filters[priority]=&filters[department]=&filters[dateRange]=&filters[assignee]='
        ],
        // Completed Tasks
        [
            'title' => 'Completed Tasks',
            'subtitle' => $isAdmin ? 'All completed tasks' : 'Your completed tasks',
            'count' => $completedTasks,
            'percentage' => round($completedPercentage, 1),
            'trend' => 'up',
            'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'color' => '#3b82f6',
            'route' => route('task-collections') . '?filters[status]=done&filters[dateRange]=&currentTab=done'
        ],
        // Pending Tasks
        [
            'title' => 'Pending Tasks',
            'subtitle' => $isAdmin ? 'All pending tasks' : 'Your pending tasks',
            'count' => $pendingTasks,
            'percentage' => round($pendingPercentage, 1),
            'trend' => 'neutral',
            'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            'color' => '#3b82f6',
            'route' => route('task-collections') . '?filters[search]=&filters[status]=in_progress&filters[priority]=&filters[department]=&filters[dateRange]=&filters[assignee]='
        ],
        
        // Todo stats (Purple color: #8b5cf6)
        [
            'title' => "Today's Todos",
            'subtitle' => $isAdmin ? 'All todos created today' : 'Your todos created today',
            'count' => $todayTodos,
            'percentage' => round($todoPercentage, 1),
            'trend' => $todoPercentage >= 0 ? 'up' : 'down',
            'icon' => 'M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75',
            'color' => '#8b5cf6',
            'route'=>route('todos-list').'?filters[dateRange]=&filters[status]=in_progress&currentTab=in_progress'
        ],
        [
            'title' => 'Completed Todos',
            'subtitle' => $isAdmin ? 'All completed todos' : 'Your completed todos',
            'count' => $completedTodos,
            'percentage' => round($completedTodoPercentage, 1),
            'trend' => 'up',
            'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'color' => '#8b5cf6',
            'route'=>route('todos-list').'?filters[search]=&filters[date]=&filters[priority]=&filters[dateRange]=&filters[status]=completed&currentTab=completed'
            
        ],
        
        // Leave stats (Amber color: #ca8a04)
        [
            'title' => $isAdmin ? 'Users on Leave' : 'Leave Status',
            'subtitle' => 'Today',
            'count' => $usersOnLeaveToday,
            'percentage' => $leaveTodayPercentage,
            'trend' => $leaveTodayPercentage > 0 ? 'up' : ($leaveTodayPercentage < 0 ? 'down' : 'neutral'),
            'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
            'color' => '#ca8a04'
        ],
        [
            'title' => $isAdmin ? 'Users on Leave' : 'Leave Status',
            'subtitle' => 'This Week',
            'count' => $usersOnLeaveThisWeek,
            'percentage' => $leaveWeekPercentage,
            'trend' => $leaveWeekPercentage > 0 ? 'up' : ($leaveWeekPercentage < 0 ? 'down' : 'neutral'),
            'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
            'color' => '#ca8a04'
        ],
        [
            'title' => $isAdmin ? 'Users on Leave' : 'Leave Status',
            'subtitle' => 'This Month',
            'count' => $usersOnLeaveThisMonth,
            'percentage' => round($leaveMonthPercentage, 1),
            'trend' => $leaveMonthPercentage > 0 ? 'up' : ($leaveMonthPercentage < 0 ? 'down' : 'neutral'),
            'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
            'color' => '#ca8a04'
        ]
    ];
    }

    public function submitContactForm()
    {
        // Validate form data
        $this->validate();
        
        // Prepare form data for email
        $formData = [
            'contact_name' => $this->contact_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'requirements' => $this->requirements,
        ];
        
        // Send email to admin
        Mail::to(env('ADMIN_EMAIL'))->send(new ContactFormMail($formData));
        
        // Show success message
        $this->showSuccessMessage = true;
        
        // Reset form fields
        $this->resetForm();
        
        // Generate a new captcha
        $this->generateMathCaptcha();
        
        
        // Hide success message after 5 seconds
        $this->dispatch('contact-form-submitted');
    }

    /**
     * Reset form fields
     */
    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->company = '';
        $this->requirements = '';
        $this->captchaAnswer = '';
    }
    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}