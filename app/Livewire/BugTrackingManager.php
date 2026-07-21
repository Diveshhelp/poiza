<?php
namespace App\Livewire;

use App\Models\BugMaster;
use App\Models\BugImage;
use App\Models\BugComment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BugTrackingManager extends Component
{
    use WithPagination, WithFileUploads;

    // Bug Master Properties
    
    public $title = '';
    public $explanation = '';

    public $status = 'Draft';

    public $client_status = 'Created';

    public $type = 'Bug';

    public $assigned_to = '';

    public $priority = 1;

    public $due_date = '';

    public $newComment = '';

    public $commentType = 'General';
    
    // Component State
    public $search = '';
    public $isUpdate = false;
    public $editUuid = '';
    public $team_id;
    public $showModal = false;
    public $showDetailModal = false;
    public $selectedBug;

    // Filters
    public $filterStatus = '';
    public $filterClientStatus = '';
    public $filterType = '';
    public $filterPriority = '';
    public $filterAssignedTo = '';

    public $existingImages = [];

    // Comments

    public $isInternalComment = false;
    public $showComments = false;
    public $images=[];
    // Constants
    public $statusOptions = [
        'Draft', 
        'Ready for work', 
        'In progress', 
        'Attention required', 
        'Deployed', 
        'Done'
    ];
    
    public $clientStatusOptions = [
        'Created', 
        'In Review', 
        'In Development', 
        'In Testing', 
        'Done', 
        'Ready for check'
    ];
    
    public $typeOptions = ['Bug', 'Enhancement', 'Justification'];
    
    public $priorityOptions = [
        1 => 'Low', 
        2 => 'Medium', 
        3 => 'High', 
        4 => 'Critical'
    ];
    
    public $commentTypeOptions = [
        'General', 
        'Status Update', 
        'Internal Note', 
        'Client Response'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterClientStatus' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterPriority' => ['except' => ''],
        'filterAssignedTo' => ['except' => ''],
    ];
    public function mount(): void
    {
        $this->team_id = Auth::user()->currentTeam->id;
        $this->resetForm();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatedFilterClientStatus(): void
    {
        $this->resetPage();
    }

    public function updatedFilterType(): void
    {
        $this->resetPage();
    }

    public function updatedFilterPriority(): void
    {
        $this->resetPage();
    }

    public function updatedFilterAssignedTo(): void
    {
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->title = '';
        $this->explanation = '';
        $this->status = 'Draft';
        $this->client_status = 'Created';
        $this->type = 'Bug';
        $this->assigned_to = '';
        $this->priority = 1;
        $this->due_date = '';
        $this->images = [];
        $this->existingImages = [];
        $this->isUpdate = false;
        $this->editUuid = '';
        $this->newComment = '';
        $this->commentType = 'General';
        $this->isInternalComment = false;
        $this->resetErrorBag();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterClientStatus = '';
        $this->filterType = '';
        $this->filterPriority = '';
        $this->filterAssignedTo = '';
        $this->resetPage();
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function openDetailModal($uuid): void
    {
        $this->selectedBug = $this->getBugDetails($uuid);
        $this->showDetailModal = true;
        $this->showComments = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedBug = null;
        $this->showComments = false;
        $this->newComment = '';
    }

    private function getBugDetails($uuid)
    {
        return BugMaster::with([
            'user:id,name,email',
            'assignedUser:id,name,email',
            'createdBy:id,name',
            'updatedBy:id,name',
            'images' => function($query) {
                $query->orderBy('sort_order');
            },
            'comments' => function($query) {
                $query->with('createdBy:id,name')
                      ->orderBy('created_at', 'desc');
            }
        ])
        ->where('uuid', $uuid)
        ->where('team_id', $this->team_id)
        ->first();
    }

    public function saveBug(): void
    {
        $this->validate([
          'title' => 'required|string|max:255',
            'explanation' => 'required|string',
            'status' => 'required|in:Draft,Ready for work,In progress,Attention required,Deployed,Done',
            'client_status' => 'required|in:Created,In Review,In Development,In Testing,Done,Ready for check',
            'type' => 'required|in:Bug,Enhancement,Justification',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|integer|min:1|max:4',
            'due_date' => 'nullable|date|after:today',
            'images.*' => 'nullable|image|max:5120', // 5MB max per image
        ]);


        try {
            DB::beginTransaction();

            if ($this->isUpdate) {
                $this->updateBug();
            } else {
                $this->createBug();
            }

            DB::commit();
            $this->closeModal();
            $this->dispatch('notify-success', 'Bug ' . ($this->isUpdate ? 'updated' : 'created') . ' successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify-error', 'Error: ' . $e->getMessage());
        }
    }

    private function createBug(): void
    {
        $bug = BugMaster::create([
            'title' => trim($this->title),
            'explanation' => trim($this->explanation),
            'status' => $this->status,
            'client_status' => $this->client_status,
            'type' => $this->type,
            'user_id' => auth()->id(),
            'team_id' => $this->team_id,
            'assigned_to' => $this->assigned_to ?: null,
            'priority' => $this->priority,
            'due_date' => $this->due_date ?: null,
            'created_by' => auth()->id()
        ]);

        $this->uploadImages($bug->id);
        $this->clearBugCache();
    }

    private function updateBug(): void
    {
        $bug = BugMaster::where('uuid', $this->editUuid)
            ->where('team_id', $this->team_id)
            ->firstOrFail();

        $bug->update([
            'title' => trim($this->title),
            'explanation' => trim($this->explanation),
            'status' => $this->status,
            'client_status' => $this->client_status,
            'type' => $this->type,
            'assigned_to' => $this->assigned_to ?: null,
            'priority' => $this->priority,
            'due_date' => $this->due_date ?: null,
            'updated_by' => auth()->id()
        ]);

        $this->uploadImages($bug->id);
        $this->clearBugCache();
    }

    private function uploadImages($bugMasterId): void
    {
        if (empty($this->images)) {
            return;
        }

        $sortOrder = BugImage::where('bug_master_id', $bugMasterId)->max('sort_order') ?? 0;

        foreach ($this->images as $image) {
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('bug-images', $filename, 'public');

            BugImage::create([
                'bug_master_id' => $bugMasterId,
                'image_path' => $path,
                'original_name' => $image->getClientOriginalName(),
                'mime_type' => $image->getMimeType(),
                'file_size' => $image->getSize(),
                'sort_order' => ++$sortOrder,
                'uploaded_by' => auth()->id()
            ]);
        }
    }

    public function editBug($uuid): void
    {
        $bug = BugMaster::with('images')
            ->where('uuid', $uuid)
            ->where('team_id', $this->team_id)
            ->firstOrFail();

        $this->title = $bug->title;
        $this->explanation = $bug->explanation;
        $this->status = $bug->status;
        $this->client_status = $bug->client_status;
        $this->type = $bug->type;
        $this->assigned_to = $bug->assigned_to;
        $this->priority = $bug->priority;
        $this->due_date = $bug->due_date?->format('Y-m-d');
        $this->existingImages = $bug->images->toArray();
        $this->editUuid = $uuid;
        $this->isUpdate = true;
        $this->showModal = true;
    }

    public function deleteBug($uuid): void
    {
        try {
            DB::beginTransaction();

            $bug = BugMaster::where('uuid', $uuid)
                ->where('team_id', $this->team_id)
                ->firstOrFail();

            // Delete associated images from storage
            foreach ($bug->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $bug->delete();
            $this->clearBugCache();

            DB::commit();
            $this->dispatch('notify-success', 'Bug deleted successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify-error', 'Error deleting bug: ' . $e->getMessage());
        }
    }

    public function deleteImage($imageUuid): void
    {
        try {
            $image = BugImage::where('uuid', $imageUuid)->firstOrFail();
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            
            $this->dispatch('notify-success', 'Image deleted successfully!');
            
            // Refresh existing images if in edit mode
            if ($this->isUpdate) {
                $bug = BugMaster::with('images')->where('uuid', $this->editUuid)->first();
                $this->existingImages = $bug->images->toArray();
            }
            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error deleting image: ' . $e->getMessage());
        }
    }

    public function updateStatus($uuid, $newStatus): void
    {
        try {
            $bug = BugMaster::where('uuid', $uuid)
                ->where('team_id', $this->team_id)
                ->firstOrFail();

            $bug->update([
                'status' => $newStatus,
                'updated_by' => auth()->id()
            ]);

            $this->clearBugCache();
            $this->dispatch('notify-success', 'Status updated successfully!');
            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error updating status: ' . $e->getMessage());
        }
    }

    public function updateClientStatus($uuid, $newClientStatus): void
    {
        try {
            $bug = BugMaster::where('uuid', $uuid)
                ->where('team_id', $this->team_id)
                ->firstOrFail();

            $bug->update([
                'client_status' => $newClientStatus,
                'updated_by' => auth()->id()
            ]);

            $this->clearBugCache();
            $this->dispatch('notify-success', 'Client status updated successfully!');
            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error updating client status: ' . $e->getMessage());
        }
    }

    public function addComment(): void
    {
        $this->validate([
            'newComment' => 'required|string|max:2000',
            'commentType' => 'required|in:' . implode(',', $this->commentTypeOptions)
        ]);

        try {
            BugComment::create([
                'bug_master_id' => $this->selectedBug->id,
                'comment' => trim($this->newComment),
                'comment_type' => $this->commentType,
                'is_internal' => $this->isInternalComment,
                'created_by' => auth()->id()
            ]);

            $this->newComment = '';
            $this->commentType = 'General';
            $this->isInternalComment = false;

            // Refresh bug details
            $this->selectedBug = $this->getBugDetails($this->selectedBug->uuid);
            
            $this->dispatch('notify-success', 'Comment added successfully!');
            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error adding comment: ' . $e->getMessage());
        }
    }

    public function deleteComment($commentUuid): void
    {
        try {
            BugComment::where('uuid', $commentUuid)->delete();
            
            // Refresh bug details
            $this->selectedBug = $this->getBugDetails($this->selectedBug->uuid);
            
            $this->dispatch('notify-success', 'Comment deleted successfully!');
            
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error deleting comment: ' . $e->getMessage());
        }
    }

    private function clearBugCache(): void
    {
        Cache::forget("bugs_team_{$this->team_id}");
        Cache::forget("users_team_{$this->team_id}");
    }


    #[On('refresh-component')]
    public function refreshComponent(): void
    {
        $this->clearBugCache();
    }

    public function render()
    {
        $query = BugMaster::where('team_id', $this->team_id)
        ->where('is_active', true);

        // Apply filters
        if (!empty($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('explanation', 'like', $searchTerm)
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm);
                  });
            });
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        if (!empty($this->filterClientStatus)) {
            $query->where('client_status', $this->filterClientStatus);
        }

        if (!empty($this->filterType)) {
            $query->where('type', $this->filterType);
        }

        if (!empty($this->filterPriority)) {
            $query->where('priority', $this->filterPriority);
        }

        if (!empty($this->filterAssignedTo)) {
            $query->where('assigned_to', $this->filterAssignedTo);
        }

        $bugs = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.bug-tracking-manager', [

            'bugs' => $bugs,
            'teamUsers' => User::get()
        ])->layout('layouts.app');
    }
}