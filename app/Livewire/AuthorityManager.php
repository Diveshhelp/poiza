<?php

namespace App\Livewire;

use App\Models\Authority;
use App\Models\User;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AuthorityManager extends Component
{
    use WithPagination;

    public $title;
    public $search = '';
    public $serverSearch = ''; // For server-side search when needed
    public $moduleTitle = AUTHORITY_TITLE;
    public $isUpdate = false;
    public $editUuid;    
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $team_id;
    public $user_id;
    public $allRecords = []; // Store all records for client-side filtering
    public $enableServerSearch = true; // Toggle between client/server search
    protected $queryString = [
        'serverSearch' => ['except' => ''],
    ];
    public $allUsers;
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'performServerSearch' => 'performServerSearch',
        'toggleSearchMode' => 'toggleSearchMode'
    ];

    public function mount()
    {
        $this->team_id = Auth::user()->currentTeam->id;
        $this->user_id = Auth::user()->id;
        $this->resetForm();
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        
        // Load all records for client-side filtering
        $this->loadAllRecords();
        $this->allUsers=User::where("current_team_id",$this->team_id)->get();
    }

    public function loadAllRecords()
    {
        $this->allRecords = Authority::where("team_id", $this->team_id)
            ->with('createdUser')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function updatingServerSearch()
    {
        $this->resetPage();
    }

    public function updatedServerSearch()
    {
        $this->resetPage();
    }

    public function performServerSearch($searchTerm)
    {
        $this->serverSearch = $searchTerm;
        $this->enableServerSearch = true;
        $this->resetPage();
    }

    public function toggleSearchMode()
    {
        $this->enableServerSearch = !$this->enableServerSearch;
        $this->search = '';
        $this->serverSearch = '';
        $this->resetPage();
        
        if (!$this->enableServerSearch) {
            $this->loadAllRecords();
        }
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->serverSearch = '';
        $this->resetPage();
        $this->dispatch('search-cleared');
    }

    public function resetForm()
    {
        $this->title = '';
        $this->isUpdate = false;
        $this->editUuid = null;
    }

    public function saveDataObject()
    {
        $this->validate([
            'user_id' => 'required|string|max:512|unique:authority,user_id,NULL,id,team_id,' . $this->team_id
        ], [
            'user_id.unique' => 'This authority already exists in your team.',
        ]);

        try {
            $newRecord = new Authority();
            $newRecord->uuid= (string) \Illuminate\Support\Str::uuid();
            $newRecord->status = "1";
            $newRecord->created_by = auth()->id();
            $newRecord->team_id = $this->team_id;
            $newRecord->user_id = $this->user_id;
            $newRecord->save();

            // Add to allRecords for immediate display without re-render
            $newRecord->load('createdUser');
            array_unshift($this->allRecords, $newRecord->toArray());

            $this->dispatch('notify-success', $this->commonCreateSuccess);
            $this->dispatch('record-added', $newRecord->toArray());
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error creating ' . $this->moduleTitle . ': ' . $e->getMessage());
        }
    }


  

    public function deleteAuthority($uuid)
    {
        try {
            $Authority = Authority::where('uuid', $uuid)
                ->where('team_id', $this->team_id)
                ->first();

            if ($Authority) {
                $Authority->delete();

                // Remove from allRecords array
                $this->allRecords = collect($this->allRecords)
                    ->reject(function ($item) use ($uuid) {
                        return $item['uuid'] === $uuid;
                    })
                    ->values()
                    ->toArray();

                $this->dispatch('notify-success', $this->commonDeleteSuccess);
                $this->dispatch('record-deleted', $uuid);
            } else {
                $this->dispatch('notify-error', $this->moduleTitle . ' not found.');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to delete ' . $this->moduleTitle . '. ' . $e->getMessage());
        }
    }

    public function toggleStatus($uuid)
    {
        try {
            $Authority = Authority::where('uuid', $uuid)
                ->where('team_id', $this->team_id)
                ->first();

            if ($Authority) {
                $newStatus = $Authority->status == "1" ? "0" :"1";
                $Authority->update([
                    'status' => $newStatus,
                    'updated_by' => auth()->id()
                ]);

                // Update in allRecords array
                $index = collect($this->allRecords)->search(function ($item) use ($uuid) {
                    return $item['uuid'] === $uuid;
                });

                if ($index !== false) {
                    $this->allRecords[$index]['status'] = $newStatus;
                }

                $statusText = $newStatus == 1 ? 'activated' : 'deactivated';
                $this->dispatch('notify-success', $this->moduleTitle . ' ' . $statusText . ' successfully.');
                $this->dispatch('status-updated', ['uuid' => $uuid, 'status' => $newStatus]);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to update status. ' . $e->getMessage());
        }
    }

    public function refreshData()
    {
        $this->loadAllRecords();
        $this->dispatch('data-refreshed');
    }

    public function getFilteredRecordsProperty()
    {
        if (empty($this->serverSearch)) {
            return collect($this->allRecords);
        }

        return collect($this->allRecords)->filter(function ($record) {
            $searchTerm = strtolower($this->serverSearch);
            return str_contains(strtolower($record['title']), $searchTerm) ||
                   (isset($record['created_user']['name']) && str_contains(strtolower($record['created_user']['name']), $searchTerm));
        });
    }

    public function render()
    {
        if ($this->enableServerSearch || !empty($this->serverSearch)) {
            $query = Authority::where("team_id", $this->team_id)
            ->with('createdUser');
             $searchTerm = trim($this->serverSearch);
            $searchTermLike = '%' . $searchTerm . '%';
            
            $query->where(function ($q) use ($searchTerm, $searchTermLike) {
                $q->Where('created_by', $searchTerm)
                  // Search in related User table
                  ->orWhereHas('authorityUser', function ($userQuery) use ($searchTerm, $searchTermLike) {
                      $userQuery->where('name', 'like', $searchTermLike)
                               ->orWhere('email', 'like', $searchTermLike)
                               ->orWhere('id', $searchTerm);
                  });
            });

            $dataObject = $query->orderBy('created_at', 'desc')
                ->paginate(PER_PAGE);
        } else {
            // Client-side filtering - return empty paginator as we'll handle filtering in Alpine.js
            $dataObject = new \Illuminate\Pagination\LengthAwarePaginator(
                collect($this->allRecords),
                count($this->allRecords),
                PER_PAGE,
                1,
                ['path' => request()->url()]
            );
        }

        return view('livewire.authority.data-collections', [
            'data_list' => $dataObject,
            'all_records' => $this->allRecords,
            'enable_server_search' => $this->enableServerSearch
        ])->layout('layouts.app');
    }
}