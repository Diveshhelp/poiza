<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">Diora Product Catalog</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage stainless steel mortise handles, hardware, finishes, and multiple photo galleries.</p>
        </div>
        <div class="flex items-center gap-3">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search products..." 
                class="rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 w-64 py-2"
            >
            <button 
                wire:click="openModal" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition whitespace-nowrap">
                + Add Product
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-3.5 rounded-xl text-xs text-emerald-800 shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Products Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold">Image</th>
                        <th class="px-4 py-3 text-left font-bold">Product Details</th>
                        <th class="px-4 py-3 text-center font-bold">Finish / Material</th>
                        <th class="px-4 py-3 text-center font-bold">Parts & Packing</th>
                        <th class="px-4 py-3 text-right font-bold">Price</th>
                        <th class="px-4 py-3 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition">
                            <!-- Display First Image in List -->
                          <!-- Update your image thumbnail cell inside the <table> loop -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->images && count($product->images) > 0)
                                        <button type="button" wire:click="showImage('{{ Storage::url($product->images[0]) }}')" class="group relative focus:outline-hidden">
                                            <img src="{{ Storage::url($product->images[0]) }}" class="w-12 h-12 rounded-xl object-cover border border-gray-200 shadow-2xs transition group-hover:opacity-80 group-hover:scale-105">
                                            <span class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 rounded-xl transition text-white">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                            </span>
                                        </button>
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-[10px] text-gray-400 font-medium">No Image</div>
                                    @endif
                                </div>
                            </td>

                            <!-- Product Name & Codes -->
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900 text-xs">{{ $product->product_name }}</div>
                                <div class="text-[11px] text-gray-400">Code: <span class="text-gray-600 font-medium">{{ $product->product_code }}</span></div>
                                <div class="text-[11px] text-gray-400">Model: <span class="text-gray-600 font-medium">{{ $product->model_key ?? 'N/A' }}</span></div>
                            </td>

                            <!-- Finish & Material -->
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block text-[11px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 mb-1">
                                    {{ $product->finish ?? 'Standard' }}
                                </span>
                                <div class="text-[10px] text-gray-500">{{ $product->material ?? 'Stainless Steel' }}</div>
                            </td>

                            <!-- Parts & Packing -->
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ $product->piece }} Parts / {{ $product->packing }} Pack
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="px-4 py-3 text-right font-bold text-gray-900 text-xs">
                                ₹{{ number_format($product->price, 2) }}
                            </td>

                            <!-- Actions (Icon Buttons) -->
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <!-- Edit Icon Button -->
                                    <button wire:click="edit({{ $product->id }})" title="Edit Product" class="p-1.5 bg-sky-50 text-sky-600 hover:bg-sky-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Delete Icon Button -->
                                    <button wire:click="delete({{ $product->id }})" onclick="confirm('Are you sure you want to delete this product?') || event.stopImmediatePropagation()" title="Delete Product" class="p-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-xs">
                                No Diora products found. Click "Add Product" to create one.
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

    <!-- MODAL FOR CREATE / EDIT -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 space-y-4 my-8">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-base">{{ $isEditMode ? 'Edit Diora Product' : 'Add New Diora Product' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Product Name *</label>
                        <input type="text" wire:model="product_name" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                        @error('product_name') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Product Code / SKU *</label>
                        <input type="text" wire:model="product_code" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                        @error('product_code') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Finish (e.g. Matt, Glossy)</label>
                        <input type="text" wire:model="finish" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Material</label>
                        <input type="text" wire:model="material" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Size / Dimensions</label>
                        <input type="text" wire:model="size" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Model Key</label>
                        <input type="text" wire:model="model_key" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Parts Count (Pieces)</label>
                        <input type="number" wire:model="piece" min="1" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Packing Count</label>
                        <input type="number" wire:model="packing" min="1" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Price (₹)</label>
                        <input type="number" step="0.01" wire:model="price" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-gray-700 mb-1">Upload Multiple Images</label>
                        <input type="file" wire:model="images" multiple class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        
                        <!-- Preview Existing Images -->
                        @if($existingImages)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($existingImages as $index => $img)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($img) }}" class="w-14 h-14 rounded-lg object-cover border">
                                        <button type="button" wire:click="removeExistingImage({{ $index }})" class="absolute -top-1.5 -right-1.5 bg-rose-600 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px]">×</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Preview New Uploads -->
                        @if ($images)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach ($images as $image)
                                    <img src="{{ $image->temporaryUrl() }}" class="w-14 h-14 rounded-lg object-cover border">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-semibold">Cancel</button>
                    
                    <!-- Save Button with Loading Spinner -->
                    <button wire:click="store" wire:loading.attr="disabled" class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition disabled:opacity-50">
                        <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="store">Save Product</span>
                        <span wire:loading wire:target="store">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
    <!-- ENLARGED IMAGE LIGHTBOX MODAL -->
    @if($enlargedImage)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-xs p-4" wire:click="closeImageModal">
            <div class="relative max-w-4xl max-h-[90vh] p-2 bg-white rounded-2xl shadow-2xl overflow-hidden" @click.stop>
                <button type="button" wire:click="closeImageModal" class="absolute top-3 right-3 bg-gray-900/60 hover:bg-gray-900 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold z-10 transition">
                    &times;
                </button>
                <img src="{{ $enlargedImage }}" class="max-w-full max-h-[80vh] object-contain rounded-xl mx-auto block shadow-sm">
            </div>
        </div>
    @endif
</div>