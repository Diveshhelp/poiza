<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MyProduct;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $customerFilter = '';
    public $paymentStatusFilter = '';
    
    // View Modal Properties
    public $isViewModalOpen = false;
    public $viewingOrder;

    // Create Order Modal Properties
    public $isOpen = false;
    public $order_number;
    public $customer_name, $customer_phone, $customer_email, $shipping_address;
    public $status = 'pending', $payment_status = 'unpaid';
    
    // Order Items Dynamic Array
    public $orderItems = [];
    public $productsList = [];

    protected $queryString = [
        'search' => ['except' => ''], 
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'customerFilter' => ['except' => ''],
        'paymentStatusFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->productsList = MyProduct::all();
    }

    protected function rules()
    {
        return [
            'order_number' => 'required|string|unique:orders,order_number',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid,refunded',
            'orderItems' => 'required|array|min:1',
            'orderItems.*.my_product_id' => 'required|exists:my_products,id',
            'orderItems.*.quantity' => 'required|integer|min:1',
            'orderItems.*.unit_type' => 'required|in:piece,box',
            'orderItems.*.price' => 'required|numeric|min:0',
        ];
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }
    public function updatingCustomerFilter() { $this->resetPage(); }
    public function updatingPaymentStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $orders = Order::with('items')
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->paymentStatusFilter, function ($query) {
                $query->where('payment_status', $this->paymentStatusFilter);
            })
            ->when($this->customerFilter, function ($query) {
                $query->where('customer_name', 'like', '%' . $this->customerFilter . '%');
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest()
            ->paginate(10);

        $customersList = Order::select('customer_name')->distinct()->pluck('customer_name');

        return view('livewire.orders', [
            'orders' => $orders,
            'customersList' => $customersList
        ])->layout('layouts.app');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo', 'customerFilter', 'paymentStatusFilter']);
        $this->resetPage();
    }

    public function view($id)
    {
        $this->viewingOrder = Order::with('items.product')->findOrFail($id);
        $this->isViewModalOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->viewingOrder = null;
    }

    public function create()
    {
        $this->resetInputFields();
        
        $latestOrder = Order::orderBy('id', 'desc')->first();
        $nextNumber = $latestOrder ? $latestOrder->id + 1 : 1;
        $this->order_number = 'AM-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $this->addItemRow();

        $this->isOpen = true;
    }

    public function addItemRow()
    {
        $this->orderItems[] = [
            'my_product_id' => '',
            'quantity' => 1,
            'unit_type' => 'piece',
            'price' => 0
        ];
    }

    public function removeItemRow($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
    }

    public function updatedOrderItems($value, $key)
    {
        if (Str::contains($key, 'my_product_id')) {
            $index = explode('.', $key)[0];
            $productId = $value;
            $product = MyProduct::find($productId);
            if ($product) {
                // Fixed: Fetches 'price' matching your latest product schema
                $this->orderItems[$index]['price'] = $product->price ?? 0; 
            }
        }
    }

    public function store()
    {
        $this->validate();

        $totalAmount = collect($this->orderItems)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        $order = Order::create([
            'uuid' => (string) Str::uuid(),
            'order_number' => $this->order_number,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'shipping_address' => $this->shipping_address,
            'subtotal' => $totalAmount,
            'total_amount' => $totalAmount,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
        ]);

        foreach ($this->orderItems as $item) {
            $product = MyProduct::find($item['my_product_id']);
            
            OrderItem::create([
                'order_id' => $order->id,
                'my_product_id' => $item['my_product_id'],
                'product_name' => $product ? $product->product_name : 'Product',
                'sku' => $product ? $product->product_code : '',
                'model_key' => $product ? $product->model_key : null,
                'unit_type' => $item['unit_type'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
            ]);
        }

        session()->flash('message', 'Order created successfully.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->order_number = '';
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->customer_email = '';
        $this->shipping_address = '';
        $this->status = 'pending';
        $this->payment_status = 'unpaid';
        $this->orderItems = [];
    }

    public function updateOrderStatus($orderId, $newStatus)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $newStatus]);
        session()->flash('message', "Order {$order->order_number} status updated to " . ucfirst($newStatus) . ".");
    }

    public function updatePaymentStatus($orderId, $newPaymentStatus)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['payment_status' => $newPaymentStatus]);
        session()->flash('message', "Order {$order->order_number} payment status updated.");
    }
}