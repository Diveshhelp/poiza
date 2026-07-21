<?php

namespace App\Livewire;

use Auth;
use Livewire\Component;
use App\Models\TypeOfWork;
use Illuminate\Support\Str;

class TypeOfWorkManager extends Component
{
    public $type_of_work_title;
    public $moduleTitle = TYPE_OF_WORK_TITLE;
    public $isUpdate = false;
    public $editUuid;    
    public $commonCreateSuccess; 
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $team_id;
    
    public function mount()
    {
        $this->team_id=Auth::user()->currentTeam->id;
        $this->resetForm();
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);

    }

    public function resetForm()
    {
        $this->type_of_work_title = '';
        $this->isUpdate = false;
        $this->editUuid = null;
    }

    public function saveDataObject()
    {
        $this->validate([
            'type_of_work_title' => 'required|string|max:255'
        ]);

        try {
            TypeOfWork::create([
                'uuid' => Str::uuid(),
                'type_of_work_title' => $this->type_of_work_title,
                'row_status' => 1,
                'created_by' => auth()->id(),
                'team_id'=>$this->team_id
            ]);

            $this->dispatch('notify-success', $this->commonCreateSuccess);
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error creating Type Of Work: ' . $e->getMessage());
        }
    }

    public function editTypeOfWork($uuid)
    {
        $typeOfWork = TypeOfWork::where('uuid', $uuid)->first();
        if ($typeOfWork) {
            $this->type_of_work_title = $typeOfWork->type_of_work_title;
            $this->editUuid = $uuid;
            $this->isUpdate = true;
        } else {
            $this->showNotification('error', 'Type of Work not found.');
        }
    }

    public function updateDataObject()
    {
        $this->validate([
            'type_of_work_title' => 'required|string|max:255'
        ]);

        try {
            $typeOfWork = TypeOfWork::where('uuid', $this->editUuid)->first();
            if ($typeOfWork) {
                $typeOfWork->update([
                    'type_of_work_title' => $this->type_of_work_title
                ]);
                $this->dispatch('notify-success', $this->commonUpdateSuccess);
                $this->resetForm();
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to update Type of Work. ' . $e->getMessage());
        }
    }

    public function deleteTypeOfWork($uuid)
    {
        try {
            $typeOfWork = TypeOfWork::where('uuid', $uuid)->first();
            if ($typeOfWork) {
                $typeOfWork->delete();
                $this->dispatch('notify-success', $this->commonDeleteSuccess);
            } 
            // else {
            //     $this->showNotification('warning', 'Type of Work not found.');
            // }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to delete Type of Work. ' . $e->getMessage());
        }
    }

    public function render()
    {
        $typeOfWorks = TypeOfWork::where("team_id",$this->team_id)->with('createdUser')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('livewire.type-of-work-format.type-of-work-collections', [
            'type_of_works_list' => $typeOfWorks,
        ])->layout('layouts.app');
    }
}