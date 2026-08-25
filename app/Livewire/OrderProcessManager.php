<?php 
namespace App\Livewire;

use App\Models\BuffPiece;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CastingRecord;
use App\Models\TurningPiece;
use App\Models\OrderProcess;
use Livewire\Component;

class OrderProcessManager extends Component
{
    public Order $order;
    public string $activeStep = 'casting'; 
    public array $quantities = [];
    public array $buffPieces = []; // Holds multi-piece inputs for Buff step
    public array $turningPieces = []; // Holds multi-piece inputs for turning
    public function mount(Order $order): void
{
    // Eager load order items, products with their buff prices, and process details
    $this->order = $order->load([
        'items.product.buffPrices', 
        'items.castingRecord', 
        'items.turningPieces', 
        'processDetails'
    ]);
    
    foreach ($this->order->items as $item) {
        $existingCasting = optional($item->castingRecord)->casting_qty;

        $this->quantities[$item->id] = [
            'casting_qty' => $existingCasting ?? $item->quantity,
            'buff_qty'    => $item->buff_qty ?? 0,
        ];

        // Determine piece count from product configuration
        $productPieces = 1;
        if ($item->product) {
            $productPieces = $item->product->piece ?? (int) $item->product->packing ?? 1;
        }

        $totalPieces = $productPieces > 0 ? $productPieces : 1;
        
        for ($i = 1; $i <= $totalPieces; $i++) {
            // 1. Initialize Turning pieces
            $existingTurning = $item->turningPieces->where('piece_number', $i)->first();
            
            $this->turningPieces[$item->id][$i]['order_qty'] = $existingTurning ? $existingTurning->order_qty : $item->quantity;
            $this->turningPieces[$item->id][$i]['received_qty'] = $existingTurning ? $existingTurning->received_qty : 0;

            // 2. Initialize Buff pieces (Scoped strictly to this specific order AND product)
            if ($item->product_id) {
                $existingBuff = \App\Models\BuffPiece::where('order_id', $this->order->id) // Scope to current order
                    ->where('product_id', $item->product_id)
                    ->where('piece_number', $i)
                    ->first();

                // Fetch default price from master buff_prices table if not saved yet
                $defaultPrice = 0;
                if ($item->product && $item->product->buffPrices) {
                    $priceRow = $item->product->buffPrices->where('piece_number', $i)->first();
                    $defaultPrice = $priceRow ? $priceRow->price_per_piece : 0;
                }

                $this->buffPieces[$item->product_id][$i]['order_qty'] = $existingBuff ? $existingBuff->order_qty : $item->quantity;
                $this->buffPieces[$item->product_id][$i]['received_qty'] = $existingBuff ? $existingBuff->received_qty : 0;
                $this->buffPieces[$item->product_id][$i]['price_per_unit'] = $existingBuff ? $existingBuff->price_per_unit : $defaultPrice;
                $this->buffPieces[$item->product_id][$i]['pricing_type'] = $existingBuff ? $existingBuff->pricing_type : 'piece';
            }
        }
    }
}

    public function setStep(string $step): void
    {
        $this->activeStep = $step;
    }

    public function updateCasting(): void
    {
        foreach ($this->quantities as $itemId => $data) {
            CastingRecord::updateOrCreate(
                ['order_id' => $this->order->id, 'order_item_id' => $itemId],
                ['casting_qty' => $data['casting_qty'] ?? 0]
            );

        }

        OrderProcess::updateOrCreate(
            ['order_id' => $this->order->id],
            ['casting_status' => 'completed', 'casting_completed_at' => now()]
        );
        
        $this->order->refresh();
        session()->flash('message', 'Casting quantities saved successfully.');
    }

    // Save individual piece quantities for the Turning step
    public function updateTurning(): void
    {
        foreach ($this->turningPieces as $itemId => $pieces) {
            foreach ($pieces as $pieceNumber => $data) {
                TurningPiece::updateOrCreate(
                    [
                        'order_id'      => $this->order->id,
                        'order_item_id' => $itemId,
                        'piece_number'  => $pieceNumber,
                    ],
                    [
                        'order_qty'     => $data['order_qty'] ?? 0,
                        'received_qty'  => $data['received_qty'] ?? 0,
                    ]
                );
            }
        }

        OrderProcess::updateOrCreate(
            ['order_id' => $this->order->id],
            ['turning_status' => 'completed', 'turning_completed_at' => now()]
        );

        $this->order->refresh();
        session()->flash('message', 'Turning piece order and received quantities updated successfully.');
    }

    public function updateBuff(): void
    {
        foreach ($this->buffPieces as $itemId => $pieces) {
            $orderItem = OrderItem::find($itemId);
            $productId = $orderItem ? $orderItem->product_id : null;

            foreach ($pieces as $pieceNumber => $data) {
                $receivedQty = $data['received_qty'] ?? 0;
                $pricePerUnit = $data['price_per_unit'] ?? 0;
                
                BuffPiece::updateOrCreate(
                    ['order_id' => $this->order->id, 'order_item_id' => $itemId, 'piece_number' => $pieceNumber],
                    [
                        'product_id'     => $productId,
                        'order_qty'      => $data['order_qty'] ?? 0,
                        'received_qty'   => $receivedQty,
                        'price_per_unit' => $pricePerUnit,
                        'total_amount'   => $receivedQty * $pricePerUnit,
                        'pricing_type'   => $data['pricing_type'] ?? 'piece',
                    ]
                );
            }
        }

        OrderProcess::updateOrCreate(
            ['order_id' => $this->order->id],
            ['buff_status' => 'completed', 'buff_completed_at' => now()]
        );

        $this->order->refresh();
        session()->flash('message', 'Buff tracking and pricing updated successfully.');
    }
    public function render()
    {
        return view('livewire.order-process-manager')
            ->layout('layouts.app');
    }
}