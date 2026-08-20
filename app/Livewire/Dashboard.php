<?php

namespace App\Livewire;

use App\Models\Order;
use Auth;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $team_id;
    public $pendingOrders = [];
    public $pendingOrdersCount = 0;

    // View Modal Properties
    public $isViewModalOpen = false;
    public $viewingOrder = null;

    public function mount()
    {
        $this->team_id = Auth::user()->currentTeam->id;
        $this->loadPendingOrders();
    }

    public function loadPendingOrders()
    {
        $this->pendingOrdersCount = Order::where('status', 'pending')
            ->count();

        // Fetch pending orders with their items & activities for review
        $this->pendingOrders = Order::with(['items', 'activities'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
    }

    // Open review modal for a specific order
    public function reviewOrder($orderId)
    {
        $this->viewingOrder = Order::with(['items', 'activities'])->findOrFail($orderId);
        $this->isViewModalOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->viewingOrder = null;
    }

    // Quick status update from the modal
    public function updateOrderStatus($orderId, $newStatus)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $newStatus]);

        // Refresh list
        $this->loadPendingOrders();
        $this->closeViewModal();
        session()->flash('message', 'Order status updated successfully.');
    }

    public function updatePaymentStatus($orderId, $newPaymentStatus)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['payment_status' => $newPaymentStatus]);

        $this->viewingOrder = $order->fresh(['items', 'activities']);
        session()->flash('message', 'Payment status updated successfully.');
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}