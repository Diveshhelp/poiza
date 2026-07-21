<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Log;
use Carbon\Carbon;

class TaskCreater extends Command
{
    protected $signature = 'task:creator {--catchup : Create all pending tasks from start to current date}';
    protected $description = 'Create repetition tasks automatically';

    public function handle()
    {
        //Setup code on cpanel godaddy
        //* 	* 	* 	* 	* 	/usr/local/bin/php /home/nx2l6p3g8f7z/public_html/artisan schedule:run >> /dev/null 2>&1
        Log::info("TaskCreater Run at : " . Carbon::now());

        // Find master tasks that should be repeated
        $tasks = Task::where('is_master_task', 1)
                     ->where('repetition', '!=', 'no')
                     ->get();
        
        $now = Carbon::now();
        $tasksCreated = 0;
        $catchupMode = $this->option('catchup');
        
        if ($catchupMode) {
            $this->info("Running in catch-up mode - will create all pending tasks from start to current date");
        }
        
        foreach ($tasks as $task) {
            // Parse repetition details
            $repetitionDetails = json_decode($task->repetition_details, true);
            $repetitionType = $repetitionDetails['type'] ?? 'no';
            
            // Get repeatUntilDate if specified
            $repeatUntilDate = null;
            if (isset($repetitionDetails['repeatUntilDate']) && !empty($repetitionDetails['repeatUntilDate'])) {
                $repeatUntilDate = Carbon::parse($repetitionDetails['repeatUntilDate']);
            }
            
            // Get maxOccurrences if specified
            $maxOccurrences = null;
            if (isset($repetitionDetails['maxOccurrences']) && $repetitionDetails['maxOccurrences'] > 0) {
                $maxOccurrences = (int)$repetitionDetails['maxOccurrences'];
            }
            
            if ($catchupMode) {
                $tasksCreated += $this->catchupTasks($task, $repetitionType, $repetitionDetails, $repeatUntilDate, $maxOccurrences);
            } else {
                // Original logic for creating just the next task if within window
                $tasksCreated += $this->createNextTaskIfNeeded($task, $repetitionType, $repetitionDetails, $repeatUntilDate, $maxOccurrences);
            }
        }
        
        $this->info("Created {$tasksCreated} repeated tasks");
        return 0;
    }
    
    /**
     * Create all pending tasks from the original task's deadline to the current date
     */
    protected function catchupTasks($masterTask, $repetitionType, $repetitionDetails, $repeatUntilDate = null, $maxOccurrences = null)
    {
        $count = 0;
        $now = Carbon::now();
        $baseDeadline = Carbon::parse($masterTask->deadline);
        
        // Skip if the master task's deadline is in the future
        if ($baseDeadline->greaterThan($now)) {
            return $count;
        }
        
        // If a repeatUntilDate is set and is in the past, use that instead of now
        $endDate = $now;
        if ($repeatUntilDate && $repeatUntilDate->lessThan($now)) {
            $endDate = $repeatUntilDate;
            $this->info("Task {$masterTask->title} has a repeat until date of {$repeatUntilDate->format('Y-m-d')}");
        }
        
        $currentDeadline = clone $baseDeadline;
        
        // Calculate how many instances already exist
        $existingCount = Task::where('title', $masterTask->title)
                             ->where('is_master_task', 0)
                             ->count();
        
        // Check if we've already reached max occurrences
        if ($maxOccurrences !== null && $existingCount >= $maxOccurrences) {
            $this->info("Task {$masterTask->title} has reached its maximum occurrences ({$maxOccurrences})");
            return $count;
        }
        
        // Generate all instances that should exist from the master task deadline until endDate
        while (true) {
            // Check if we've reached max occurrences
            if ($maxOccurrences !== null && $existingCount + $count >= $maxOccurrences) {
                $this->info("Reached the maximum occurrences ({$maxOccurrences}) for task: {$masterTask->title}");
                break;
            }
            
            // Move to the next deadline based on repetition type
            $nextDeadline = $this->calculateNextDeadline($currentDeadline, $repetitionType, $repetitionDetails);
            
            if (!$nextDeadline || $nextDeadline->greaterThan($endDate)) {
                break; // Stop if we've reached the end date or current date
            }
            
            // Check if repeatUntilDate is set and if we've exceeded it
            if ($repeatUntilDate && $nextDeadline->greaterThan($repeatUntilDate)) {
                $this->info("Reached the repetition end date for task: {$masterTask->title}");
                break;
            }
            
            // Check if this task already exists
            $existingTask = Task::where('title', $masterTask->title)
                                ->where('deadline', $nextDeadline->format('Y-m-d'))
                                ->exists();
                                
            if (!$existingTask) {
                $newTask = $masterTask->replicate();
                $newTask->uuid = (string) \Illuminate\Support\Str::uuid();
                $newTask->is_master_task = 0;
                $newTask->deadline = $nextDeadline->format('Y-m-d');
                $newTask->status = 'not_started';
                $newTask->created_at = now();
                $newTask->updated_at = now();
                $newTask->save();
                
                $count++;
                $this->info("Created catch-up task for: {$masterTask->title} with deadline {$nextDeadline->format('Y-m-d')}");
            }
            
            $currentDeadline = $nextDeadline;
        }
        
        return $count;
    }
    
