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
    public $colorPieces = [];
    public $shippingItems = [];
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
            $existingCasting = CastingRecord::where('order_id', $this->order->id)
            ->where('order_item_id', $item->id)
            ->first();

            $this->quantities[$item->id] = [
                'casting_qty' => $existingCasting ? $existingCasting->casting_qty : $item->quantity,
                'buff_qty'    => $item->buff_qty ?? 0,
            ];
            // Determine piece count from product configuration
            $productPieces = 1;
            if ($item->product) {
                $productPieces = $item->product->piece ?? (int) $item->product->packing ?? 1;
            }

            $totalPieces = $productPieces > 0 ? $productPieces : 1;
            
            for ($i = 1; $i <= $totalPieces; $i++) {
                // -------------------------------------------------------------
                // 1. Initialize Turning pieces (Scoped to order, item & piece)
                // -------------------------------------------------------------
                $existingTurning = TurningPiece::where('order_id', $this->order->id)
                    ->where('order_item_id', $item->id)
                    ->where('piece_number', $i)
                    ->first();

                // Fetch default price and pricing type from product master turning prices relation (if available)
                $defaultTurningPrice = 0;
                $defaultTurningPricingType = 'piece';

                if ($item->product && method_exists($item->product, 'turningPrices') && $item->product->turningPrices) {
                    $turningPriceRow = $item->product->turningPrices->where('piece_number', $i)->first();
                    if ($turningPriceRow) {
                        $defaultTurningPrice = $turningPriceRow->price_per_piece ?? 0;
                        $defaultTurningPricingType = $turningPriceRow->pricing_type ?? 'piece';
                    }
                }

                $this->turningPieces[$item->id][$i]['order_qty']      = $existingTurning ? $existingTurning->order_qty : $item->quantity;
                $this->turningPieces[$item->id][$i]['received_qty']   = $existingTurning ? $existingTurning->received_qty : 0;
                $this->turningPieces[$item->id][$i]['price_per_unit'] = $existingTurning ? $existingTurning->price_per_unit : $defaultTurningPrice;
                $this->turningPieces[$item->id][$i]['pricing_type']   = $existingTurning ? ($existingTurning->pricing_type ?? $defaultTurningPricingType) : $defaultTurningPricingType;

                // -------------------------------------------------------------
                // 2. Initialize Buff pieces (Scoped strictly to order & product)
                // -------------------------------------------------------------
                if ($item->product_id) {
                    $existingBuff = \App\Models\BuffPiece::where('order_id', $this->order->id) 
                        ->where('product_id', $item->product_id)
                        ->where('piece_number', $i)
                        ->first();

                    // Fetch default price and pricing type from master buff_prices table
                    $defaultBuffPrice = 0;
                    $defaultBuffPricingType = 'piece';

                    if ($item->product && $item->product->buffPrices) {
                        $priceRow = $item->product->buffPrices->where('piece_number', $i)->first();
                        if ($priceRow) {
                            $defaultBuffPrice = $priceRow->price_per_piece ?? 0;
                            $defaultBuffPricingType = $priceRow->pricing_type ?? 'piece';
                        }
                    }
                    $existingTurning = TurningPiece::where('order_id', $this->order->id)
                    ->where('order_item_id', $item->id)
                    ->where('piece_number', $i)
                    ->first();

                    // Uses turning received_qty if present, otherwise turning order_qty, fallback to item quantity
                    $turningQty = $this->turningPieces[$item->id][$i]['received_qty'] > 0 
                        ? $this->turningPieces[$item->id][$i]['received_qty'] 
                        : ($this->turningPieces[$item->id][$i]['order_qty'] ?? $item->quantity);

                    $this->buffPieces[$item->product_id][$i]['order_qty'] = $turningQty;
                    $this->buffPieces[$item->product_id][$i]['received_qty']   = $existingBuff ? $existingBuff->received_qty : 0;
                    $this->buffPieces[$item->product_id][$i]['price_per_unit'] = $existingBuff ? $existingBuff->price_per_unit : $defaultBuffPrice;
                    $this->buffPieces[$item->product_id][$i]['pricing_type']   = $existingBuff ? ($existingBuff->pricing_type ?? $defaultBuffPricingType) : $defaultBuffPricingType;
                }

                // -------------------------------------------------------------
                // 3. Initialize Color pieces (Derives quantity from Buff)
                // -------------------------------------------------------------
                if ($item->product_id) {
                    $existingColor = TurningPiece::where('order_id', $this->order->id)
                    ->where('order_item_id', $item->id)
                    ->where('piece_number', $i)
                    ->first();

                    // Default Color Price and Type (fallback to 0 and 'piece')
                    $defaultColorPrice = 0;
                    $defaultColorPricingType = 'piece';

                    // Quantity cascade: Saved Color -> Buff received_qty -> Buff order_qty -> item quantity
                    $buffQty = null;
                    if (isset($this->buffPieces[$item->product_id][$i])) {
                        $buffRecv  = (int) ($this->buffPieces[$item->product_id][$i]['received_qty'] ?? 0);
                        $buffOrder = (int) ($this->buffPieces[$item->product_id][$i]['order_qty'] ?? 0);
                        $buffQty   = $buffRecv > 0 ? $buffRecv : $buffOrder;
                    }

                    $defaultColorOrderQty = $buffQty ?: $item->quantity;

                    $this->colorPieces[$item->product_id][$i]['order_qty']      = $existingColor ? $existingColor->order_qty : $defaultColorOrderQty;
                    $this->colorPieces[$item->product_id][$i]['received_qty']   = $existingColor ? $existingColor->received_qty : 0;
                    $this->colorPieces[$item->product_id][$i]['price_per_unit'] = $existingColor ? $existingColor->price_per_unit : $defaultColorPrice;
                    $this->colorPieces[$item->product_id][$i]['pricing_type']   = $existingColor ? ($existingColor->pricing_type ?? $defaultColorPricingType) : $defaultColorPricingType;
                }

                foreach ($this->order->items as $item) {
                // Determine auto-ready quantity: minimum completed quantity from Color/Coating or Buff
                $colorRecv = \App\Models\ColorPiece::where('order_id', $this->order->id)
                    ->where('order_item_id', $item->id)
                    ->sum('received_qty');

                $readyQty = $item->ready_qty ?? ($colorRecv > 0 ? $colorRecv : 0);

                $this->shippingItems[$item->id] = [
                    'order_qty' => (int) $item->quantity,
                    'ready_qty' => (int) $readyQty,
                    'status'    => $item->item_status ?? ($readyQty >= $item->quantity ? 'ready_to_ship' : 'in_production'),
                ];
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
            $orderItem = OrderItem::where("product_id", $itemId)->first();
            
            $orderItemID = $orderItem ? $orderItem->id : null;

            foreach ($pieces as $pieceNumber => $data) {
                $receivedQty = $data['received_qty'] ?? 0;
                $pricePerUnit = $data['price_per_unit'] ?? 0;
                
                BuffPiece::updateOrCreate(
                    ['order_id' => $this->order->id, 'order_item_id' => $orderItemID, 'piece_number' => $pieceNumber],
                    [
                        'product_id'     => $itemId,
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
    public function updateColor(): void
    {
        foreach ($this->colorPieces as $productId => $pieces) {
            foreach ($pieces as $pieceNumber => $data) {
                \App\Models\ColorPiece::updateOrCreate(
                    [
                        'order_id'     => $this->order->id,
                        'product_id'   => $productId,
                        'piece_number' => $pieceNumber,
                    ],
                    [
                        'order_qty'      => (int) ($data['order_qty'] ?? 0),
                        'received_qty'   => (int) ($data['received_qty'] ?? 0),
                        'price_per_unit' => (float) ($data['price_per_unit'] ?? 0),
                        'pricing_type'   => $data['pricing_type'] ?? 'piece',
                    ]
                );
            }
        }

        session()->flash('message', 'Color department quantities and prices saved successfully.');
        }

        public function updateShipping(): void
        {
            foreach ($this->shippingItems as $itemId => $data) {
                $orderQty = (int) ($data['order_qty'] ?? 0);
                $readyQty = (int) ($data['ready_qty'] ?? 0);

                // Auto-assign status if not manually set
                $status = $data['status'] ?? 'in_production';
                if ($readyQty >= $orderQty && $status === 'in_production') {
                    $status = 'ready_to_ship';
                }

                OrderItem::where('id', $itemId)->update([
                    'ready_qty'   => $readyQty,
                    'item_status' => $status,
                ]);
            }

            // Check if entire order is ready
            $allReady = collect($this->shippingItems)->every(fn($item) => $item['ready_qty'] >= $item['order_qty']);
            if (optional($this->order->processDetails)->exists) {
                $this->order->processDetails->update([
                    'shipping_status' => $allReady ? 'completed' : 'in_progress',
                ]);
            }

            $this->order->refresh();
            session()->flash('message', 'Shipping & dispatch quantities updated successfully.');
        }
    public function render()
    {
        return view('livewire.order-process-manager')
            ->layout('layouts.app');
    }
}