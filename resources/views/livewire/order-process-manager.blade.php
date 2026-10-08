<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header & Step Status Overview -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b border-gray-200 gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h2 class="text-2xl font-extrabold text-gray-900">Order #{{ $order->order_number }} Process Tracking</h2>
                
            </div>
            <p class="text-sm text-gray-500 mt-1">Customer: <strong class="text-gray-700">{{ $order->customer_name }}</strong></p>
        </div>
        <a href="{{ route('orders') }}" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg font-semibold transition self-start md:self-auto">Back</a>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded text-sm text-emerald-800 shadow-sm flex items-center justify-between">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Step Navigation Tabs (Compact 1-Row Grid) -->
@php
    $process = $order->processDetails;
    $steps = [
        'casting'  => ['num' => 1, 'label' => 'Casting',  'status' => $process->casting_status ?? 'pending'],
        'turning'  => ['num' => 2, 'label' => 'Turning',  'status' => $process->turning_status ?? 'pending'],
        'buff'     => ['num' => 3, 'label' => 'Buffing',  'status' => $process->buff_status ?? 'pending'],
        'color'    => ['num' => 4, 'label' => 'Color',    'status' => $process->color_status ?? 'pending'],
        'shipping' => ['num' => 5, 'label' => 'Shipping', 'status' => $process->shipping_status ?? 'pending'],
    ];
