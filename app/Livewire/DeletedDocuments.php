<?php

namespace App\Livewire;

use App\Models\Documents;
use App\Models\DocAttachment;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedDocuments extends Component
{
    use WithPagination;

    public $moduleTitle = DOCUMENT_DELETED_TITLE;
    public $perPage = PER_PAGE;
    public $sortField = 'deleted_at';
    public $sortDirection = 'desc';

    public $confirmingDelete = false;
    public $documentToDelete = null;
    public $securityCode = '';
    public $expectedSecurityCode = ''; 
    protected $listeners = ['documentRestored' => '$refresh'];
    protected $rules = [
        'securityCode' => 'required|string',
    ];
    public $user_id;
    public $selectedDocuments = [];
    public $selectAll = false;
    public $selectedCount = 0;
    public $bulkDeleteModalVisible = false;
    public $team_id;
    public $userRoles;
    public function mount()
    {
        
        $this->user_id = Auth::User()->id;
        $this->team_id=Auth::user()->currentTeam->id;
        $this->userRoles = !empty(Auth::User()->user_role) 
        ? explode(',', Auth::User()->user_role) 
        : [];
    }

    public function confirmDelete($uuid)
    {
        $this->documentToDelete = $uuid;
        $this->confirmingDelete = true;
    }

    public function cancelDelete()
    {
        $this->documentToDelete = null;
        $this->confirmingDelete = false;
        $this->securityCode = '';
        $this->resetErrorBag();
    }
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedDocuments = Documents::onlyTrashed()
                ->where('user_id', Auth::id())
                ->where('team_id', $this->team_id)
                ->pluck('uuid')
                ->toArray();
        } else {
            $this->selectedDocuments = [];
        }
        
        $this->selectedCount = count($this->selectedDocuments);
    }

    public function updatedSelectedDocuments()
    {
        $this->selectAll = false;
        $this->selectedCount = count($this->selectedDocuments);
    }

    public function clearSelection()
    {
        $this->selectedDocuments = [];
        $this->selectAll = false;
        $this->selectedCount = 0;
    }

    public function restore($uuid)
    {
        try {
            $document = Documents::withTrashed()
                ->where('team_id', $this->team_id)
                ->where('uuid', $uuid)
                ->firstOrFail();

            $document->restore();
            
            $this->dispatch('notify-success', COMMON_RESTORE_SUCCESS);
        } catch (\Exception $e) {
            $this->dispatch('notify-error', COMMON_RESTORE_ERROR);
        }
    }

    public function permanentDelete($uuid)
    {
        
        try {

            // Validate the security code
            $this->validate();
            $user = User::find($this->user_id);
            // Check if the security code matches
            if ($this->securityCode !== $user->security_code) {
                return;
            }
            
            $document = Documents::withTrashed()
                ->where('uuid', $uuid)
                ->where('team_id', $this->team_id)
                ->firstOrFail();

            // Delete associated attachments
            $attachments = DocAttachment::where('documents_id', $document->id)->get();
            foreach ($attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment->file_path)) {
                    Storage::disk('public')->delete($attachment->file_path);
                }
                $attachment->forceDelete();
            }

            // Delete document
            $document->forceDelete();


            // Reset confirmation state and security code
            $this->documentToDelete = null;
            $this->confirmingDelete = false;
            $this->securityCode = '';
            
            $this->dispatch('notify-success', COMMON_PERMANENT_DELETE_SUCCESS);
        } catch (\Exception $e) {
            $this->dispatch('notify-error','Invalid security code. Deletion canceled.');
        }
    }

    public function render()
    {
        
        $deletedDocuments = Documents::onlyTrashed()
            ->where('team_id', $this->team_id)
            ->where("created_by", $this->user_id)
            ->with(['owner', 'department', 'doc_categories', 'ownership'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.deleted-documents', [
            'documents' => $deletedDocuments
        ])->layout('layouts.app');
    }
}