<?php

namespace App\Livewire;

use App\Models\StockMovement;
use App\Models\StockProduct;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Str;

class StockMovementCollection extends Component
{
    use WithPagination;

    public $moduleTitle = STOCK_MOVEMENT_TITLE; 
    public $perPage = PER_PAGE;   
    public $showModal = false;
    public $product_id;
    public $stock;
    public $type = 'in';
    public $notes;
    public $uuid;
    public $selectedProduct;

    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonStatusUpdateSuccess;
    public $commonNotDeleteSuccess;

    protected $rules = [
        'product_id' => 'required|exists:stock_products,id',
        'stock' => 'required|integer|min:1',
        'type' => 'required|in:in,out',
        'notes' => 'nullable|string|max:1000'
    ];


    public function mount($productuuid = null)
    {
        if ($productuuid) {
            $product = StockProduct::where('uuid', $productuuid)->first();
            if ($product) {
                $this->product_id = $product->id;
                $this->selectedProduct = $product;
            }
        }
        
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonStatusUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_STATUS_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($uuid)
    {
        $stockMovement = StockMovement::where('uuid', $uuid)->first();
        if ($stockMovement) {
            $this->uuid = $stockMovement->uuid;
            $this->product_id = $stockMovement->product_id;
            $this->stock = $stockMovement->stock;
            $this->type = $stockMovement->type;
            $this->notes = $stockMovement->notes;
            $this->selectedProduct = $stockMovement->product;
            $this->showModal = true;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->uuid) {
            $movement = StockMovement::where('uuid', $this->uuid)->first();
            // Reverse previous stock update
            $oldQuantity = $movement->stock * ($movement->type === 'in' ? -1 : 1);
            $this->updateProductStock($movement->product_id, $oldQuantity);
        } else {
            $movement = new StockMovement();
            $movement->uuid = Str::uuid();
        }

        $movement->product_id = $this->product_id;
        $movement->stock = $this->stock;
        $movement->type = $this->type;
        $movement->notes = $this->notes;
        $movement->team_id = Auth::user()->currentTeam->id;
        $movement->user_id = Auth::id();
        $movement->created_by = Auth::id();

        // Update product stock
        $this->updateProductStock($this->product_id, $this->stock * ($this->type === 'in' ? 1 : -1));

        $movement->save();

        $this->resetForm();
        $this->dispatch('notify-success', $this->commonCreateSuccess);
    }

    protected function updateProductStock($productId, $quantity)
    {
        $product = StockProduct::find($productId);
        if ($product) {
            $product->quantity += $quantity;
            $product->save();
        }
    }

    public function delete($uuid)
    {
        $movement = StockMovement::where('uuid', $uuid)->first();
        if ($movement) {
            // Reverse the stock movement
            $this->updateProductStock(
                $movement->product_id, 
                $movement->stock * ($movement->type === 'in' ? -1 : 1)
            );
            $movement->delete();
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        }
    }

    private function resetForm()
    {
        $this->reset(['uuid', 'stock', 'type', 'notes']);
        $this->showModal = false;
    }

    public function render()
    {
        $query = StockMovement::with(['product', 'user'])
            ->where('team_id', Auth::user()->currentTeam->id);

        if ($this->product_id) {
            $query->where('product_id', $this->product_id);
        }

        $stockMovements = $query->latest()->paginate(10);
        $products = StockProduct::where('team_id', Auth::user()->currentTeam->id)
            ->where('status', 'active')
            ->get();

        return view('livewire.stock-management.stock-movement-collection', [
            'stockMovements' => $stockMovements,
            'products' => $products
        ])->layout('layouts.app');
    }
}