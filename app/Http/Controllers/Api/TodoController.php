<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubDepartments;
use App\Models\Task;
use App\Models\Todo;
use App\Models\TodoAttachment;
use App\Models\TodoNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Log;
use Str;
use Validator;

class TodoController extends Controller
{
    public function __construct()
    {
    }
    public function listTodos(Request $request)
    {
        $userId = $request->user()->id;
        $perPage = $request->input('per_page', 15);
        $status = $request->input('status');
        $serchByTitle = $request->input('search');
        // Validate per_page parameter
        $validatedData = $request->validate([
            'per_page' => 'sometimes|integer|min:1|max:100'
        ]);
        
        $query = Todo::with(['created_user', 'notes']);
        // Apply tab-based filtering
        if ($status != '') {
            $query->where('status', $status);
        }

        // Apply other filters
        if($serchByTitle!=""){
            $query->where('title', 'like', '%'.$serchByTitle.'%');
        }
        // $query->where('user_id',  $userId);

        $todos = $query->orderBy("due_date","asc")->paginate($perPage);
        return response()->json([
            'status' => 'success',
            'total_records'=>$todos->count(),
            'data' => $todos
        ]);
    }

    public function todoDetails(Request $request, $id)
    {
        $userId = $request->user()->id;
        
        // Find the task with all related data
        $todo = Todo::with(['created_user', 'notes'])->where('id', $id)->firstOrFail();
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
        $todo = $todo->toArray();
         // Process task images to add full URLs
         if (isset($todo['attachments']) && !empty($todo['attachments'])) {
            foreach ($todo['attachments'] as &$image) {
                // Add the full URL to the image
                $image['image_url'] = $this->getFullImageUrl($image['file_path']);
            }
        }
        
      
        return response()->json([
            'status' => 'success',
            'data' => $todo
        ]);
    }
    private function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;

        return number_format($size / pow(1024, $power), 2, '.', ',').' '.$units[$power];
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

    public function getAllStatuses()
    {

        $statuses = [
            [
                'value' => 'pending',
                'label' => 'Pending'
            ],
            [
                'value' => 'in_progress',
                'label' => 'In Progress'
            ],
            [
                'value' => 'completed',
                'label' => 'Completed'
            ],
            [
                'value' => 'cancelled',
                'label' => 'Cancelled'
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
            $task = Todo::where('uuid', $uuid)->firstOrFail();
            
            // Store the old status for logging
            $oldStatus = $task->status;
            $newStatus = $request->status;
            
            // Update the task status
            $task->status = $newStatus;
            $task->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Todo status updated successfully',
                'data' => [
                    'task' => $task,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Todo not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating todo status', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getTodoCountsByStatus(Request $request)
    {
        $userId = $request->user()->id;
        $searchByTitle = $request->input('search');
        
        // Base query
        $query = Todo::query();
        
        // Apply search filter if provided
        if ($searchByTitle) {
            $query->where('title', 'like', '%' . $searchByTitle . '%');
        }
        
        // You can uncomment this if you want to filter by the current user
        // $query->where('user_id', $userId);
        
        // Get counts for each status using a single query
        $counts = $query->selectRaw('status, COUNT(*) as count')
                        ->groupBy('status')
                        ->pluck('count', 'status')
                        ->toArray();
        
        // Ensure all possible statuses have a count (even if zero)
        $allStatuses = ['pending', 'completed', 'in_progress', 'cancelled']; // Add all your possible statuses here
        $result = [];
        
        foreach ($allStatuses as $status) {
            $result[$status] = $counts[$status] ?? 0;
        }
        
        // Add total count
        $result['total'] = array_sum($counts);
        
        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required   ',
            'media_files.*' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create new todo
            $todo = new Todo();
            $todo->id = rand(100, 999) . time();
            $todo->uuid = Str::uuid()->toString();
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->due_date = date('Y-m-d H:i:s', strtotime($request->due_date));
            $todo->priority = $request->priority;
            $todo->status = $request->status;
            $todo->user_id = Auth::id();
            $todo->assigned_by = Auth::id();
            
            if ($request->status === 'completed') {
                $todo->completed_at = now();
            }
            
            $todo->save();

            // Handle file uploads
            if ($request->hasFile('media_files')) {
                foreach ($request->file('media_files') as $file) {
                    $fileName = $file->getClientOriginalName();
                    $filePath = $file->store('todo-attachments', 'public');
                   
                    TodoAttachment::create([
                        'todo_id' => $todo->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientOriginalExtension(),
                        'file_size' => $file->getSize()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Todo created successfully',
                'data' => $todo
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified todo resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
            'media_files.*' => 'nullable|file|max:10240', // 10MB max file size
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $todo = Todo::where('uuid', $uuid)->firstOrFail();
            
            $todo->update([
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => date('Y-m-d H:i:s', strtotime($request->due_date)),
                'priority' => $request->priority,
                'status' => $request->status,
                'completed_at' => $request->status === 'completed' ? now() : null,
            ]);

            // Handle file uploads
            if ($request->hasFile('media_files')) {
                foreach ($request->file('media_files') as $file) {
                    $fileName = $file->getClientOriginalName();
                    $filePath = $file->store('todo-attachments', 'public');
                    
                    TodoAttachment::create([
                        'todo_id' => $todo->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientOriginalExtension(),
                        'file_size' => $file->getSize()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Todo updated successfully',
                'data' => $todo
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function addNote(Request $request, $todoUuid)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find the todo
            $todo = Todo::where('uuid', $todoUuid)->firstOrFail();
            
            // Create note
            $note = TodoNote::create([
                'todo_id' => $todo->id,
                'user_id' => Auth::id(),
                'content' => $request->content
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Note added successfully',
                'data' => $note
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a note
     * 
     * @param int $noteId
     * @return \Illuminate\Http\Response
     */
    public function deleteNote($noteId)
    {
        try {
            $note = TodoNote::findOrFail($noteId);
            
            // Check if user owns the note or is admin
            if ($note->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }
            
            $note->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all notes for a todo
     * 
     * @param string $todoUuid
     * @return \Illuminate\Http\Response
     */
    public function getNotes($todoUuid)
    {
        try {
            $todo = Todo::where('uuid', $todoUuid)->firstOrFail();
            
            $notes = TodoNote::with('user:id,name')
                ->where('todo_id', $todo->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $notes
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}