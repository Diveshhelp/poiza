<div class="py-6 mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">Buff Price Management</h2>
            <p class="text-xs text-gray-500 mt-0.5">Configure and update item part rates (per piece or per inch) seamlessly.</p>
        </div>
        <div class="flex items-center gap-3">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search products..." 
                class="rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 w-64 py-2"
            >
            <button 
                wire:click="saveAllPrices" 
                wire:loading.attr="disabled"
                class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition whitespace-nowrap disabled:opacity-50">
                
                <!-- Loading Spinner -->
                <svg wire:loading wire:target="saveAllPrices" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <span wire:loading.remove wire:target="saveAllPrices">Save All Changes</span>
                <span wire:loading wire:target="saveAllPrices">Saving changes...</span>
            </button>
        </div>
    </div>

    <!-- Flash Message Notification -->
    @if (session()->has('message'))
        <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-3.5 rounded-xl text-xs text-emerald-800 shadow-sm flex items-center justify-between">
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Main Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold">Product Information</th>
                        <th class="px-4 py-3 text-center font-bold">Finish</th>
                        <th class="px-4 py-3 text-center font-bold">Total Parts</th>
                        <th class="px-4 py-3 text-left font-bold">Piece-by-Piece Pricing Layout</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        @php
                            $totalPieces = (int) ($product->piece ?? $product->packing ?? 1);
                            $totalPieces = $totalPieces > 0 ? $totalPieces : 1;
                        @endphp

                        <tr class="hover:bg-gray-50/50 transition">
                            <!-- Product Name & Codes -->
                            <td class="px-4 py-3 align-top w-1/4">
                                <div class="font-bold text-gray-900 text-xs">{{ $product->product_name }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">Code: <span class="text-gray-600 font-medium">{{ $product->product_code }}</span></div>
                                <div class="text-[11px] text-gray-400">Model: <span class="text-gray-600 font-medium">{{ $product->model_key ?? 'N/A' }}</span></div>
                            </td>

                            <!-- Finish Badge -->
                            <td class="px-4 py-3 align-top text-center whitespace-nowrap">
                                <span class="inline-block text-[11px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                    {{ $product->finish ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- Parts Count -->
                            <td class="px-4 py-3 align-top text-center whitespace-nowrap">
                                <span class="inline-block text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ $totalPieces }} Parts
                                </span>
                            </td>

                            <!-- Inline Grid Inputs for Parts -->
                            <td class="px-4 py-3 align-middle">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
                                    @for($i = 1; $i <= $totalPieces; $i++)
                                        <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-200 flex items-center justify-between gap-2 shadow-2xs">
                                            <div class="text-[11px] font-bold text-gray-700 whitespace-nowrap">
                                                Part {{ $i }}
                                            </div>
                                            
                                            <div class="flex items-center gap-1.5 justify-end">
                                                <select 
                                                    wire:model="prices.{{ $product->id }}.{{ $i }}.pricing_type" 
                                                    class="text-[10px] rounded border-gray-300 py-1 px-1 bg-white font-medium text-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <option value="piece">Piece</option>
                                                    <option value="inch">Inch</option>
                                                </select>

                                                <input 
                                                    type="number" step="0.01" 
                                                    wire:model="prices.{{ $product->id }}.{{ $i }}.price_per_piece" 
                                                    class="w-20 text-center rounded border-gray-300 text-xs py-1 px-1 bg-white focus:ring-indigo-500 focus:border-indigo-500 font-semibold text-gray-800"
                                                    placeholder="0.00"
                                                >
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-gray-400 text-xs">
                                No products found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Footer Save Bar -->
        <div class="bg-gray-50/75 p-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="w-full sm:w-auto text-xs">
                {{ $products->links() }}
            </div>
            <button 
                wire:click="saveAllPrices" 
                wire:loading.attr="disabled"
                class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition disabled:opacity-50">
                Save All Changes
            </button>
        </div>
    </div>
</div>