@endphp

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 mb-6">
    @foreach($steps as $key => $step)
        @php
            $isComplete = $step['status'] === 'completed';
            $isActive = $activeStep === $key;
        @endphp
        <button wire:click="setStep('{{ $key }}')" 
            class="flex items-center justify-between p-2 rounded-lg border transition-all text-left relative overflow-hidden
            {{ $isActive ? 'ring-2 ring-indigo-600 border-indigo-600 bg-indigo-50/30 shadow-xs' : 'bg-white border-gray-200 hover:border-gray-300 shadow-2xs' }}">
            
            <div class="flex items-center gap-2 min-w-0">
                <span class="w-6 h-6 flex items-center justify-center rounded-md font-bold text-xs shrink-0
                    {{ $isComplete ? 'bg-emerald-100 text-emerald-700' : ($isActive ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600') }}">
                    @if($isComplete)
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        {{ $step['num'] }}
                    @endif
                </span>
                
                <div class="min-w-0">
                    <div class="text-[9px] font-semibold uppercase tracking-wider leading-none text-gray-400">Step {{ $step['num'] }}</div>
                    <div class="text-xs font-bold text-gray-800 truncate leading-tight mt-0.5">{{ $step['label'] }}</div>
                </div>
            </div>

            <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase rounded shrink-0 ml-1
                {{ $isComplete ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                {{ $isComplete ? 'Done' : 'Pend' }}
            </span>
        </button>
    @endforeach
</div>
    <!-- STEP 1: CASTING -->
@if($activeStep === 'casting')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-2">
            <div>
                <h3 class="text-base font-bold text-gray-900">Casting Requisition Details</h3>
                <p class="text-xs text-gray-500">Manage required casting quantities based on order specifications.</p>
            </div>

            <!-- Dynamic Casting Ordered Badge -->
            @php
                $hasCastingRecords = $order->items->contains(function($item) {
                    return $item->castingRecord && $item->castingRecord->casting_qty > 0;
                });
            @endphp

            @if($hasCastingRecords)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200 self-start md:self-auto">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Already Ordered for Casting
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full border border-amber-200 self-start md:self-auto">
                    Pending Casting Order
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Product Name / SKU</th>
                        <th class="px-4 py-3 text-left">Model Key</th>
                        <th class="px-4 py-3 text-center">Ordered Qty</th>
                        <th class="px-4 py-3 text-center">Casting Qty</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($order->items as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-800">{{ $item->product_name }}</div>
                                <div class="text-xs text-gray-400">SKU: {{ $item->sku ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">{{ $item->model_key ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-center text-gray-500 font-semibold">
                                {{ $item->quantity }} {{ $item->unit_type }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" wire:model="quantities.{{ $item->id }}.casting_qty" class="w-28 text-center rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-400">No items found for this order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Save Button with Loader -->
        <div class="mt-6 flex justify-end">
            <button wire:click="updateCasting" wire:loading.attr="disabled" class="inline-flex items-center px-5 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 transition disabled:opacity-50">
                <svg wire:loading wire:target="updateCasting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="updateCasting">Save Casting Quantities</span>
                <span wire:loading wire:target="updateCasting">Saving...</span>
            </button>
        </div>
    </div>
@endif
@if($activeStep === 'turning')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="mb-4">
            <h3 class="text-base font-bold text-gray-900">Turning Department Piece Tracking</h3>
            <p class="text-xs text-gray-500">Manage individual piece order quantities versus actual received quantities.</p>
        </div>

        <div class="space-y-6">
            @foreach($order->items as $item)
                @php
                    $productPieces = 1;
                    if ($item->product) {
                        $productPieces = $item->product->piece ?? (int) $item->product->packing ?? 1;
                    }
                    $finish = $item->finish ?? $item->product?->finish;
                    $size = $item->size ?? $item->product?->size;
                @endphp

                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50/50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-3 pb-2 border-b border-gray-200 gap-2">
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-bold text-gray-800 text-sm">{{ $item->product_name }}</span>
                                <span class="text-xs font-mono text-gray-400">SKU: {{ $item->sku }}</span>
                            </div>

                            {{-- Finish & Size Badges --}}
                            @if(!empty($finish) || !empty($size))
                                <div class="flex flex-wrap items-center gap-1.5 text-[10px]">
                                    @if(!empty($finish))
                                        <span class="inline-flex items-center gap-1 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200 px-1.5 py-0.5 rounded border border-amber-200/70 dark:border-amber-800/60 font-medium">
                                            <b class="text-amber-600 dark:text-amber-400 font-semibold uppercase text-[9px]">Finish:</b> {{ $finish }}
                                        </span>
                                    @endif

                                    @if(!empty($size))
                                        <span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-gray-700 text-slate-800 dark:text-gray-200 px-1.5 py-0.5 rounded border border-slate-200 dark:border-gray-600 font-medium">
                                            <b class="text-slate-500 dark:text-gray-400 font-semibold uppercase text-[9px]">Size:</b> {{ $size }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md self-start md:self-auto">
                            Total Parts Count: {{ $productPieces }} parts
                        </div>
                    </div>

                    <!-- Dynamic Piece Inputs Grid for Order Qty & Received Qty -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @for($p = 1; $p <= $productPieces; $p++)
                            <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-xs space-y-2">
                                <div class="text-xs font-bold text-gray-700 border-b pb-1">Part {{ $p }}</div>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Order Qty</label>
                                        <input 
                                            type="number" 
                                            wire:model="turningPieces.{{ $item->id }}.{{ $p }}.order_qty" 
                                            class="w-full text-center rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 py-1"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Received Qty</label>
                                        <input 
                                            type="number" 
                                            wire:model="turningPieces.{{ $item->id }}.{{ $p }}.received_qty" 
                                            class="w-full text-center rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 py-1"
                                        >
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Save Button with Loader -->
        <div class="mt-6 flex justify-end">
            <button wire:click="updateTurning" wire:loading.attr="disabled" class="inline-flex items-center px-5 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 transition disabled:opacity-50">
                <svg wire:loading wire:target="updateTurning" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="updateTurning">Save Turning Quantities</span>
                <span wire:loading wire:target="updateTurning">Saving...</span>
            </button>
        </div>
    </div>
@endif

 <!-- STEP 3: BUFF -->
    @if($activeStep === 'buff')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="mb-6 pb-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Buff Department Tracking & Pricing</h3>
                <p class="text-xs text-gray-500 mt-1">Manage finish specifications, individual piece counts, received quantities, and unit pricing.</p>
            </div>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                    @if(!$item->product_id) @continue @endif

                    @php
                        $productPieces = 1;
                        if ($item->product) {
                            $productPieces = $item->product->piece ?? (int)($item->product->packing ?? 1);
                        }
                        $productPieces = max(1, $productPieces);
                        
                        $productFinish = $item->finish ?? $item->product?->finish;
                        $productSize = $item->size ?? $item->product?->size;
                    @endphp

                    <!-- Single Compact Row Container -->
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-2xs p-4 hover:border-gray-300 transition-all">
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                            
                            <!-- Left Column: Product Identity & Details -->
                            <div class="xl:w-1/4 space-y-1.5">
                                <div class="flex items-center justify-between xl:justify-start gap-2">
                                    <span class="font-bold text-gray-900 text-xs tracking-tight truncate max-w-[220px]" title="{{ $item->product_name }}">
                                        {{ $item->product_name }}
                                    </span>
                                </div>

                                {{-- Explicit Finish & Size Badges --}}
                                @if(!empty($productFinish) || !empty($productSize))
                                    <div class="flex flex-wrap items-center gap-1.5 text-[10px]">
                                        @if(!empty($productFinish))
                                            <span class="inline-flex items-center gap-1 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200 px-1.5 py-0.5 rounded border border-amber-200/70 dark:border-amber-800/60 font-medium">
                                                <b class="text-amber-600 dark:text-amber-400 font-semibold uppercase text-[9px]">Finish:</b> {{ $productFinish }}
                                            </span>
                                        @endif

                                        @if(!empty($productSize))
                                            <span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-gray-700 text-slate-800 dark:text-gray-200 px-1.5 py-0.5 rounded border border-slate-200 dark:border-gray-600 font-medium">
                                                <b class="text-slate-500 dark:text-gray-400 font-semibold uppercase text-[9px]">Size:</b> {{ $productSize }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-center gap-2 text-[11px] text-gray-400">
                                    <span>SKU: <strong class="text-gray-600 font-medium">{{ $item->sku ?? 'N/A' }}</strong></span>
                                    <span>•</span>
                                    <span class="text-indigo-600 font-semibold bg-indigo-50 px-1.5 py-0.5 rounded text-[10px]">
                                        {{ $productPieces }} {{ $productPieces > 1 ? 'Parts' : 'Part' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Right Column: Part Matrix Grid -->
                            <div class="xl:w-3/4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                    @for($p = 1; $p <= $productPieces; $p++)
                                        <div class="bg-gray-50/75 rounded-lg border border-gray-200/70 p-2.5 flex flex-col justify-between gap-2">
                                            
                                            <!-- Part Header & Pricing Type Selector -->
                                            <div class="flex items-center justify-between border-b border-gray-200/60 pb-1.5">
                                                <span class="text-[11px] font-extrabold text-gray-700">Part {{ $p }}</span>
                                                <select 
                                                    wire:model="buffPieces.{{ $item->product_id }}.{{ $p }}.pricing_type" 
                                                    class="text-[10px] rounded border-gray-300 py-0.5 px-1 bg-white focus:border-indigo-500 focus:ring-indigo-500 text-gray-600 font-semibold shadow-2xs">
                                                    <option value="piece">Per Piece</option>
                                                    <option value="inch">Per Inch</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Quantities & Price Inputs Grid -->
                                            <div class="grid grid-cols-3 gap-1.5">
                                                <div>
                                                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-0.5">Order</label>
                                                    <input 
                                                        type="number" 
                                                        wire:model="buffPieces.{{ $item->product_id }}.{{ $p }}.order_qty" 
                                                        class="w-full text-center rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 bg-white font-medium"
                                                        placeholder="0"
                                                    >
                                                </div>
                                                <div>
                                                    <label class="block text-[9px] font-bold text-emerald-600 uppercase mb-0.5">Recv</label>
                                                    <input 
                                                        type="number" 
                                                        wire:model="buffPieces.{{ $item->product_id }}.{{ $p }}.received_qty" 
                                                        class="w-full text-center rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 bg-white font-semibold text-emerald-700"
                                                        placeholder="0"
                                                    >
                                                </div>
                                                <div>
                                                    <label class="block text-[9px] font-bold text-indigo-600 uppercase mb-0.5">Price</label>
                                                    <input 
                                                        type="number" step="0.01"
                                                        wire:model="buffPieces.{{ $item->product_id }}.{{ $p }}.price_per_unit" 
                                                        class="w-full text-center rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 bg-white font-semibold text-indigo-700"
                                                        placeholder="0.00"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Save Button with Loader -->
            <div class="mt-6 flex justify-end">
                <button wire:click="updateBuff" wire:loading.attr="disabled" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition disabled:opacity-50 shadow-sm">
                    <svg wire:loading wire:target="updateBuff" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="updateBuff">Save Buff Details & Prices</span>
                    <span wire:loading wire:target="updateBuff">Saving...</span>
                </button>
            </div>
        </div>
    @endif

    <!-- STEP: COLOR / COATING -->
@if($activeStep === 'color')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6 pb-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Color / Coating Department Tracking</h3>
            <p class="text-xs text-gray-500 mt-1">Manage finish shades, individual piece counts, received quantities, and unit pricing.</p>
        </div>
        
        <div class="space-y-4">
            @foreach($order->items as $item)
                @if(!$item->product_id) @continue @endif

                @php
                    $productPieces = 1;
                    if ($item->product) {
                        $productPieces = $item->product->piece ?? (int)($item->product->packing ?? 1);
                    }
                    $productPieces = max(1, $productPieces);
                    
                    $productFinish = $item->finish ?? $item->product?->finish;
                    $productSize = $item->size ?? $item->product?->size;
                @endphp

                <!-- Single Compact Row Container -->
                <div class="bg-white rounded-xl border border-gray-200/80 shadow-2xs p-4 hover:border-gray-300 transition-all">
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                        
                        <!-- Left Column: Product Identity & Details -->
                        <div class="xl:w-1/4 space-y-1.5">
                            <div class="flex items-center justify-between xl:justify-start gap-2">
                                <span class="font-bold text-gray-900 text-xs tracking-tight truncate max-w-[220px]" title="{{ $item->product_name }}">
                                    {{ $item->product_name }}
                                </span>
                            </div>

                            {{-- Finish & Size Badges --}}
                            @if(!empty($productFinish) || !empty($productSize))
                                <div class="flex flex-wrap items-center gap-1.5 text-[10px]">
                                    @if(!empty($productFinish))
                                        <span class="inline-flex items-center gap-1 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200 px-1.5 py-0.5 rounded border border-amber-200/70 dark:border-amber-800/60 font-medium">
                                            <b class="text-amber-600 dark:text-amber-400 font-semibold uppercase text-[9px]">Finish:</b> {{ $productFinish }}
                                        </span>
                                    @endif

                                    @if(!empty($productSize))
                                        <span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-gray-700 text-slate-800 dark:text-gray-200 px-1.5 py-0.5 rounded border border-slate-200 dark:border-gray-600 font-medium">
                                            <b class="text-slate-500 dark:text-gray-400 font-semibold uppercase text-[9px]">Size:</b> {{ $productSize }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="flex items-center gap-2 text-[11px] text-gray-400">
                                <span>SKU: <strong class="text-gray-600 font-medium">{{ $item->sku ?? 'N/A' }}</strong></span>
                                <span>•</span>
                                <span class="text-indigo-600 font-semibold bg-indigo-50 px-1.5 py-0.5 rounded text-[10px]">
                                    {{ $productPieces }} {{ $productPieces > 1 ? 'Parts' : 'Part' }}
                                </span>
                            </div>
                        </div>

                        <!-- Right Column: Part Matrix Grid -->
                        <div class="xl:w-3/4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                @for($p = 1; $p <= $productPieces; $p++)
                                    <div class="bg-gray-50/75 rounded-lg border border-gray-200/70 p-2.5 flex flex-col justify-between gap-2">
                                        
                                        <!-- Part Header & Pricing Type Selector -->
                                        <div class="flex items-center justify-between border-b border-gray-200/60 pb-1.5">
                                            <span class="text-[11px] font-extrabold text-gray-700">Part {{ $p }}</span>
                                            <select 
                                                wire:model="colorPieces.{{ $item->product_id }}.{{ $p }}.pricing_type" 
                                                class="text-[10px] rounded border-gray-300 py-0.5 px-1 bg-white focus:border-indigo-500 focus:ring-indigo-500 text-gray-600 font-semibold shadow-2xs">
                                                <option value="piece">Per Piece</option>
                                                <option value="inch">Per Inch</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Quantities & Price Inputs Grid -->
                                        <div class="grid grid-cols-3 gap-1.5">
                                            <div>
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-0.5">Order</label>
                                                <input 
                                                    type="number" 
                                                    wire:model="colorPieces.{{ $item->product_id }}.{{ $p }}.order_qty" 
                                                    class="w-full text-center rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 bg-white font-medium"
                                                    placeholder="0"
                                                >
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-emerald-600 uppercase mb-0.5">Recv</label>
                                                <input 
                                                    type="number" 
                                                    wire:model="colorPieces.{{ $item->product_id }}.{{ $p }}.received_qty" 
                                                    class="w-full text-center rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 bg-white font-semibold text-emerald-700"
                                                    placeholder="0"
                                                >
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-indigo-600 uppercase mb-0.5">Price</label>
                                                <input 
                                                    type="number" step="0.01"
                                                    wire:model="colorPieces.{{ $item->product_id }}.{{ $p }}.price_per_unit" 
                                                    class="w-full text-center rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 bg-white font-semibold text-indigo-700"
                                                    placeholder="0.00"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- Save Button with Loader -->
        <div class="mt-6 flex justify-end">
            <button wire:click="updateColor" wire:loading.attr="disabled" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition disabled:opacity-50 shadow-sm">
                <svg wire:loading wire:target="updateColor" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="updateColor">Save Color Details & Prices</span>
                <span wire:loading wire:target="updateColor">Saving...</span>
            </button>
        </div>
    </div>
@endif
<!-- STEP 5: SHIPPING & DISPATCH TRACKING -->
@if($activeStep === 'shipping')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-base font-bold text-gray-900">Shipping & Dispatch Tracking</h3>
                <p class="text-xs text-gray-500">Track order quantity, ready-for-dispatch quantity, pending balance, and item shipping status.</p>
            </div>
            
            {{-- Quick Stats Badge --}}
            @php
                $totalOrdered = collect($shippingItems)->sum('order_qty');
                $totalReady = collect($shippingItems)->sum('ready_qty');
                $totalRemaining = max(0, $totalOrdered - $totalReady);
            @endphp
            <div class="flex items-center gap-2 text-xs font-semibold">
                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md border border-emerald-200">
                    Ready: {{ $totalReady }} / {{ $totalOrdered }}
                </span>
                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-md border border-amber-200">
                    Remaining: {{ $totalRemaining }}
                </span>
            </div>
        </div>

        <!-- Shipping Items Table (Compact & Space-Saving) -->
        <div class="border rounded-xl overflow-x-auto dark:border-gray-700 shadow-xs">
            <table class="w-full text-left text-xs min-w-[620px]">
                <thead class="bg-gray-50/80 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                    <tr>
                        <th class="px-3 py-2 font-semibold">Product Details</th>
                        <th class="px-2 py-2 font-semibold text-center w-24">Order Qty</th>
                        <th class="px-2 py-2 font-semibold text-center w-28">Ready Qty</th>
                        <th class="px-2 py-2 font-semibold text-center w-24">Remaining</th>
                        <th class="px-3 py-2 font-semibold text-center w-36">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-gray-700 dark:text-gray-200">
                    @foreach($order->items as $item)
                        @php
                            $finish = $item->finish ?? $item->product?->finish;
                            $size = $item->size ?? $item->product?->size;
                            
                            $orderQty = (int) ($shippingItems[$item->id]['order_qty'] ?? $item->quantity);
                            $readyQty = (int) ($shippingItems[$item->id]['ready_qty'] ?? 0);
                            $remainingQty = max(0, $orderQty - $readyQty);
                        @endphp
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors">
                            {{-- Product Name & Attributes --}}
                            <td class="px-3 py-2.5">
                                <div class="font-bold text-gray-900 dark:text-white leading-snug">
                                    {{ $item->product_name }}
                                </div>
                                <div class="flex flex-wrap items-center gap-1.5 mt-0.5 text-[10px]">
                                    @if(!empty($finish))
                                        <span class="inline-flex items-center gap-0.5 px-1 py-0.2 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200 rounded border border-amber-200/60">
                                            <b>Finish:</b> {{ $finish }}
                                        </span>
                                    @endif
                                    @if(!empty($size))
                                        <span class="inline-flex items-center gap-0.5 px-1 py-0.2 bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 rounded">
                                            <b>Size:</b> {{ $size }}
                                        </span>
                                    @endif
                                    <span class="font-mono text-gray-400">#{{ $item->sku }}</span>
                                </div>
                            </td>

                            {{-- Order Quantity --}}
                            <td class="px-2 py-2.5 text-center font-semibold text-gray-800 dark:text-gray-100">
                                {{ $orderQty }}
                            </td>

                            {{-- Ready Quantity Input --}}
                            <td class="px-2 py-2.5 text-center">
                                <input 
                                    type="number" 
                                    min="0" 
                                    max="{{ $orderQty }}"
                                    wire:model.live="shippingItems.{{ $item->id }}.ready_qty" 
                                    class="w-20 text-center font-bold text-emerald-700 bg-emerald-50/50 border-emerald-300 rounded-lg text-xs py-1 focus:ring-emerald-500 focus:border-emerald-500"
                                >
                            </td>

                            {{-- Remaining Balance Quantity --}}
                            <td class="px-2 py-2.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-bold text-xs {{ $remainingQty === 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $remainingQty }}
                                </span>
                            </td>

                            {{-- Status Dropdown --}}
                            <td class="px-3 py-2.5 text-center">
                                <select 
                                    wire:model="shippingItems.{{ $item->id }}.status" 
                                    class="text-[11px] rounded-lg border-gray-300 py-1 px-2 font-semibold shadow-2xs focus:ring-indigo-500 focus:border-indigo-500 {{ ($shippingItems[$item->id]['status'] ?? '') === 'ready_to_ship' ? 'text-emerald-700 bg-emerald-50/70 border-emerald-300' : (($shippingItems[$item->id]['status'] ?? '') === 'shipped' ? 'text-blue-700 bg-blue-50/70 border-blue-300' : 'text-amber-700 bg-amber-50/70 border-amber-300') }}"
                                >
                                    <option value="in_production">In Production</option>
                                    <option value="ready_to_ship">Ready to Ship</option>
                                    <option value="shipped">Dispatched / Shipped</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Action / Save Bar -->
        <div class="flex items-center justify-between pt-2">
            <p class="text-[11px] text-gray-400 italic">Remaining balance recalculates automatically as ready quantities change.</p>
            <button wire:click="updateShipping" wire:loading.attr="disabled" class="inline-flex items-center px-5 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition disabled:opacity-50 shadow-xs">
                <svg wire:loading wire:target="updateShipping" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="updateShipping">Save Shipping Details</span>
                <span wire:loading wire:target="updateShipping">Saving...</span>
            </button>
        </div>
    </div>
@endif
</div>