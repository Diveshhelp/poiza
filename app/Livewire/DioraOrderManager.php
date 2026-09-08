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
use Barryvdh\DomPDF\Facade\Pdf;
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
    public $discount_type = 'percentage'; // 'percentage' or 'flat'
    public $discount_value = 0;
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
            'discount_type'  => 'required|in:percentage,flat',
            'discount_value' => 'nullable|numeric|min:0',
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

    public function calculateGrandTotal()
{
    $subtotal = 0;
    foreach ($this->orderItems as $item) {
        $qty = (float)($item['quantity'] ?? 0);
        $price = (float)($item['price'] ?? 0);
        $subtotal += $qty * $price;
    }

    $discVal = (float)($this->discount_value ?? 0);
    $discAmount = 0;

    if ($this->discount_type === 'percentage') {
        $discAmount = $subtotal * ($discVal / 100);
    } else {
        $discAmount = $discVal; // flat amount
    }

    $finalTotal = max(0, $subtotal - $discAmount);

    return [
        'subtotal' => round($subtotal, 2),
        'discount_amount' => round($discAmount, 2),
        'total_amount' => round($finalTotal, 2),
    ];
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
    public $editingOrderId = null; // Tracks if we are editing an existing order

    public function editOrder($id)
{
    try {
        $order = DioraOrder::with('items')->findOrFail($id);
        $this->editingOrderId = $order->id;
        $this->diora_customer_id = $order->diora_customer_id;
        $this->order_date = $order->order_date;
        $this->notes = $order->notes;
        $this->status = $order->status;
        $this->discount_type = $order->discount_type ?? 'percentage';
        $this->discount_value = $order->discount_value ?? 0;
        
        $this->orderItems = [];
        foreach ($order->items as $item) {
            $this->orderItems[] = [
                'product_id' => $item->diora_product_id,
                'set_type' => $item->set_type ?? 'Round Dabi',
                'quantity' => $item->quantity,
                'price' => $item->price,
            ];
        }

        $this->isModalOpen = true;
    } catch (Throwable $e) {
        session()->flash('error', 'Unable to load order for editing.');
    }
}   public function storeOrder()
{
    $this->validate();

    try {
        DB::transaction(function () {
            $totals = $this->calculateGrandTotal();
            $activeStates = ['confirm', 'process', 'ready_for_dispatch', 'dispatched', 'done'];

            if ($this->editingOrderId) {
                // --- UPDATE EXISTING ORDER ---
                $order = DioraOrder::with('items')->findOrFail($this->editingOrderId);
                $oldStatus = $order->status;

                if (in_array($oldStatus, $activeStates)) {
                    $this->restoreStockForOrder($order);
                }

                $order->update([
                    'diora_customer_id' => $this->diora_customer_id,
                    'status'            => $this->status,
                    'subtotal'          => $totals['subtotal'],
                    'discount_type'     => $this->discount_type,
                    'discount_value'    => $this->discount_value,
                    'discount_amount'   => $totals['discount_amount'],
                    'total_amount'      => $totals['total_amount'],
                    'order_date'        => $this->order_date,
                    'notes'             => $this->notes,
                ]);

                $order->items()->delete();
                foreach ($this->orderItems as $item) {
                    DioraOrderItem::create([
                        'diora_order_id'   => $order->id,
                        'diora_product_id' => $item['product_id'],
                        'set_type'         => $item['set_type'],
                        'quantity'         => $item['quantity'],
                        'price'            => $item['price'],
                        'total'            => $item['quantity'] * $item['price'],
                    ]);
                }

                if (in_array($this->status, $activeStates)) {
                    $this->deductStockForOrder($order);
                }

                session()->flash('message', "Order #{$order->order_no} updated successfully.");

            } else {
                // --- CREATE NEW ORDER ---
                $orderNo = 'DR-' . strtoupper(uniqid());

                $order = DioraOrder::create([
                    'order_no'          => $orderNo,
                    'diora_customer_id' => $this->diora_customer_id,
                    'status'            => $this->status,
                    'subtotal'          => $totals['subtotal'],
                    'discount_type'     => $this->discount_type,
                    'discount_value'    => $this->discount_value,
                    'discount_amount'   => $totals['discount_amount'],
                    'total_amount'      => $totals['total_amount'],
                    'order_date'        => $this->order_date,
                    'notes'             => $this->notes,
                ]);

                foreach ($this->orderItems as $item) {
                    DioraOrderItem::create([
                        'diora_order_id'   => $order->id,
                        'diora_product_id' => $item['product_id'],
                        'set_type'         => $item['set_type'],
                        'quantity'         => $item['quantity'],
                        'price'            => $item['price'],
                        'total'            => $item['quantity'] * $item['price'],
                    ]);
                }

                if (in_array($this->status, $activeStates)) {
                    $this->deductStockForOrder($order);
                }

                session()->flash('message', 'Order placed successfully and inventory updated.');
            }
        });

        $this->closeModal();

    } catch (Throwable $e) {
        session()->flash('error', 'Failed to save order: ' . $e->getMessage());
    }
}

private function resetForm()
{
    $this->editingOrderId = null;
    $this->diora_customer_id = null;
    $this->order_date = date('Y-m-d');
    $this->notes = '';
    $this->status = 'pending';
    $this->discount_type = 'percentage';
    $this->discount_value = 0;
    $this->orderItems = [];
    $this->addOrderItem();
    $this->resetErrorBag();
}

    // Delivery Challan PDF Generator
    public function generateChallan($orderId)
    {
        try {
            $order = DioraOrder::with(['customer', 'items.product'])->findOrFail($orderId);

            $pdf = Pdf::loadView('pdf.diora-challan', [
                'order' => $order
            ]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'Delivery-Challan-' . $order->order_no . '.pdf');

        } catch (Throwable $e) {
            session()->flash('error', 'Failed to generate challan PDF: ' . $e->getMessage());
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