    /**
     * Create the next instance of a task if it's within the creation window
     */
    protected function createNextTaskIfNeeded($task, $repetitionType, $repetitionDetails, $repeatUntilDate = null, $maxOccurrences = null)
    {
        $now = Carbon::now();
        $nextDeadline = $this->calculateNextDeadline(Carbon::parse($task->deadline), $repetitionType, $repetitionDetails);
        
        if (!$nextDeadline) {
            return 0; // Skip if no valid next deadline
        }
        
        // Check if we've reached the repetition end date
        if ($repeatUntilDate && $nextDeadline->greaterThan($repeatUntilDate)) {
            $this->info("Skipping task {$task->title} as it has reached its repeat until date");
            return 0;
        }
        
        // Calculate how many instances already exist
        $existingCount = Task::where('title', $task->title)
                             ->where('is_master_task', 0)
                             ->count();
        
        // Check if we've already reached max occurrences
        if ($maxOccurrences !== null && $existingCount >= $maxOccurrences) {
            $this->info("Skipping task {$task->title} as it has reached its maximum occurrences ({$maxOccurrences})");
            return 0;
        }
        
        // Check if we're within the create_before_days window
        $createDate = (clone $nextDeadline)->subDays($task->create_before_days);
        $shouldCreateTask = $now->greaterThanOrEqualTo($createDate);
        
        // Create the new task if needed
        if ($shouldCreateTask) {
            // Check if this task already exists to prevent duplicates
            $existingTask = Task::where('title', $task->title)
                                ->where('deadline', $nextDeadline->format('Y-m-d'))
                                ->exists();
                                
            if (!$existingTask) {
                $newTask = $task->replicate();
                $newTask->uuid = (string) \Illuminate\Support\Str::uuid();
                $newTask->is_master_task = 0;
                $newTask->deadline = $nextDeadline->format('Y-m-d');
                $newTask->status = 'not_started';
                $newTask->created_at = now();
                $newTask->updated_at = now();
                $newTask->save();
                
                $this->info("Created repeated task for: {$task->title} with deadline {$nextDeadline->format('Y-m-d')}");
                return 1;
            }
        }
        
        return 0;
    }
    
    /**
     * Calculate the next deadline based on repetition type
     */
    protected function calculateNextDeadline($currentDeadline, $repetitionType, $repetitionDetails)
    {
        $nextDeadline = null;
        
        switch ($repetitionType) {
            case 'daily':
                $nextDeadline = (clone $currentDeadline)->addDay();
                break;
                
            case 'weekly':
                $nextDeadline = (clone $currentDeadline)->addWeek();
                break;
                
            case 'monthly':
                $nextDeadline = (clone $currentDeadline)->addMonth();
                break;
                
            case 'yearly':
                $nextDeadline = (clone $currentDeadline)->addYear();
                break;
                
            case 'custom':
                // Handle custom repetition using values in $repetitionDetails['values']
                // If you provide details about your custom implementation, I can adapt this
                $this->info("Custom repetition needs custom logic - skipping");
                break;
        }
        
        return $nextDeadline;
    }
}