<?php

namespace App\Livewire;

use App\Models\Video;
use App\Models\VideoAttachment;
use App\Traits\HasSubscriptionCheck;
use Livewire\Component;
use App\Models\Photo;
use App\Models\PhotoAttachment;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Str;

class Videos extends Component
{
    use WithFileUploads;
    use HasSubscriptionCheck;
    public $moduleTitle="Videos";
    public $album_title;
    public $priority = 'medium';
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
        'album_title' => 'required|min:3',
        'priority' => 'required|in:low,medium,high',
        'mediaFiles' => 'required|array|min:1',
        'mediaFiles.*' => 'required|file',
    ];

    public function loadTodo($uuid)
    {
        try {
            $todo = Video::where('uuid', $uuid)->firstOrFail();
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
        $attachment = VideoAttachment::findOrFail($attachmentId);
        // Delete the file from storage
        Storage::disk('public')->delete($attachment->file_path);
        // Delete the record
        $attachment->delete();
        // Refresh the existing files list
        $this->existingMediaFiles = VideoAttachment::where('photo_id', $attachment->photo_id)->get();
    }

    public function savePhoto()
    {
        $this->validate();
        
        try {
            if ($this->isUpdate) {
                $todo = Video::where('uuid', $this->todoUuid)->firstOrFail();
                $todo->update([
                    'album_title' => $this->album_title,
                    'priority' => $this->priority,
                    'completed_at' => $this->status === 'completed' ? now() : null,
                ]);
            } else {
                $todo = new Video();
                $todo->id=rand('100', '999') . time();
                $todo->uuid = Str::uuid()->toString();
                $todo->album_title = $this->album_title;
                $todo->priority = $this->priority;
                $todo->user_id = Auth::id();
                $todo->save();
            }
    
            // Handle new file uploads
            if (!empty($this->mediaFiles)) {
                foreach ($this->mediaFiles as $file) {
                    $fileName = $file->getClientOriginalName();
                    $filePath = $file->store('video-attachments/'.Auth::id().'/'.$todo->id, 'public');
                    VideoAttachment::create([
                        'video_id' => $todo->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientOriginalExtension(),
                        'file_size' => $file->getSize()
                    ]);
                }
            }
    
            $message = $this->isUpdate ? $this->commonUpdateSuccess : $this->commonCreateSuccess;
            
            $this->dispatch('notify-success', $message);
            $this->reset(['album_title', 'priority', 'mediaFiles']);
            return redirect()->route('my-videos');
            

            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', $e->getMessage());
        }
    }
  
    public function render()
    {
        return view('livewire.videos.video-add')->layout('layouts.app');
    }
}