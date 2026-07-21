<?php

namespace App\Livewire;

use App\Models\SubDepartments;
use Auth;
use Livewire\Component;
use App\Models\Department;
use Illuminate\Support\Str;

class DepartmentsManager extends Component
{
    public $department_name;
    public $sub_department_name;
    public $parent_department_uuid;
    public $moduleTitle = DEPARTMENT_TITLE;
    public $isUpdate = false;
    public $isSubDepartment = false;
    public $editUuid;
    public $editSubDepartmentUuid;
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $description;

    public $parent_department_id;
    public $team_id;
    protected $rules = [
        'department_name' => 'required|string|max:255',
        'sub_department_name' => 'required_if:isSubDepartment,true|string|max:255',
        'parent_department_uuid' => 'required_if:isSubDepartment,true|string|exists:departments,uuid',
        'description' => 'nullable|string|max:500'
    ];

    public function mount()
    {
        $this->team_id = Auth::user()->currentTeam->id;
        $this->resetForm();
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
    }

    public function resetForm()
    {
        $this->department_name = '';
        $this->sub_department_name = '';
        $this->parent_department_uuid = '';
        $this->description = '';
        $this->isUpdate = false;
        $this->isSubDepartment = false;
        $this->editUuid = null;
        $this->editSubDepartmentUuid = null;
    }

    public function toggleSubDepartment()
    {
        $this->isSubDepartment = !$this->isSubDepartment;
        $this->resetForm();
    }

    public function saveDataObject()
    {
        if ($this->isSubDepartment) {
            $this->validate([
                'parent_department_uuid' => 'required|string|exists:departments,uuid',
                'sub_department_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500'
            ]);

            try {
                SubDepartments::create([
                    'uuid' => Str::uuid(),
                    'department_uuid' => $this->parent_department_uuid,
                    'sub_department_name' => $this->sub_department_name,
                    'description' => $this->description,
                    'row_status' => 1,
                    'created_by' => auth()->id(),
                    'team_id'=>$this->team_id
                ]);

                $this->dispatch('notify-success', 'Sub-department created successfully');
                $this->resetForm();
            } catch (\Exception $e) {
                $this->dispatch('notify-error', 'Error creating sub-department: ' . $e->getMessage());
            }
        } else {
            $this->validate([
                'department_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500'
            ]);

            try {
                Department::create([
                    'uuid' => Str::uuid(),
                    'department_name' => $this->department_name,
                    'description' => $this->description,
                    'row_status' => 1,
                    'created_by' => auth()->id(),
                    'team_id'=>$this->team_id
                ]);

                $this->dispatch('notify-success', $this->commonCreateSuccess);
                $this->resetForm();
            } catch (\Exception $e) {
                $this->dispatch('notify-error', 'Error creating department: ' . $e->getMessage());
            }
        }
    }

    public function editDepartment($uuid)
    {
        $department = Department::where('uuid', $uuid)->first();
        if ($department) {
            $this->department_name = $department->department_name;
            $this->description = $department->description;
            $this->editUuid = $uuid;
            $this->isUpdate = true;
            $this->isSubDepartment = false;
        } else {
            $this->dispatch('notify-error', 'Department not found.');
        }
    }

    public function editSubDepartment($uuid)
    {
        $subDepartment = SubDepartments::where('uuid', $uuid)->first();
        if ($subDepartment) {
            $this->sub_department_name = $subDepartment->sub_department_name;
            $this->parent_department_uuid = $subDepartment->department_uuid;
            $this->description = $subDepartment->description;
            $this->editSubDepartmentUuid = $uuid;
            $this->isUpdate = true;
            $this->isSubDepartment = true;
        } else {
            $this->dispatch('notify-error', 'Sub-department not found.');
        }
    }

    public function updateDataObject()
    {
        if ($this->isSubDepartment) {
            $this->validate([
                'sub_department_name' => 'required|string|max:255',
                'parent_department_uuid' => 'required|string|exists:departments,uuid',
                'description' => 'nullable|string|max:500'
            ]);

            try {
                $subDepartment = SubDepartments::where('uuid', $this->editSubDepartmentUuid)->first();
                if ($subDepartment) {
                    $subDepartment->update([
                        'department_uuid' => $this->parent_department_uuid,
                        'sub_department_name' => $this->sub_department_name,
                        'description' => $this->description
                    ]);
                    $this->dispatch('notify-success', 'Sub-department updated successfully');
                    $this->resetForm();
                }
            } catch (\Exception $e) {
                $this->dispatch('notify-error', 'Failed to update sub-department: ' . $e->getMessage());
            }
        } else {
            $this->validate([
                'department_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500'
            ]);

            try {
                $department = Department::where('uuid', $this->editUuid)->first();
                if ($department) {
                    $department->update([
                        'department_name' => $this->department_name,
                        'description' => $this->description
                    ]);
                    $this->dispatch('notify-success', $this->commonUpdateSuccess);
                    $this->resetForm();
                }
            } catch (\Exception $e) {
                $this->dispatch('notify-error', 'Failed to update department: ' . $e->getMessage());
            }
        }
    }

    public function deleteDepartment($uuid)
    {
        try {
            $department = Department::where('uuid', $uuid)->first();
            if ($department) {
                // Delete associated sub-departments first
                SubDepartments::where('department_id', $department->id)->delete();
                $department->delete();
                $this->dispatch('notify-success', $this->commonDeleteSuccess);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to delete department: ' . $e->getMessage());
        }
    }

    public function deleteSubDepartment($id)
    {
        try {
            $subDepartment = SubDepartments::where('id', $id)->first();
            if ($subDepartment) {
                $subDepartment->delete();
                $this->dispatch('notify-success', 'Sub-department deleted successfully');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to delete sub-department: ' . $e->getMessage());
        }
    }

    public function saveSubDepartment()
    {
        $this->validate([
            'parent_department_id' => 'required|string|exists:departments,id',
            'sub_department_name' => 'required|string|max:255'
        ]);

        try {
            SubDepartments::create([
                'department_id' => $this->parent_department_id,
                'name' => $this->sub_department_name,
                'is_active' => 1,
                'created_by' => auth()->id(),
                'team_id'=>$this->team_id
            ]);

            $this->dispatch('notify-success', 'Sub-department created successfully');
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error creating sub-department: ' . $e->getMessage());
        }
    }
    public function createSubDepartment($parentUuid, $formData)
    {
        try {
            $parentDepartment = Department::where('uuid', $parentUuid)->firstOrFail();
            
            $subDepartment = new SubDepartments([
                'name' => $formData['name'],
                'row_status' => 1,
                'department_id' => $parentDepartment->id,
                'created_by' => auth()->id(),
                'team_id'=>$this->team_id
            ]);


            $subDepartment->save();

            $this->dispatch('notify-success', 'Sub-department created successfully');


            return true;
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error creating sub-department: ' . $e->getMessage());
            return false;
        }
    }

    public function render()
    {
        $departments = Department::where("team_id",$this->team_id)->with(['createdUser', 'subDepartments.createdUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(26);

        return view('livewire.departments-format.departments-collections', [
            'departments' => $departments,
        ])->layout('layouts.app');
    }
}