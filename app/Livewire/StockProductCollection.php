<?php

namespace App\Livewire;

use App\Models\StockCategory;
use App\Models\StockProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Str;

class StockProductCollection extends Component
{
    use WithFileUploads, WithPagination;

    public $moduleTitle = STOCK_PRODUCT_TITLE;

    public $perPage = PER_PAGE;

    // Form fields
    public $uuid;

    public $product_name;

    public $category_id;

    public $description;

    public $price;

    public $quantity;

    public $sku;

    public $status = 'active';

    public $images = [];

    public $existingImages = [];

    public $currentProduct = null;

    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonStatusUpdateSuccess;
    public $commonNotDeleteSuccess;

    // Filters
    public $searchQuery = '';

    public $filterStatus = '';

    public $filterCategory = '';

    public $filterStartDate = '';

    public $filterEndDate = '';

    public $activeFiltersCount = 0;

    protected $rules = [
        'product_name' => 'required|string|min:3|max:255',
        'category_id' => 'required|exists:stock_categories,id',
        'description' => 'nullable|string|max:1000',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'sku' => 'nullable|string|max:15|unique:stock_products,sku',
        'status' => 'required|in:active,inactive',
        'images.*' => 'nullable|image',
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
        $this->reset([
            'uuid', 'product_name', 'category_id', 'description',
            'price', 'quantity', 'sku', 'status', 'images',
            'existingImages',
        ]);
        // $this->isFormOpen = false;
    }

    // public function toggleForm()
    // {
    //     $this->isFormOpen = ! $this->isFormOpen;
    //     if (! $this->isFormOpen) {
    //         $this->resetForm();
    //     }
    // }

    public function resetFilters()
    {
        $this->reset([
            'searchQuery', 'filterStatus', 'filterCategory',
            'filterStartDate', 'filterEndDate', 'activeFiltersCount',
        ]);
    }

    public function saveProduct()
    {
        $this->validate();

        $product = new StockProduct;
        $product->uuid = Str::uuid();
        $product->product_name = $this->product_name;
        $product->category_id = $this->category_id;
        $product->description = $this->description;
        $product->price = $this->price;
        $product->quantity = $this->quantity;
        $product->sku = $this->sku;
        $product->status = $this->status;
        $product->team_id = Auth::user()->currentTeam->id;
        $product->user_id = Auth::id();
        $product->created_by = Auth::id();

        // Handle image uploads
        if (! empty($this->images)) {
            $uploadedImages = [];
            foreach ($this->images as $image) {
                $originalName = $image->getClientOriginalName();
                // Generate unique filename for storage while keeping original name
                $uniqueName = uniqid().'_'.$originalName;
                $image->storeAs('product-images', $uniqueName, 'public');
                // Store only original filename in database
                $uploadedImages[] = $originalName;
            }
            $product->images = $uploadedImages;
        }

        $product->save();

        $this->resetForm();
        $this->dispatch('notify-success', $this->commonCreateSuccess);
    }

    public function editProduct($uuid)
    {
        $product = StockProduct::where('uuid', $uuid)->first();
        if ($product) {
            $this->currentProduct = $product;
            $this->uuid = $product->uuid;
            $this->product_name = $product->product_name;
            $this->category_id = $product->category_id;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->quantity = $product->quantity;
            $this->sku = $product->sku;
            $this->status = $product->status;
            $this->existingImages = $product->images ?? [];
            // $this->isFormOpen = true;
        }
    }

    public function updateProduct()
    {
        $this->validate([
            'sku' => 'nullable|string|max:50|unique:stock_products,sku,'.StockProduct::where('uuid', $this->uuid)->first()->id,
        ]);

        $product = StockProduct::where('uuid', $this->uuid)->first();
        if ($product) {
            $product->product_name = $this->product_name;
            $product->category_id = $this->category_id;
            $product->description = $this->description;
            $product->price = $this->price;
            $product->quantity = $this->quantity;
            $product->sku = $this->sku;
            $product->status = $this->status;

            // Handle new image uploads
            if (! empty($this->images)) {
                $uploadedImages = $product->images ?? [];
                foreach ($this->images as $image) {
                    $originalName = $image->getClientOriginalName();
                    $uniqueName = uniqid().'_'.$originalName;
                    $image->storeAs('product-images', $uniqueName, 'public');
                    $uploadedImages[] = $originalName;
                }
                $product->images = $uploadedImages;
            }

            $product->save();

            $this->resetForm();
            $this->dispatch('notify-success', $this->commonUpdateSuccess);
        }
    }

    public function deleteProduct($uuid)
    {
        $product = StockProduct::where('uuid', $uuid)->first();
        if ($product) {
            // Delete associated images
            if (! empty($product->images)) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $product->delete();
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        }
    }

    public function removeImage($index)
    {
        $product = StockProduct::where('uuid', $this->uuid)->first();
        if ($product && isset($product->images[$index])) {
            $images = $product->images;
            Storage::disk('public')->delete($images[$index]);
            unset($images[$index]);
            $product->images = array_values($images);
            $product->save();
            $this->existingImages = $product->images;
            $this->dispatch('notify-success', 'Image removed successfully');
        }
    }

    public function countActiveFilters()
    {
        $count = 0;
        if ($this->searchQuery) {
            $count++;
        }
        if ($this->filterStatus) {
            $count++;
        }
        if ($this->filterCategory) {
            $count++;
        }
        if ($this->filterStartDate) {
            $count++;
        }
        if ($this->filterEndDate) {
            $count++;
        }
        $this->activeFiltersCount = $count;
    }

    public function getImageUrl($filename)
    {
        $storagePath = Storage::disk('public')->path('product-images');
        $files = glob($storagePath.'/*_'.$filename);
        if (! empty($files)) {
            $relativePath = str_replace(Storage::disk('public')->path(''), '', $files[0]);

            return Storage::url($relativePath);
        }

        return asset('images/placeholder.png');
    }

    public function render()
    {
        $query = StockProduct::with('category')
            ->where('team_id', Auth::user()->currentTeam->id);

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('product_name', 'like', '%'.$this->searchQuery.'%')
                    ->orWhere('description', 'like', '%'.$this->searchQuery.'%')
                    ->orWhere('sku', 'like', '%'.$this->searchQuery.'%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }

        if ($this->filterStartDate) {
            $query->whereDate('created_at', '>=', $this->filterStartDate);
        }

        if ($this->filterEndDate) {
            $query->whereDate('created_at', '<=', $this->filterEndDate);
        }

        $this->countActiveFilters();

        $categories = StockCategory::where('status', 'active')
            ->where('team_id', Auth::user()->currentTeam->id)
            ->get();

        $products = $query->latest()->paginate($this->perPage);

        return view('livewire.stock-management.stock-product-collection', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}