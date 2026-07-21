<?php

namespace App\Livewire;

use App\Traits\HasSubscriptionCheck;
use Livewire\Component;
use App\Models\Todo;
use App\Models\TodoAttachment;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Str;

class Todos extends Component
{
    use WithFileUploads;
    use HasSubscriptionCheck;
    public $moduleTitle=TODO_TITLE;
    public $title;
    public $description;
    public $due_date;
    public $priority = 'medium';
    public $status = 'pending';
    public $isUpdate = false;
    public $mediaFiles = [];
    
    public $showSuccessMessage = false;
    public $successMessage = '';
    public $showWarningMessage = false;
    public $showErrorMessage=false;
    
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonNotDeleteSuccess;

    public $todoUuid;
    public $existingMediaFiles = [];
    
    // protected function queryString = ['uuid'];
    public $team_id;
    public function mount()
    {
        if (!$this->ensureSubscription()) {
            abort(402, 'Access denied. Subscription is over, Please renew the plan and get the full access!');
        }
        $this->user_id = Auth::User()->id;
        $this->team_id=Auth::user()->currentTeam->id;
        $this->team_name=Auth::user()->currentTeam->name;
          
        $this->commonCreateSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_DELETE_SUCCESS);
        $this->commonNotDeleteSuccess=str_replace("{module-name}",$this->moduleTitle,COMMON_DELETE_FAILURE);
     
        $uuid = request()->query('uuid');
        if ($uuid) {
           $this->loadTodo($uuid);
        }         
    }
     
    protected $rules = [
        'title' => 'required|min:3',
        'description' => 'nullable',
        'due_date' => 'required|date|after_or_equal:today',
        'priority' => 'required|in:low,medium,high',
        'status' => 'required|in:pending,in_progress,completed,cancelled',
        'mediaFiles.*' => 'nullable|file'
    ];

    public function loadTodo($uuid)
    {
        try {
            $todo = Todo::where('uuid', $uuid)->firstOrFail();
            $this->todoUuid = $uuid;
            $this->title = $todo->title;
            $this->description = $todo->description;
            $this->due_date = $todo->due_date ? date('Y-m-d\TH:i', strtotime($todo->due_date)) : null;
            $this->priority = $todo->priority;
            $this->status = $todo->status;
            $this->isUpdate = true;

            // Load existing attachments
            $this->existingMediaFiles = $todo->attachments()->get();
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Todo not found');
            return redirect()->route('todos.index');
        }
    }

    public function removeFile($index)
    {
        array_splice($this->mediaFiles, $index, 1);
    }

    public function removeExistingFile($attachmentId)
    {
        $attachment = TodoAttachment::findOrFail($attachmentId);
        // Delete the file from storage
        Storage::disk('public')->delete($attachment->file_path);
        // Delete the record
        $attachment->delete();
        // Refresh the existing files list
        $this->existingMediaFiles = TodoAttachment::where('todo_id', $attachment->todo_id)->get();
    }

    public function saveTodo()
    {
        $this->validate();
        
        try {
            if ($this->isUpdate) {
                $todo = Todo::where('uuid', $this->todoUuid)->firstOrFail();
                $todo->update([
                    'title' => $this->title,
                    'description' => $this->description,
                    'due_date' => $this->due_date,
                    'priority' => $this->priority,
                    'status' => $this->status,
                    'completed_at' => $this->status === 'completed' ? now() : null,
                ]);
            } else {
                $todo = new Todo();
                $todo->id=rand('100', '999') . time();
                $todo->uuid = Str::uuid()->toString();
                $todo->title = $this->title;
                $todo->description = $this->description;
                $todo->due_date = date('Y-m-d H:i:s',strtotime($this->due_date));
                $todo->priority = $this->priority;
                $todo->status = $this->status;
                $todo->user_id = Auth::id();
                $todo->assigned_by = Auth::id();
                if ($this->status === 'completed') {
                    $todo->completed_at = now();
                }
                $todo->team_id=$this->team_id;
                $todo->save();
            }
    
            // Handle new file uploads
            if (!empty($this->mediaFiles)) {
                foreach ($this->mediaFiles as $file) {
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
    
            $message = $this->isUpdate ? $this->commonUpdateSuccess : $this->commonCreateSuccess;
            
            $this->dispatch('notify-success', $message);

            
            if ($this->isUpdate) {
                return redirect()->route('todos-list');
            }
            
            $this->reset(['title', 'description', 'due_date', 'priority', 'status', 'mediaFiles']);
            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', $e->getMessage());
        }
    }
  
    public function render()
    {
        return view('livewire.todos.todo-add')->layout('layouts.app');
    }
}