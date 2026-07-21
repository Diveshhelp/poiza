<?php
namespace App\Livewire;

use App\Models\DropboxDeletionLog;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedMyDocuments extends Component
{
    use WithPagination;
    
    public $moduleTitle = DOCUMENT_DELETED_TITLE;
    public $perPage = PER_PAGE;
    public $sortField = 'created_at'; // Using created_at for DropboxDeletionLog
    public $sortDirection = 'desc';
    public $user_id;
    public $team_id;
    public $searchTerm = '';
   
    public function mount()
    {
        $this->user_id = Auth::User()->id;
        $this->team_id = Auth::user()->currentTeam->id;
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }
   
    public function render()
    {
        // Get Dropbox deletion logs
        $query = DropboxDeletionLog::whereNotNull('user_id');
        
        // Add search functionality
        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('item_name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('item_path', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('item_type', 'like', '%' . $this->searchTerm . '%');
            });
        }
        
        // Sort the data
        $query->orderBy($this->sortField, $this->sortDirection);
        
        // Paginate the results
        $dropboxItems = $query->paginate($this->perPage);
        
        return view('livewire.deleted-my-documents', [
            'dropboxItems' => $dropboxItems
        ])->layout('layouts.app');
    }
}