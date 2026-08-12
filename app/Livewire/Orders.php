<?php
namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MyProduct; // Assuming your products model name from previous module
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    
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


    public function mount()
    {
        $this->productsList = MyProduct::all();
    }
// Update $queryString to include your new filters so state is preserved on pagination/refresh:
protected $queryString = [
    'search' => ['except' => ''], 
    'statusFilter' => ['except' => ''],
    'dateFrom' => ['except' => ''],
    'dateTo' => ['except' => ''],
    'customerFilter' => ['except' => ''],
    'paymentStatusFilter' => ['except' => ''],
];

 

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
            'orderItems.*.price' => 'required|numeric|min:0',
        ];
    }
    // Add these public properties to your class:
public $dateFrom = '';
public $dateTo = '';
public $customerFilter = '';
public $paymentStatusFilter = '';



    // Update updating methods so pagination resets when any filter changes:
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }
    public function updatingCustomerFilter() { $this->resetPage(); }
    public function updatingPaymentStatusFilter() { $this->resetPage(); }

    // Update your render method to include the new filters:
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

        // Optional: Get a unique list of customers for a dropdown filter if preferred
        $customersList = Order::select('customer_name')->distinct()->pluck('customer_name');

        return view('livewire.orders', [
            'orders' => $orders,
            'customersList' => $customersList
        ])->layout('layouts.app');
    }

    // Add a reset filters helper:
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
        
        // Auto-generate order number like ORD-2026-0001
        $latestOrder = Order::orderBy('id', 'desc')->first();
        $nextNumber = $latestOrder ? $latestOrder->id + 1 : 1;
        $this->order_number = 'ORD-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Add default first empty item row
        $this->addItemRow();

        $this->isOpen = true;
    }

    public function addItemRow()
    {
        $this->orderItems[] = [
            'my_product_id' => '',
            'quantity' => 1,
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
        // When product selection changes, auto-fill unit price if available
        if (Str::contains($key, 'my_product_id')) {
            $index = explode('.', $key)[0];
            $productId = $value;
            $product = MyProduct::find($productId);
            if ($product) {
                // If you store price in products table, assign it here. 
                // Using 0 as fallback or field if applicable.
                $this->orderItems[$index]['price'] = $product->selling_price ?? 0; 
            }
        }
    }

    public function store()
    {
        $this->validate();

        // Calculate total amount
        $totalAmount = collect($this->orderItems)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        $order = Order::create([
            'order_number' => $this->order_number,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'shipping_address' => $this->shipping_address,
            'total_amount' => $totalAmount,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
        ]);

        foreach ($this->orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'my_product_id' => $item['my_product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['quantity'] * $item['price'],
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