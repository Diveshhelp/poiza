<?php 
namespace App\Livewire;

use App\Models\DioraProduct;
use App\Models\DioraStock;
use Livewire\Component;
use Livewire\WithPagination;

class DioraStockManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    
    // Lightbox image preview state
    public $enlargedImage = null;

    // Gallery Modal state
    public $selectedProductImages = [];
    public $isGalleryModalOpen = false;

    // Stock History Modal state
    public $isHistoryModalOpen = false;
    public $historyProduct = null;
    public $productStockHistory = [];

    // Form fields for adding stock
    public $diora_product_id;
    public $quantity = 1;
    public $type = 'addition'; // addition, deduction, opening
    public $reference_no;
    public $notes;

    protected function rules()
    {
        return [
            'diora_product_id' => 'required|exists:diora_products,id',
            'quantity'         => 'required|integer|min:1',
            'type'             => 'required|in:addition,deduction,opening',
            'reference_no'     => 'nullable|string|max:100',
            'notes'            => 'nullable|string',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($productId = null)
    {
        $this->resetForm();
        if ($productId) {
            $this->diora_product_id = $productId;
        }
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    // Methods to handle the image lightbox popup
    public function showImage($url)
    {
        $this->enlargedImage = $url;
    }

    public function closeImageModal()
    {
        $this->enlargedImage = null;
    }

    // Gallery Methods
    public function showGallery($imagesJson)
    {
        $images = is_array($imagesJson) ? $imagesJson : json_decode($imagesJson, true);
        $this->selectedProductImages = $images ? array_map(fn($img) => \Illuminate\Support\Facades\Storage::url($img), $images) : [];
        $this->isGalleryModalOpen = true;
    }

    public function closeGalleryModal()
    {
        $this->isGalleryModalOpen = false;
        $this->selectedProductImages = [];
    }

    // History Modal Methods
    public function viewStockHistory($productId)
    {
        $this->historyProduct = DioraProduct::findOrFail($productId);
        // Fetch all stock movements for this product, newest first
        $this->productStockHistory = DioraStock::where('diora_product_id', $productId)
            ->latest()
            ->get();
            
        $this->isHistoryModalOpen = true;
    }

    public function closeHistoryModal()
    {
        $this->isHistoryModalOpen = false;
        $this->historyProduct = null;
        $this->productStockHistory = [];
    }

    private function resetForm()
    {
        $this->diora_product_id = null;
        $this->quantity = 1;
        $this->type = 'addition';
        $this->reference_no = '';
        $this->notes = '';
    }

    public function storeStock()
    {
        $this->validate();

        $finalQty = $this->quantity;
        if ($this->type === 'deduction') {
            $finalQty = -abs($this->quantity);
        }

        DioraStock::create([
            'diora_product_id' => $this->diora_product_id,
            'quantity'         => $finalQty,
            'type'             => $this->type,
            'reference_no'     => $this->reference_no,
            'notes'            => $this->notes,
        ]);

        session()->flash('message', 'Stock entry recorded successfully.');
        $this->closeModal();
    }

    public function render()
    {
        $products = DioraProduct::with(['stocks'])
            ->withSum('stocks as total_stock', 'quantity')
            ->when($this->search, function($q) {
                $q->where('product_name', 'like', '%' . $this->search . '%')
                  ->orWhere('product_code', 'like', '%' . $this->search . '%')
                  ->orWhere('finish', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $allProductsForDropdown = DioraProduct::orderBy('product_name')->get();

        return view('livewire.diora-stock-manager', [
            'products' => $products,
            'allProductsForDropdown' => $allProductsForDropdown,
        ])->layout('layouts.app');
    }
}