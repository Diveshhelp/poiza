<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">Inventory & Stock Management</h2>
            <p class="text-xs text-gray-500 mt-0.5">Track real-time stock levels, check-in new stock, and audit inventory transactions.</p>
        </div>
        <div class="flex items-center gap-3">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search product inventory..." 
                class="rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 w-64 py-2"
            >
            <button 
                wire:click="openModal" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition whitespace-nowrap">
                + Add Stock Entry
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-3.5 rounded-xl text-xs text-emerald-800 shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Stock Overview Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold w-36">Product Image</th>
                        <th class="px-4 py-3 text-left font-bold">Product Name & Code</th>
                        <th class="px-4 py-3 text-center font-bold">Finish</th>
                        <th class="px-4 py-3 text-center font-bold">Price</th>
                        <th class="px-4 py-3 text-center font-bold">Current Stock Level</th>
                        <th class="px-4 py-3 text-right font-bold">Quick Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        @php
                            $currentStock = $product->total_stock ?? 0;
                            $hasImages = $product->images && count($product->images) > 0;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition" x-data="{ activeIndex: 0, images: @js($hasImages ? array_map(fn($img) => Storage::url($img), $product->images) : []) }">
                            
                            <!-- Product Image Column with Gallery Support -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($hasImages)
                                        <div class="relative group cursor-pointer" wire:click="showGallery('{{ json_encode($product->images) }}')">
                                            <!-- Main Thumbnail View -->
                                            <img :src="images[activeIndex]" class="w-12 h-12 rounded-xl object-cover border border-gray-200 shadow-2xs hover:opacity-90 transition">
                                            <span class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 rounded-xl transition text-white text-[9px] font-bold">
                                                View
                                            </span>
                                            
                                            <!-- Image Counter Badge -->
                                            <template x-if="images.length > 1">
                                                <span class="absolute bottom-1 right-1 bg-black/70 text-white text-[8px] font-bold px-1 py-0.2 rounded-full" x-text="(activeIndex + 1) + '/' + images.length"></span>
                                            </template>
                                        </div>

                                        <!-- Scroll Navigation Arrows for Multiple Images -->
                                        <template x-if="images.length > 1">
                                            <div class="flex flex-col justify-between h-12 py-0.5">
                                                <button @click.stop="activeIndex = (activeIndex === 0) ? images.length - 1 : activeIndex - 1" class="bg-gray-100 hover:bg-indigo-600 hover:text-white text-gray-600 rounded p-0.5 transition shadow-2xs" title="Previous Image">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                                </button>
                                                <button @click.stop="activeIndex = (activeIndex === images.length - 1) ? 0 : activeIndex + 1" class="bg-gray-100 hover:bg-indigo-600 hover:text-white text-gray-600 rounded p-0.5 transition shadow-2xs" title="Next Image">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-[10px] text-gray-400 font-medium">No Img</div>
                                    @endif
                                </div>
                            </td>

                            <!-- Product Name & Codes -->
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900 text-xs">{{ $product->product_name }}</div>
                                <div class="text-[11px] text-gray-400">Code: <span class="text-gray-600 font-medium">{{ $product->product_code }}</span></div>
                            </td>

                            <!-- Finish -->
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block text-[11px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                    {{ $product->finish ?? 'Standard' }}
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="px-4 py-3 text-center text-xs font-semibold text-gray-700">
                                ₹{{ number_format($product->price, 2) }}
                            </td>

                            <!-- Current Stock Status Badge -->
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $currentStock > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $currentStock }} Units Available
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                                <!-- History Ledger Button -->
                                <button wire:click="viewStockHistory({{ $product->id }})" title="View Stock History" class="px-2.5 py-1 bg-sky-50 text-sky-600 hover:bg-sky-100 rounded-lg text-xs font-semibold transition">
                                    History
                                </button>
                                <!-- Adjust Stock Button -->
                                <button wire:click="openModal({{ $product->id }})" class="px-2.5 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-semibold transition">
                                    + Adjust
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-xs">
                                No products found in inventory.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 text-xs">
            {{ $products->links() }}
        </div>
    </div>

    <!-- ADD / ADJUST STOCK MODAL -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-base">Record Stock Movement</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Select Product *</label>
                        <select wire:model="diora_product_id" class="w-full rounded-lg border-gray-300 py-1.5 px-2 bg-white">
                            <option value="">-- Choose Product --</option>
                            @foreach($allProductsForDropdown as $p)
                                <option value="{{ $p->id }}">{{ $p->product_name }} ({{ $p->product_code }})</option>
                            @endforeach
                        </select>
                        @error('diora_product_id') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Movement Type *</label>
                            <select wire:model="type" class="w-full rounded-lg border-gray-300 py-1.5 px-2 bg-white">
                                <option value="addition">Stock In (Addition)</option>
                                <option value="deduction">Stock Out (Deduction)</option>
                                <option value="opening">Opening Balance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Quantity *</label>
                            <input type="number" wire:model="quantity" min="1" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                            @error('quantity') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Reference No / Invoice</label>
                        <input type="text" wire:model="reference_no" placeholder="e.g. INV-2026-001" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Notes / Remarks</label>
                        <textarea wire:model="notes" rows="2" placeholder="Optional details..." class="w-full rounded-lg border-gray-300 p-2"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-semibold">Cancel</button>
                    <button wire:click="storeStock" wire:loading.attr="disabled" class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition disabled:opacity-50">
                        <svg wire:loading wire:target="storeStock" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove wire:target="storeStock">Save Stock Entry</span>
                        <span wire:loading wire:target="storeStock">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- STOCK MOVEMENT HISTORY LEDGER MODAL -->
    @if($isHistoryModalOpen && $historyProduct)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-xs p-4 overflow-y-auto" wire:click="closeHistoryModal">
            <div class="relative max-w-3xl w-full bg-white rounded-3xl shadow-2xl p-6 space-y-4 max-h-[90vh] flex flex-col" @click.stop>
                
                <!-- Modal Header -->
                <div class="flex justify-between items-center border-b pb-3">
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base">Stock Movement History</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Product: <span class="font-bold text-gray-700">{{ $historyProduct->product_name }}</span> (Code: {{ $historyProduct->product_code }})
                        </p>
                    </div>
                    <button type="button" wire:click="closeHistoryModal" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>

                <!-- History Logs Table -->
                <div class="overflow-x-auto flex-1 border rounded-2xl">
                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                        <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-[10px]">
                            <tr>
                                <th class="px-3 py-2.5 text-left">Date / Time</th>
                                <th class="px-3 py-2.5 text-center">Type</th>
                                <th class="px-3 py-2.5 text-center">Qty Change</th>
                                <th class="px-3 py-2.5 text-left">Reference / Invoice</th>
                                <th class="px-3 py-2.5 text-left">Notes / Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($productStockHistory as $history)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-3 py-2.5 text-gray-500 whitespace-nowrap">
                                        {{ $history->created_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                        @if($history->type === 'addition' || $history->type === 'opening')
                                            <span class="px-2 py-0.5 rounded-md font-bold text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                {{ ucfirst($history->type) }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-md font-bold text-[10px] bg-rose-50 text-rose-700 border border-rose-200">
                                                {{ ucfirst($history->type) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5 text-center font-extrabold whitespace-nowrap {{ $history->quantity > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $history->quantity > 0 ? '+' . $history->quantity : $history->quantity }}
                                    </td>
                                    <td class="px-3 py-2.5 font-mono text-gray-700">
                                        {{ $history->reference_no ?? 'N/A' }}
                                    </td>
                                    <td class="px-3 py-2.5 text-gray-600">
                                        {{ $history->notes ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-xs">
                                        No stock movement history found for this product.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end pt-3 border-t">
                    <button type="button" wire:click="closeHistoryModal" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                        Close History
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- FULL PRODUCT IMAGE GALLERY POPUP MODAL -->
    @if($isGalleryModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-xs p-4 overflow-y-auto" wire:click="closeGalleryModal">
            <div class="relative max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" @click.stop 
                 x-data="{ selectedImage: '{{ $selectedProductImages[0] ?? '' }}' }">
                
                <!-- Modal Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base">Product Image Gallery</h3>
                        <p class="text-[11px] text-gray-500">Click any thumbnail below to preview high-resolution angles.</p>
                    </div>
                    <button type="button" wire:click="closeGalleryModal" class="w-8 h-8 rounded-full bg-gray-200/60 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-lg font-bold transition">
                        &times;
                    </button>
                </div>

                <!-- Modal Body: Split Stage (Large Viewer + Thumbnail Selector) -->
                <div class="p-6 flex flex-col md:flex-row gap-6 overflow-y-auto bg-gray-50/50 flex-1">
                    
                    <!-- Main Active Image Preview Stage -->
                    <div class="flex-1 bg-white rounded-2xl border border-gray-200/80 p-4 flex items-center justify-center shadow-2xs relative group min-h-[320px]">
                        <img :src="selectedImage" class="max-h-[50vh] max-w-full object-contain rounded-xl transition-all duration-300">
                    </div>

                    <!-- Thumbnails Sidebar / Grid Carousel -->
                    <div class="md:w-48 flex flex-row md:flex-col gap-2.5 overflow-x-auto md:overflow-y-auto max-h-[50vh] p-1">
                        @foreach($selectedProductImages as $imgUrl)
                            <div @click="selectedImage = '{{ $imgUrl }}'" 
                                 :class="selectedImage === '{{ $imgUrl }}' ? 'ring-2 ring-indigo-600 border-transparent shadow-md scale-[1.02]' : 'border-gray-200 opacity-70 hover:opacity-100'"
                                 class="cursor-pointer bg-white rounded-xl border p-1 transition-all duration-200 flex-shrink-0">
                                <img src="{{ $imgUrl }}" class="w-20 h-20 md:w-full md:h-24 object-cover rounded-lg">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end px-6 py-4 bg-white border-t border-gray-100">
                    <button type="button" wire:click="closeGalleryModal" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                        Close Gallery
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>