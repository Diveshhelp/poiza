<?php

namespace App\Livewire;

use App\Models\NatureOfWork;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NatureOfWorkManager extends Component
{
    use WithPagination;

    public $title;
    public $search = '';
    public $serverSearch = '';
    public $moduleTitle = NATURE_OF_WORK_TITLE;
    public $isUpdate = false;
    public $editUuid;    
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $team_id;
    public $allRecords = [];
    public $enableServerSearch = true;

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
        $this->resetForm();
        $this->setupMessages();
        
        // Only load records if client-side search is enabled
        if (!$this->enableServerSearch) {
            $this->loadAllRecords();
        }
    }

    private function setupMessages()
    {
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
    }

    public function loadAllRecords()
    {
        $cacheKey = "nature_of_work_team_{$this->team_id}";
        
        $this->allRecords = Cache::remember($cacheKey, 300, function () {
            return NatureOfWork::select(['uuid', 'title', 'status', 'created_at', 'created_by'])
                ->where('team_id', $this->team_id)
                ->with(['createdUser:id,name'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
        });
    }

    public function updatingServerSearch()
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
            'title' => [
                'required',
                'string',
                'max:512',
                function ($attribute, $value, $fail) {
                    if (NatureOfWork::where('title', trim($value))->where('team_id', $this->team_id)->exists()) {
                        $fail('This nature of work already exists in your team.');
                    }
                }
            ]
        ]);

        try {
            DB::beginTransaction();
            
            $newRecord = NatureOfWork::create([
                'uuid' => Str::uuid()->toString(),
                'title' => trim($this->title),
                'status' => "1",
                'created_by' => auth()->id(),
                'team_id' => $this->team_id
            ]);

            // Clear cache to force refresh
            Cache::forget("nature_of_work_team_{$this->team_id}");

            // Add to allRecords for immediate display
            if (!$this->enableServerSearch) {
                $newRecord->load('createdUser:id,name');
                array_unshift($this->allRecords, $newRecord->toArray());
            }

            DB::commit();

            $this->dispatch('notify-success', $this->commonCreateSuccess);
            $this->dispatch('record-added', $newRecord->toArray());
            $this->resetForm();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify-error', 'Error creating ' . $this->moduleTitle . ': ' . $e->getMessage());
        }
    }

    public function editNatureOfWork($uuid)
    {
        $natureOfWork = NatureOfWork::select(['uuid', 'title'])
            ->where('uuid', $uuid)
            ->where('team_id', $this->team_id)
            ->first();

        if ($natureOfWork) {
            $this->title = $natureOfWork->title;
            $this->editUuid = $uuid;
            $this->isUpdate = true;
        } else {
            $this->dispatch('notify-error', $this->moduleTitle . ' not found.');
        }
    }

    public function updateDataObject()
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:512',
                function ($attribute, $value, $fail) {
                    if (NatureOfWork::where('title', trim($value))
                        ->where('team_id', $this->team_id)
                        ->where('uuid', '!=', $this->editUuid)
                        ->exists()) {
                        $fail('This nature of work already exists in your team.');
                    }
                }
            ]
        ]);

        try {
            DB::beginTransaction();
            
            $updated = NatureOfWork::where('uuid', $this->editUuid)
                ->where('team_id', $this->team_id)
                ->update([
                    'title' => trim($this->title),
                    'updated_at' => now()
                ]);

            if ($updated) {
                // Clear cache
                Cache::forget("nature_of_work_team_{$this->team_id}");

                // Update allRecords if using client-side search
                if (!$this->enableServerSearch) {
                    $index = array_search($this->editUuid, array_column($this->allRecords, 'uuid'));
                    if ($index !== false) {
                        $this->allRecords[$index]['title'] = trim($this->title);
                        $this->allRecords[$index]['updated_at'] = now()->toISOString();
                    }
                }

                DB::commit();

                $this->dispatch('notify-success', $this->commonUpdateSuccess);
                $this->dispatch('record-updated', ['uuid' => $this->editUuid, 'title' => trim($this->title)]);
                $this->resetForm();
            } else {
                DB::rollBack();
                $this->dispatch('notify-error', $this->moduleTitle . ' not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify-error', 'Failed to update ' . $this->moduleTitle . '. ' . $e->getMessage());
        }
    }

    public function deleteNatureOfWork($uuid)
    {
        try {
            DB::beginTransaction();
            
            $deleted = NatureOfWork::where('uuid', $uuid)
                ->where('team_id', $this->team_id)
                ->delete();

            if ($deleted) {
                // Clear cache
                Cache::forget("nature_of_work_team_{$this->team_id}");

                // Remove from allRecords
                if (!$this->enableServerSearch) {
                    $this->allRecords = array_values(
                        array_filter($this->allRecords, fn($item) => $item['uuid'] !== $uuid)
                    );
                }

                DB::commit();

                $this->dispatch('notify-success', $this->commonDeleteSuccess);
                $this->dispatch('record-deleted', $uuid);
            } else {
                DB::rollBack();
                $this->dispatch('notify-error', $this->moduleTitle . ' not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify-error', 'Failed to delete ' . $this->moduleTitle . '. ' . $e->getMessage());
        }
    }

    public function toggleStatus($uuid)
    {
        try {
            DB::beginTransaction();
            
            $natureOfWork = NatureOfWork::select(['uuid', 'status'])
                ->where('uuid', $uuid)
                ->where('team_id', $this->team_id)
                ->first();

            if ($natureOfWork) {
                $newStatus = $natureOfWork->status == "1" ? "0" : "1";
                
                $natureOfWork->update([
                    'status' => $newStatus,
                    'updated_by' => auth()->id()
                ]);

                // Clear cache
                Cache::forget("nature_of_work_team_{$this->team_id}");

                // Update allRecords
                if (!$this->enableServerSearch) {
                    $index = array_search($uuid, array_column($this->allRecords, 'uuid'));
                    if ($index !== false) {
                        $this->allRecords[$index]['status'] = $newStatus;
                    }
                }

                DB::commit();

                $statusText = $newStatus == 1 ? 'activated' : 'deactivated';
                $this->dispatch('notify-success', $this->moduleTitle . ' ' . $statusText . ' successfully.');
                $this->dispatch('status-updated', ['uuid' => $uuid, 'status' => $newStatus]);
            } else {
                DB::rollBack();
                $this->dispatch('notify-error', $this->moduleTitle . ' not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify-error', 'Failed to update status. ' . $e->getMessage());
        }
    }

    public function refreshData()
    {
        Cache::forget("nature_of_work_team_{$this->team_id}");
        $this->loadAllRecords();
        $this->dispatch('data-refreshed');
    }

    public function getFilteredRecordsProperty()
    {
        if (empty($this->serverSearch) || $this->enableServerSearch) {
            return collect($this->allRecords);
        }

        $searchTerm = strtolower($this->serverSearch);
        
        return collect($this->allRecords)->filter(function ($record) use ($searchTerm) {
            return str_contains(strtolower($record['title']), $searchTerm) ||
                   (isset($record['created_user']['name']) && str_contains(strtolower($record['created_user']['name']), $searchTerm));
        });
    }

    public function render()
    {
        if ($this->enableServerSearch) {
            // Optimized server-side search
            $query = NatureOfWork::select(['uuid', 'title', 'status', 'created_at', 'created_by'])
                ->where('team_id', $this->team_id)
                ->with(['createdUser:id,name']);

            if (!empty($this->serverSearch)) {
                $searchTerm = '%' . trim($this->serverSearch) . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', $searchTerm)
                      ->orWhereHas('createdUser', function ($userQuery) use ($searchTerm) {
                          $userQuery->where('name', 'like', $searchTerm);
                      });
                });
            }

            $natureOfWorks = $query->orderBy('created_at', 'desc')
                ->paginate(PER_PAGE);
        } else {
            // Client-side filtering with efficient pagination
            $filteredRecords = $this->getFilteredRecordsProperty();
            $natureOfWorks = new \Illuminate\Pagination\LengthAwarePaginator(
                $filteredRecords->forPage(request('page', 1), PER_PAGE),
                $filteredRecords->count(),
                PER_PAGE,
                request('page', 1),
                ['path' => request()->url(), 'pageName' => 'page']
            );
        }

        return view('livewire.nature-of-work.data-collections', [
            'type_of_works_list' => $natureOfWorks,
            'all_records' => $this->allRecords,
            'enable_server_search' => $this->enableServerSearch
        ])->layout('layouts.app');
    }
}