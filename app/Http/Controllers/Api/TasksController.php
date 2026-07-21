<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubDepartments;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Log;
use Validator;

class TasksController extends Controller
{
    public function __construct()
    {
    }

    public function listTasks(Request $request)
    {
        $userId = $request->user()->id;
        $perPage = $request->input('per_page', 15);
        
        // Validate per_page parameter
        $validatedData = $request->validate([
            'per_page' => 'sometimes|integer|min:1|max:100'
        ]);
        
        $tasks = Task::where('created_by', $userId)
            ->orderBy("id", "DESC")
            ->paginate($perPage);
            
        return response()->json([
            'status' => 'success',
            'data' => $tasks
        ]);
    }

    public function taskDetails(Request $request, $id)
    {
        $userId = $request->user()->id;
        
        // Find the task with all related data
        $task = Task::with([
            'department_object',
            'assign_to',
            'created_user',
            'notes.user',
            'task_images',
            'notes',
            'statusHistories'
        ])->find($id);
        
        // Check if task exists and user has permission to view it
        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found'
            ], 404);
        }
        
        // Format the task data
        $taskData = $task->toArray();
        $taskData['created_at'] = $task->created_at->format('Y-m-d h:i A');
        
        // Process sub-departments
        if ($task->sub_departments) {
            $subDeptIds = explode(",", $task->sub_departments);
            if (!empty($subDeptIds)) {
                $subDepartments = SubDepartments::whereIn('id', $subDeptIds)
                    ->get()
                    ->map(function($subDept) {
                        return [
                            'id' => $subDept->id,
                            'name' => $subDept->name
                        ];
                    });
                $taskData['sub_departments_list'] = $subDepartments;
            }
        } else {
            $taskData['sub_departments_list'] = [];
        }

         // Process task images to add full URLs
         if (isset($taskData['task_images']) && !empty($taskData['task_images'])) {
            foreach ($taskData['task_images'] as &$image) {
                // Add the full URL to the image
                $image['image_url'] = $this->getFullImageUrl($image['image_path']);
            }
        }
        
        // Process repetition details
        if ($task->repetition_details) {
            $taskData['repetition_data'] = $this->formatRepetitionForDisplay(
                json_decode($task->repetition_details, true)
            );
        } else {
            $taskData['repetition_data'] = [
                'frequency_label' => 'One Time',
                'frequency' => 'no'
            ];
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $taskData
        ]);
    }
    private function getFullImageUrl($imagePath)
    {
        // Check if the image path already has a full URL
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }
        
        // If using Laravel's public disk
        if(env('APP_ENV')=="production"){
            return url("public".Storage::url($imagePath));
        }else{
            return url(Storage::url($imagePath));
        }
        
        // If using S3 or other cloud storage, you might use:
        // return Storage::disk('s3')->url($imagePath);
        
        // Or if images are in public directory:
        // return asset($imagePath);
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

    public function getAllStatuses()
    {
        $statuses = [
            [
                'value' => 'not_started',
                'label' => 'Work Not Started'
            ],
            [
                'value' => 'in_progress',
                'label' => 'In Progress'
            ],
            [
                'value' => 'done',
                'label' => 'Done'
            ],
            [
                'value' => 'delayed',
                'label' => 'Delayed'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $statuses
        ]);
    }
    public function updateStatus(Request $request, $uuid)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'status' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find the task by UUID
            $task = Task::where('uuid', $uuid)->firstOrFail();
            
            // Store the old status for logging
            $oldStatus = $task->status;
            $newStatus = $request->status;
            
            // Update the task status
            $task->status = $newStatus;
            $task->save();
            
            // Log the status change
            Log::info("Task status changed", [
                'task_id' => $task->id,
                'uuid' => $uuid,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'user_id' => auth()->id() ?? 'system'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully',
                'data' => [
                    'task' => $task,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating task status', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the task status'
            ], 500);
        }
    }
}