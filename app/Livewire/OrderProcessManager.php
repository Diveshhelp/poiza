<?php 

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CastingRecord;
use App\Models\OrderProcess;
use Livewire\Component;

class OrderProcessManager extends Component
{
    public Order $order;
    public string $activeStep = 'casting'; 
    public array $quantities = [];

    public function mount(Order $order): void
    {
        $this->order = $order->load(['items.castingRecord', 'processDetails']);        
        foreach ($this->order->items as $item) {
            // Fetch existing casting qty from the casting record table if available, else fallback
            $existingCasting = CastingRecord::where('order_item_id', $item->id)->value('casting_qty');

            $this->quantities[$item->id] = [
                'casting_qty' => $existingCasting ?? $item->quantity,
                'turning_qty' => $item->turning_qty ?? 0,
                'buff_qty'    => $item->buff_qty ?? 0,
            ];
        }
    }

    public function setStep(string $step): void
    {
        $this->activeStep = $step;
    }

    // Save casting quantities into the casting records table
    public function updateCasting(): void
    {
        foreach ($this->quantities as $itemId => $data) {
            CastingRecord::updateOrCreate(
                [
                    'order_id'      => $this->order->id,
                    'order_item_id' => $itemId,
                ],
                [
                    'casting_qty'   => $data['casting_qty'] ?? 0,
                ]
            );
        }
        // Automatically update the order process status for Casting
        OrderProcess::updateOrCreate(
            ['order_id' => $this->order->id],
            [
                'casting_status'       => 'completed',
                'casting_completed_at' => now(),
                'overall_status'       => 'casting_completed',
            ]
        );
        $this->order->refresh();
        session()->flash('message', 'Casting department information saved successfully.');
    }

    public function updateTurning(): void
    {
        foreach ($this->quantities as $itemId => $data) {
            OrderItem::where('id', $itemId)->update([
                'turning_qty' => $data['turning_qty'] ?? 0
            ]);
        }
        $this->order->refresh();
        session()->flash('message', 'Turning quantities updated successfully.');
    }

    public function updateBuff(): void
    {
        foreach ($this->quantities as $itemId => $data) {
            OrderItem::where('id', $itemId)->update([
                'buff_qty' => $data['buff_qty'] ?? 0
            ]);
        }
        $this->order->refresh();
        session()->flash('message', 'Buff quantities updated successfully.');
    }

    public function render()
    {
        return view('livewire.order-process-manager')
            ->layout('layouts.app');
    }
}