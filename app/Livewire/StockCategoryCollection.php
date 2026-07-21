<?php

namespace App\Livewire;

use App\Models\StockCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Str;

class StockCategoryCollection extends Component
{
    use WithPagination;

    public $moduleTitle = STOCK_CATEGORY_TITLE;
    public $perPage = PER_PAGE;

    public $filterStartDate = '';
    public $filterEndDate = '';
    public $searchQuery = '';
    public $filterStatus = '';
    public $activeFiltersCount = 0;
    
    public $category_name;
    public $description;
    public $status = 'active';
    public $uuid;

    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonStatusUpdateSuccess;
    public $commonNotDeleteSuccess;
    
    protected $rules = [
        'category_name' => 'required|string|min:3|max:255',
        'description' => 'nullable|string|max:1000',
        'status' => 'required|in:active,inactive'
    ];

    public function mount()
    {
        $this->resetForm();
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonStatusUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_STATUS_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
    }   

    public function resetForm()
    {
        $this->reset(['category_name', 'description', 'status', 'uuid']);
    }

    public function resetFilters()
    {
        $this->filterStartDate = '';
        $this->filterEndDate = '';
        $this->filterStatus = '';
        $this->searchQuery = '';
        $this->activeFiltersCount = 0;
    }

    public function countActiveFilters()
    {
        $count = 0;
        if ($this->filterStartDate) $count++;
        if ($this->filterEndDate) $count++;
        if ($this->filterStatus) $count++;
        if ($this->searchQuery) $count++;
        $this->activeFiltersCount = $count;
    }

    public function saveCategory()
    {
        $this->validate();

        $category = new StockCategory();
        $category->uuid = Str::uuid();
        $category->category_name = $this->category_name;
        $category->description = $this->description;
        $category->status = $this->status;
        $category->team_id = Auth::user()->currentTeam->id;
        $category->user_id = Auth::id();
        $category->created_by = Auth::id();
        
        $category->save();

        $this->resetForm();
        $this->dispatch('notify-success', $this->commonCreateSuccess);
    }

    public function editCategory($uuid)
    {
        $category = StockCategory::where('uuid', $uuid)->first();
        if ($category) {
            $this->uuid = $category->uuid;
            $this->category_name = $category->category_name;
            $this->description = $category->description;
            $this->status = $category->status;
        }
    }

    public function updateCategory()
    {
        $this->validate();

        $category = StockCategory::where('uuid', $this->uuid)->first();
        if ($category) {
            $category->category_name = $this->category_name;
            $category->description = $this->description;
            $category->status = $this->status;
            $category->save();

            $this->resetForm();
            $this->dispatch('notify-success', $this->commonUpdateSuccess);
        }
    }

    public function deleteCategory($uuid)
    {
        $category = StockCategory::where('uuid', $uuid)->first();
        if ($category) {
            $category->delete();
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        }
    }

    public function render()
    {
        $query = StockCategory::query()
            ->where('team_id', Auth::user()->currentTeam->id);

        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('category_name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterStartDate) {
        $query->whereDate('created_at', '>=', $this->filterStartDate);
        }

        if ($this->filterEndDate) {
            $query->whereDate('created_at', '<=', $this->filterEndDate);
        }

        $this->countActiveFilters();

        $categories = $query->latest()->paginate($this->perPage);

        return view('livewire.stock-management.stock-category-collection', [
            'categories' => $categories
        ])->layout('layouts.app');
    }
}