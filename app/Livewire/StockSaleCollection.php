<?php

namespace App\Livewire;   

use App\Models\StockSale;
use App\Models\StockProduct;
use App\Models\StockCustomer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Str;

class StockSaleCollection extends Component
{
    use WithPagination;

    public $moduleTitle = STOCK_SALE_TITLE;
    public $perPage = PER_PAGE;
    public $isFormOpen = false;

    // Form fields
    public $uuid;
    public $product_id;
    public $customer_id;
    public $price;
    public $quantity = 1;
    public $total_amount;
    public $invoice_number;
    public $payment_status = 'pending';
    public $paid_amount = 0;
    public $sale_date;
    public $notes;
    public $status = 'draft';

    // Selected product details
    public $selectedProduct;
    public $availableStock;

    // Filters
    public $searchQuery = '';
    public $filterStatus = '';
    public $filterPaymentStatus = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonStatusUpdateSuccess;
    public $commonNotDeleteSuccess;

    protected $rules = [
        'product_id' => 'required|exists:stock_products,id',
        'customer_id' => 'required|exists:stock_customers,id',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:1',
        'invoice_number' => 'nullable|string|max:50',
        'payment_status' => 'required|in:pending,paid,partial',
        'paid_amount' => 'required|numeric|min:0',
        'sale_date' => 'required|date',
        'notes' => 'nullable|string|max:1000',
        'status' => 'required|in:draft,completed,cancelled'
    ];

    public function mount()
    {
        $this->sale_date = date('Y-m-d');
        
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonStatusUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_STATUS_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
    }

    public function updatedProductId()
    {
        if ($this->product_id) {
            $this->selectedProduct = StockProduct::find($this->product_id);
            if ($this->selectedProduct) {
                $this->price = $this->selectedProduct->price;
                $this->availableStock = $this->selectedProduct->quantity;
                $this->calculateTotal();
            }
        }
    }

    public function updatedQuantity()
    {
        $this->calculateTotal();
    }

    public function updatedPrice()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        if ($this->price && $this->quantity) {
            $this->total_amount = $this->price * $this->quantity;
        }
    }

    public function toggleForm()
    {
        $this->isFormOpen = !$this->isFormOpen;
        if (!$this->isFormOpen) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->reset([
            'uuid', 'product_id', 'customer_id', 'price', 'quantity',
            'total_amount', 'invoice_number', 'payment_status', 'paid_amount',
            'notes', 'status', 'selectedProduct', 'availableStock'
        ]);
        $this->sale_date = date('Y-m-d');
    }

    public function resetFilters()
    {
        $this->reset([
            'searchQuery', 'filterStatus', 'filterPaymentStatus',
            'filterDateFrom', 'filterDateTo'
        ]);
    }

    public function saveSale()
    {
        $this->validate();

        // Check available stock
        if ($this->selectedProduct && $this->quantity > $this->selectedProduct->quantity) {
            $this->addError('quantity', 'Insufficient stock available');
            return;
        }

        $sale = new StockSale();
        $sale->uuid = Str::uuid();
        $sale->fill([
            'product_id' => $this->product_id,
            'customer_id' => $this->customer_id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_amount' => $this->total_amount,
            'invoice_number' => $this->invoice_number,
            'payment_status' => $this->payment_status,
            'paid_amount' => $this->paid_amount,
            'sale_date' => $this->sale_date,
            'notes' => $this->notes,
            'status' => $this->status,
            'team_id' => Auth::user()->currentTeam->id,
            'user_id' => Auth::id(),
            'created_by' => Auth::id()
        ]);

        $sale->save();

        // Update product stock if sale is completed
        if ($this->status === 'completed') {
            $this->selectedProduct->quantity -= $this->quantity;
            $this->selectedProduct->save();
        }

        $this->resetForm();
        $this->isFormOpen = false;
        $this->dispatch('notify-success', $this->commonCreateSuccess);
    }

    public function editSale($uuid)
    {
        $sale = StockSale::where('uuid', $uuid)->first();
        if ($sale) {
            foreach ($sale->toArray() as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
            $this->updatedProductId();
            $this->isFormOpen = true;
        }
    }

    public function updateSale()
    {
        $this->validate();

        $sale = StockSale::where('uuid', $this->uuid)->first();
        if ($sale) {
            $oldQuantity = $sale->quantity;
            $oldStatus = $sale->status;

            $sale->fill([
                'product_id' => $this->product_id,
                'customer_id' => $this->customer_id,
                'price' => $this->price,
                'quantity' => $this->quantity,
                'total_amount' => $this->total_amount,
                'invoice_number' => $this->invoice_number,
                'payment_status' => $this->payment_status,
                'paid_amount' => $this->paid_amount,
                'sale_date' => $this->sale_date,
                'notes' => $this->notes,
                'status' => $this->status
            ]);

            // Handle stock updates
            if ($oldStatus !== 'completed' && $this->status === 'completed') {
                // New completion - reduce stock
                $this->selectedProduct->quantity -= $this->quantity;
                $this->selectedProduct->save();
            } elseif ($oldStatus === 'completed' && $this->status !== 'completed') {
                // Reverting completion - restore stock
                $this->selectedProduct->quantity += $oldQuantity;
                $this->selectedProduct->save();
            } elseif ($oldStatus === 'completed' && $this->status === 'completed' && $oldQuantity !== $this->quantity) {
                // Quantity changed while completed - adjust stock
                $difference = $oldQuantity - $this->quantity;
                $this->selectedProduct->quantity += $difference;
                $this->selectedProduct->save();
            }

            $sale->save();
            $this->resetForm();
            $this->isFormOpen = false;
            $this->dispatch('notify-success', $this->commonUpdateSuccess);
        }
    }

    public function deleteSale($uuid)
    {
        $sale = StockSale::where('uuid', $uuid)->first();
        if ($sale) {
            // Restore stock if sale was completed
            if ($sale->status === 'completed') {
                $product = StockProduct::find($sale->product_id);
                if ($product) {
                    $product->quantity += $sale->quantity;
                    $product->save();
                }
            }
            
            $sale->delete();
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        }
    }

    public function render()
    {
        $query = StockSale::with(['product', 'customer'])
            ->where('team_id', Auth::user()->currentTeam->id);

        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->whereHas('customer', function($q) {
                    $q->where('name', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhereHas('product', function($q) {
                    $q->where('product_name', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhere('invoice_number', 'like', '%' . $this->searchQuery . '%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterPaymentStatus) {
            $query->where('payment_status', $this->filterPaymentStatus);
        }

        if ($this->filterDateFrom) {
            $query->whereDate('sale_date', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo) {
            $query->whereDate('sale_date', '<=', $this->filterDateTo);
        }

        $sales = $query->latest()->paginate($this->perPage);
        
        return view('livewire.stock-management.stock-sale-collection', [
            'sales' => $sales,
            'products' => StockProduct::where('status', 'active')
                ->where('team_id', Auth::user()->currentTeam->id)
                ->get(),
            'customers' => StockCustomer::where('status', 'active')
                ->where('team_id', Auth::user()->currentTeam->id)
                ->get()
        ])->layout('layouts.app');
    }
}