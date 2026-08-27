<?php

namespace App\Livewire;

use App\Models\DioraProduct;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DioraProductManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isModalOpen = false;
    public $isEditMode = false;
    public $productId;

    // Form Fields
    public $product_name, $product_code, $product_alias, $category_id, $category_name;
    public $model_key, $finish, $size, $piece = 1, $packing = 1, $price = 0;
    public $type_of_model, $material;
    
    public $images = [];      // Temporary uploads for new/additional images
    public $existingImages = []; // Images already stored in database
    public $enlargedImage = null;

    protected function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:255|unique:diora_products,product_code,' . $this->productId,
            'finish'       => 'nullable|string|max:100',
            'size'         => 'nullable|string|max:50',
            'material'     => 'nullable|string|max:100',
            'price'        => 'required|numeric|min:0',
            'piece'        => 'required|integer|min:1',
            'packing'      => 'required|integer|min:1',
            'images.*'     => 'image|max:2048', // Max 2MB per image
        ];
        }

    // Add these methods
    public function showImage($url)
    {
        $this->enlargedImage = $url;
    }

    public function closeImageModal()
    {
        $this->enlargedImage = null;
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->productId = null;
        $this->product_name = '';
        $this->product_code = '';
        $this->product_alias = '';
        $this->category_id = null;
        $this->category_name = '';
        $this->model_key = '';
        $this->finish = '';
        $this->size = '';
        $this->piece = 1;
        $this->packing = 1;
        $this->price = 0;
        $this->type_of_model = '';
        $this->material = '';
        $this->images = [];
        $this->existingImages = [];
        $this->isEditMode = false;
    }

    public function store()
    {
        $this->validate();

        $imagePaths = $this->existingImages;

        // Process newly uploaded multiple images
        if ($this->images) {
            foreach ($this->images as $image) {
                $imagePaths[] = $image->store('diora-products', 'public');
            }
        }

        DioraProduct::updateOrCreate(
            ['id' => $this->productId],
            [
                'product_name'  => $this->product_name,
                'product_code'  => $this->product_code,
                'product_alias' => $this->product_alias,
                'category_id'   => $this->category_id,
                'category_name' => $this->category_name,
                'model_key'     => $this->model_key,
                'finish'        => $this->finish,
                'size'          => $this->size,
                'images'        => $imagePaths,
                'piece'         => $this->piece,
                'packing'       => $this->packing,
                'price'         => $this->price,
                'type_of_model' => $this->type_of_model,
                'material'      => $this->material,
            ]
        );

        session()->flash('message', $this->productId ? 'Product updated successfully.' : 'Product created successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $product = DioraProduct::findOrFail($id);
        $this->productId = $product->id;
        $this->product_name = $product->product_name;
        $this->product_code = $product->product_code;
        $this->product_alias = $product->product_alias;
        $this->category_id = $product->category_id;
        $this->category_name = $product->category_name;
        $this->model_key = $product->model_key;
        $this->finish = $product->finish;
        $this->size = $product->size;
        $this->existingImages = $product->images ?? [];
        $this->piece = $product->piece;
        $this->packing = $product->packing;
        $this->price = $product->price;
        $this->type_of_model = $product->type_of_model;
        $this->material = $product->material;
        
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function removeExistingImage($index)
    {
        if (isset($this->existingImages[$index])) {
            Storage::disk('public')->delete($this->existingImages[$index]);
            unset($this->existingImages[$index]);
            $this->existingImages = array_values($this->existingImages);
        }
    }

    public function delete($id)
    {
        $product = DioraProduct::findOrFail($id);
        if ($product->images) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $product->delete();
        session()->flash('message', 'Product deleted successfully.');
    }

    public function render()
    {
        $products = DioraProduct::query()
            ->when($this->search, function($q) {
                $q->where('product_name', 'like', '%' . $this->search . '%')
                  ->orWhere('product_code', 'like', '%' . $this->search . '%')
                  ->orWhere('finish', 'like', '%' . $this->search . '%')
                  ->orWhere('material', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.diora-product-manager', [
            'products' => $products,
        ])->layout('layouts.app');
    }
}