<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    
    public $isViewModalOpen = false;
    public $viewingOrder;

    protected $queryString = ['search' => ['except' => ''], 'statusFilter' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

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
            ->latest()
            ->paginate(10);

        return view('livewire.orders', [
            'orders' => $orders
        ])->layout('layouts.app');
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