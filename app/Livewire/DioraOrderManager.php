<?php

namespace App\Livewire;

use App\Models\DioraOrder;
use App\Models\DioraOrderItem;
use App\Models\DioraCustomer;
use App\Models\DioraProduct;
use App\Models\DioraStock;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Throwable;

class DioraOrderManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $isModalOpen = false;
    public $isViewModalOpen = false;
    
    // Form Inputs
    public $diora_customer_id;
    public $order_date;
    public $notes;
    public $status = 'pending';
    public $orderItems = []; 
    
    public $viewOrder = null;

    protected function rules()
    {
        return [
            'diora_customer_id'       => 'required|exists:diora_customers,id',
            'order_date'              => 'required|date',
            'status'                  => 'required|in:pending,confirm,process,ready_for_dispatch,dispatched,done',
            'orderItems'              => 'required|array|min:1',
            'orderItems.*.product_id' => 'required|exists:diora_products,id',
            'orderItems.*.quantity'   => 'required|integer|min:1',
            'orderItems.*.price'      => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'diora_customer_id.required' => 'Please select a customer for this order.',
        'orderItems.required'        => 'At least one product item must be added.',
        'orderItems.*.product_id.required' => 'Please choose a valid product.',
        'orderItems.*.quantity.min'        => 'Order quantity must be at least 1.',
    ];

    public function mount()
    {
        $this->order_date = date('Y-m-d');
        $this->addOrderItem();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function addOrderItem()
    {
        $this->orderItems[] = [
            'product_id' => '',
            'quantity' => 1,
            'price' => 0,
        ];
    }

    public function removeOrderItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
        if (empty($this->orderItems)) {
            $this->addOrderItem();
        }
    }

    public function updatedOrderItems($value, $key)
    {
        $parts = explode('.', $key);
        if (isset($parts[0], $parts[1]) && $parts[1] === 'product_id') {
            $index = $parts[0];
            $productId = $value;
            if ($productId) {
                $product = DioraProduct::find($productId);
                if ($product) {
                    $this->orderItems[$index]['price'] = $product->price;
                }
            }
        }
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

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->viewOrder = null;
    }

    private function resetForm()
    {
        $this->diora_customer_id = null;
        $this->order_date = date('Y-m-d');
        $this->notes = '';
        $this->status = 'pending';
        $this->orderItems = [];
        $this->addOrderItem();
        $this->resetErrorBag();
    }

    public function storeOrder()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $totalAmount = 0;
                foreach ($this->orderItems as $item) {
                    $totalAmount += $item['quantity'] * $item['price'];
                }

                $orderNo = 'DR-' . strtoupper(uniqid());

                $order = DioraOrder::create([
                    'order_no'          => $orderNo,
                    'diora_customer_id' => $this->diora_customer_id,
                    'status'            => $this->status,
                    'total_amount'      => $totalAmount,
                    'order_date'        => $this->order_date,
                    'notes'             => $this->notes,
                ]);

                foreach ($this->orderItems as $item) {
                    DioraOrderItem::create([
                        'diora_order_id'   => $order->id,
                        'diora_product_id' => $item['product_id'],
                        'quantity'         => $item['quantity'],
                        'price'            => $item['price'],
                        'total'            => $item['quantity'] * $item['price'],
                    ]);
                }

                // If starting in an active state, deduct stock
                $activeStates = ['confirm', 'process', 'ready_for_dispatch', 'dispatched', 'done'];
                if (in_array($this->status, $activeStates)) {
                    $this->deductStockForOrder($order);
                }
            });

            session()->flash('message', 'Order placed successfully and inventory updated.');
            $this->closeModal();

        } catch (Throwable $e) {
            session()->flash('error', 'Failed to save order: ' . $e->getMessage());
        }
    }

    public function updateOrderStatus($orderId, $newStatus)
    {
        try {
            $order = DioraOrder::with('items')->findOrFail($orderId);
            $oldStatus = $order->status;
            $activeStates = ['confirm', 'process', 'ready_for_dispatch', 'dispatched', 'done'];
            
            DB::transaction(function () use ($order, $oldStatus, $newStatus, $activeStates) {
                if (!in_array($oldStatus, $activeStates) && in_array($newStatus, $activeStates)) {
                    $this->deductStockForOrder($order);
                } elseif (in_array($oldStatus, $activeStates) && $newStatus === 'pending') {
                    $this->restoreStockForOrder($order);
                }

                $order->update(['status' => $newStatus]);
            });

            session()->flash('message', "Order #{$order->order_no} status updated successfully.");
        } catch (Throwable $e) {
            session()->flash('error', 'Status update failed: ' . $e->getMessage());
        }
    }

    protected function deductStockForOrder(DioraOrder $order)
    {
        $existingLog = DioraStock::where('reference_no', $order->order_no)->exists();
        if ($existingLog) return;

        foreach ($order->items as $item) {
            DioraStock::create([
                'diora_product_id' => $item->diora_product_id,
                'quantity'         => -abs($item->quantity),
                'type'             => 'deduction',
                'reference_no'     => $order->order_no,
                'notes'            => 'Automatic deduction for Order #' . $order->order_no,
            ]);
        }
    }

    protected function restoreStockForOrder(DioraOrder $order)
    {
        $logs = DioraStock::where('reference_no', $order->order_no)->get();
        foreach ($logs as $log) {
            DioraStock::create([
                'diora_product_id' => $log->diora_product_id,
                'quantity'         => abs($log->quantity),
                'type'             => 'addition',
                'reference_no'     => $order->order_no . '-REV',
                'notes'            => 'Stock restoration for Order #' . $order->order_no,
            ]);
        }
    }

    public function view($id)
    {
        try {
            $this->viewOrder = DioraOrder::with(['customer', 'items.product'])->findOrFail($id);
            $this->isViewModalOpen = true;
        } catch (Throwable $e) {
            session()->flash('error', 'Unable to load order details.');
        }
    }

    public function delete($id)
    {
        try {
            $order = DioraOrder::with('items')->findOrFail($id);

            DB::transaction(function () use ($order) {
                $activeStates = ['confirm', 'process', 'ready_for_dispatch', 'dispatched', 'done'];
                if (in_array($order->status, $activeStates)) {
                    $this->restoreStockForOrder($order);
                }
                $order->delete();
            });

            session()->flash('message', 'Order deleted and inventory synchronized.');
        } catch (Throwable $e) {
            session()->flash('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $orders = DioraOrder::with(['customer', 'items'])
            ->when($this->search, function($q) {
                $q->where('order_no', 'like', '%' . $this->search . '%')
                  ->orWhereHas('customer', function($sub) {
                      $sub->where('customer_name', 'like', '%' . $this->search . '%')
                          ->orWhere('company_name', 'like', '%' . $this->search . '%');
                  });
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        $customers = DioraCustomer::orderBy('customer_name')->get();
        $products = DioraProduct::orderBy('product_name')->get();

        return view('livewire.diora-order-manager', [
            'orders' => $orders,
            'customers' => $customers,
            'products' => $products,
        ])->layout('layouts.app');
    }
}