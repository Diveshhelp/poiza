<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\DocCategory;
use Illuminate\Support\Str;

class DocCategoryManager extends Component
{
    public $category_title;
    public $moduleTitle = DOC_CATEGORY_TITLE;
    public $isUpdate = false;
    public $editUuid;    
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $team_id;
    
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
        $this->category_title = '';
        $this->isUpdate = false;
        $this->editUuid = null;
    }

    public function saveDataObject()
    {
        $this->validate([
            'category_title' => 'required|string|max:255'
        ]);

        try {
            DocCategory::create([
                'uuid' => Str::uuid(),
                'category_title' => $this->category_title,
                'row_status' => 1,
                'created_by' => auth()->id(),
                'team_id'=>$this->team_id
            ]);

            $this->dispatch('notify-success', $this->commonCreateSuccess);
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Error creating Document Category: ' . $e->getMessage());
        }
    }

    public function editCategory($uuid)
    {
        $category = DocCategory::where('uuid', $uuid)->first();
        if ($category) {
            $this->category_title = $category->category_title;
            $this->editUuid = $uuid;
            $this->isUpdate = true;
        }
    }

    public function updateDataObject()
    {
        $this->validate([
            'category_title' => 'required|string|max:255'
        ]);

        try {
            $category = DocCategory::where('uuid', $this->editUuid)->first();
            if ($category) {
                $category->update([
                    'category_title' => $this->category_title
                ]);
                $this->dispatch('notify-success', $this->commonUpdateSuccess);
                $this->resetForm();
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to update Document Category. ' . $e->getMessage());
        }
    }

    public function deleteCategory($uuid)
    {
        try {
            $category = DocCategory::where('uuid', $uuid)->first();
            if ($category) {
                $category->delete();
                $this->dispatch('notify-success', $this->commonDeleteSuccess);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify-error', 'Failed to delete Document Category. ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categories = DocCategory::where("team_id",$this->team_id)->with('createdUser')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('livewire.doc-category.doc-category-collections', [
            'categories_list' => $categories,
        ])->layout('layouts.app');
    }
}