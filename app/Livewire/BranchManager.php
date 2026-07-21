<?php

namespace App\Livewire;

use App\Models\Branches;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class BranchManager extends Component
{
    use WithPagination;

    public $title;
    public $search = '';
    public $serverSearch = ''; // For server-side search when needed
    public $moduleTitle = BRANCH_TITLE;
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
    }

    public function loadAllRecords()
    {
        $this->allRecords = Branches::where("team_id", $this->team_id)
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
            'title' => 'required|string|max:512|unique:branch,title,NULL,id,team_id,' . $this->team_id
        ], [
            'title.unique' => 'This branch already exists in your team.',
        ]);

        try {
            $newRecord = new Branches();
            $newRecord->uuid= (string) \Illuminate\Support\Str::uuid();
            $newRecord->title= trim($this->title);
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

    public function editBranches($uuid)
    {
        $Branches = Branches::where('uuid', $uuid)
            ->where('team_id', $this->team_id)
            ->first();

        if ($Branches) {
            $this->title = $Branches->title;
            $this->editUuid = $uuid;
            $this->isUpdate = true;
        } else {
            $this->dispatch('notify-error', $this->moduleTitle . ' not found.');
        }
    }

    public function updateDataObject()
    {
        $this->validate([
            'title' => 'required|string|max:512|unique:branch,title,NULL,id,team_id,' . $this->team_id . ',uuid,' . $this->editUuid
        ], [
            'title.unique' => 'This branch already exists in your team.',
        ]);

        try {
            $Branches = Branches::where('uuid', $this->editUuid)
                ->where('team_id', $this->team_id)
                ->first();

            if ($Branches) {
                $Branches->update([
                    'title' => trim($this->title),
                    'updated_by' => auth()->id()
                ]);

                // Update in allRecords array
                $index = collect($this->allRecords)->search(function ($item) {
                    return $item['uuid'] === $this->editUuid;
                });

                if ($index !== false) {
                    $this->allRecords[$index]['title'] = trim($this->title);
                    $this->allRecords[$index]['updated_at'] = $Branches->updated_at->toISOString();
                }

                $this->dispatch('notify-success', $this->commonUpdateSuccess);
                $this->dispatch('record-updated', ['uuid' => $this->editUuid, 'title' => trim($this->title)]);
                $this->resetForm();
            } else {
                $this->dispatch('notify-error', $this->moduleTitle . ' not found.');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to update ' . $this->moduleTitle . '. ' . $e->getMessage());
        }
    }

    public function deleteBranches($uuid)
    {
        try {
            $Branches = Branches::where('uuid', $uuid)
                ->where('team_id', $this->team_id)
                ->first();

            if ($Branches) {
                $Branches->delete();

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
            $Branches = Branches::where('uuid', $uuid)
                ->where('team_id', $this->team_id)
                ->first();

            if ($Branches) {
                $newStatus = $Branches->status == "1" ? "0" :"1";
                $Branches->update([
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
            // Server-side search for large datasets
            $query = Branches::where("team_id", $this->team_id)
                ->with('createdUser');

            if (!empty($this->serverSearch)) {
                $searchTerm = '%' . trim($this->serverSearch) . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', $searchTerm)
                      ->orWhereHas('createdUser', function ($userQuery) use ($searchTerm) {
                          $userQuery->where('name', 'like', $searchTerm);
                      });
                });
            }

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

        return view('livewire.branch.data-collections', [
            'data_list' => $dataObject,
            'all_records' => $this->allRecords,
            'enable_server_search' => $this->enableServerSearch
        ])->layout('layouts.app');
    }
}