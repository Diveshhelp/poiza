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

<!-- Step Navigation Tabs -->
    @php
        $process = $order->processDetails;
        $cStatus = $process->casting_status ?? 'pending';
        $tStatus = $process->turning_status ?? 'pending';
        $bStatus = $process->buff_status ?? 'pending';
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <!-- 1. Casting Department Tab -->
        <button wire:click="setStep('casting')" 
            class="flex items-center justify-between p-4 rounded-xl border transition-all text-left relative overflow-hidden
            {{ $activeStep === 'casting' ? 'ring-2 ring-indigo-600 border-indigo-600 bg-white shadow-md' : 'bg-white border-gray-200 hover:border-gray-300 shadow-sm' }}">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 flex items-center justify-center rounded-lg font-bold text-xs
                    {{ $cStatus === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    @if($cStatus === 'completed')
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        1
                    @endif
                </span>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Step One</div>
                    <div class="text-sm font-bold text-gray-800">Casting Department</div>
                </div>
            </div>
            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full 
                {{ $cStatus === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                {{ str_replace('_', ' ', $cStatus) }}
            </span>
        </button>

        <!-- 2. Turning Step Tab -->
        <button wire:click="setStep('turning')" 
            class="flex items-center justify-between p-4 rounded-xl border transition-all text-left relative overflow-hidden
            {{ $activeStep === 'turning' ? 'ring-2 ring-indigo-600 border-indigo-600 bg-white shadow-md' : 'bg-white border-gray-200 hover:border-gray-300 shadow-sm' }}">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 flex items-center justify-center rounded-lg font-bold text-xs
                    {{ $tStatus === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    @if($tStatus === 'completed')
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        2
                    @endif
                </span>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Step Two</div>
                    <div class="text-sm font-bold text-gray-800">Turning Step</div>
                </div>
            </div>
            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full 
                {{ $tStatus === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                {{ str_replace('_', ' ', $tStatus) }}
            </span>
        </button>

        <!-- 3. Buff Step Tab -->
        <button wire:click="setStep('buff')" 
            class="flex items-center justify-between p-4 rounded-xl border transition-all text-left relative overflow-hidden
            {{ $activeStep === 'buff' ? 'ring-2 ring-indigo-600 border-indigo-600 bg-white shadow-md' : 'bg-white border-gray-200 hover:border-gray-300 shadow-sm' }}">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 flex items-center justify-center rounded-lg font-bold text-xs
                    {{ $bStatus === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    @if($bStatus === 'completed')
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        3
                    @endif
                </span>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Step Three</div>
                    <div class="text-sm font-bold text-gray-800">Buff Step</div>
                </div>
            </div>
            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full 
                {{ $bStatus === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                {{ str_replace('_', ' ', $bStatus) }}
            </span>
        </button>
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
                @endphp

                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50/50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-3 pb-2 border-b border-gray-200 gap-2">
                        <div>
                            <span class="font-bold text-gray-800 text-sm">{{ $item->product_name }}</span>
                            <span class="text-xs text-gray-400 ml-2">SKU: {{ $item->sku }}</span>
                        </div>
                        <div class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">
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

        <div class="space-y-6">
            @foreach($order->items as $item)
                @if(!$item->product_id) @continue @endif

                @php
                    $productPieces = 1;
                    if ($item->product) {
                        $productPieces = $item->product->piece ?? (int)($item->product->packing ?? 1);
                    }
                    $productFinish = $item->product->finish ?? $item->finish ?? 'Standard Finish';
                @endphp

                <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 pb-3 border-b border-gray-200 gap-2">
                        <div>
                            <span class="font-bold text-gray-800 text-base">{{ $item->product_name }}</span>
                            <span class="text-xs text-gray-400 ml-2">SKU: {{ $item->sku ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-amber-800 bg-amber-50 px-3 py-1 rounded-md border border-amber-200">
                                Finish: {{ $productFinish }}
                            </span>
                            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-md">
                                {{ max(1, $productPieces) }} Parts
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @for($p = 1; $p <= max(1, $productPieces); $p++)
                            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-xs space-y-3">
                                <div class="flex items-center justify-between border-b pb-2">
                                    <span class="text-xs font-bold text-gray-700">Part {{ $p }}</span>
                                    <select 
                                        wire:model="buffPieces.{{ $item->product_id }}.{{ $p }}.pricing_type" 
                                        class="text-[10px] rounded border-gray-300 py-0.5 px-1 focus:border-indigo-500 focus:ring-indigo-500 text-gray-600 font-semibold">
                                        <option value="piece">Per Piece</option>
                                        <option value="inch">Per Inch</option>
                                    </select>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Order Qty</label>
                                        <input 
                                            type="number" 
                                            wire:model="buffPieces.{{ $item->product_id }}.{{ $p }}.order_qty" 
                                            class="w-full text-center rounded-md border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Recv Qty</label>
                                        <input 
                                            type="number" 
                                            wire:model="buffPieces.{{ $item->product_id }}.{{ $p }}.received_qty" 
                                            class="w-full text-center rounded-md border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-indigo-600 uppercase mb-1">Price</label>
                                        <input 
                                            type="number" step="0.01"
                                            wire:model="buffPieces.{{ $item->product_id }}.{{ $p }}.price_per_unit" 
                                            class="w-full text-center rounded-md border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-1.5"
                                        >
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>

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
</div>