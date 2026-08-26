<div class="p-6 max-w-7xl mx-auto">
    <!-- Header & Action Row -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Product Inventory</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage hardware items, SKUs, wholesale pricing, and stock limits.</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- BUFF PRICES LINK BUTTON -->
   
            <button wire:click="openCsvModal" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import CSV
            </button>
            <button wire:click="create" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Product
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-lg shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search Box -->
    <div class="mb-4 flex items-center justify-between">
        <div class="w-full md:w-1/3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, SKU, category..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 uppercase text-xs font-semibold tracking-wider border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-3">Product / SKU</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Pricing (Cost / Sell)</th>
                        <th class="px-6 py-3">Stock Levels</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-200">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $product->name }}</div>
                                <div class="text-xs font-mono text-indigo-600 dark:text-indigo-400 mt-0.5">{{ $product->sku }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">{{ $product->category->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-500">Cost: ₹{{ number_format($product->cost_price, 2) }}</div>
                                <div class="font-semibold text-gray-900 dark:text-white">Sell: ₹{{ number_format($product->selling_price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold {{ $product->stock_quantity <= $product->alert_quantity ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $product->stock_quantity }} {{ $product->unit }}
                                </span>
                                @if($product->stock_quantity <= $product->alert_quantity)
                                    <span class="block text-[10px] text-red-500 font-medium">Low Stock Alert</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                <button wire:click="view({{ $product->id }})" title="View Details" class="p-1.5 bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button wire:click="edit({{ $product->id }})" title="Edit Product" class="p-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="toggleStatus({{ $product->id }})" title="Toggle Status" class="p-1.5 {{ $product->status === 'active' ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100' }} rounded-lg transition">
                                    @if($product->status === 'active')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </button>
                                <button wire:click="delete({{ $product->id }})" title="Delete Product" onclick="confirm('Are you sure you want to delete this product?') || event.stopImmediatePropagation()" class="p-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $products->links() }}
        </div>
    </div>

    <!-- CSV Import Modal -->
    @if($isCsvModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Import Products via CSV</h3>
                    <button wire:click="closeCsvModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl">&times;</button>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Need a template?</span>
                        <button wire:click="exportSampleCsv" wire:loading.attr="disabled" class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium underline flex items-center gap-1">
                            <span wire:loading.remove wire:target="exportSampleCsv">Download Sample CSV</span>
                            <span wire:loading wire:target="exportSampleCsv">Generating...</span>
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="importCsv" class="space-y-4">
                    <div>
                        <input type="file" wire:model="csvFile" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-gray-700 dark:file:text-white">
                        @error('csvFile') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div wire:loading wire:target="csvFile" class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">
                        Uploading file preview...
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeCsvModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-md transition shadow flex items-center gap-2">
                            <span wire:loading.remove wire:target="importCsv">Upload & Import</span>
                            <span wire:loading wire:target="importCsv">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- View Product Details Modal -->
    @if($isViewModalOpen && $viewingProduct)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl max-w-xl w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Product Details</h3>
                    <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl">&times;</button>
                </div>

                <div class="space-y-4 text-sm">
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">UUID</span>
                            <span class="font-mono text-xs text-gray-800 dark:text-gray-200">{{ $viewingProduct->uuid }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">Status</span>
                            <span class="font-semibold {{ $viewingProduct->status === 'active' ? 'text-emerald-600' : 'text-red-600' }}">{{ ucfirst($viewingProduct->status) }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Product Name</span>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $viewingProduct->name }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">SKU Code</span>
                            <p class="font-mono font-semibold text-indigo-600 dark:text-indigo-400">{{ $viewingProduct->sku }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Category</span>
                            <p class="text-gray-800 dark:text-gray-200 font-medium">{{ $viewingProduct->category->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Cost Price</span>
                            <p class="text-gray-800 dark:text-gray-200 font-semibold">₹{{ number_format($viewingProduct->cost_price, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Selling Price</span>
                            <p class="text-gray-900 dark:text-white font-semibold">₹{{ number_format($viewingProduct->selling_price, 2) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Stock Qty</span>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $viewingProduct->stock_quantity }} {{ $viewingProduct->unit }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Alert Limit</span>
                            <p class="text-gray-800 dark:text-gray-200">{{ $viewingProduct->alert_quantity }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Unit Type</span>
                            <p class="uppercase text-xs font-semibold text-indigo-600 dark:text-indigo-400 mt-1">{{ $viewingProduct->unit }}</p>
                        </div>
                    </div>

                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Description</span>
                        <p class="text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-700/20 p-2 rounded text-xs">{{ $viewingProduct->description ?? 'No description provided.' }}</p>
                    </div>
                </div>

                <div class="flex justify-end pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" wire:click="closeViewModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition">Close</button>
                </div>
            </div>
        </div>
    @endif
<!-- Quick Add Category Modal -->
@if($isCategoryModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl max-w-sm w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add New Category</h3>
                <button wire:click="closeCategoryModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl">&times;</button>
            </div>

            <form wire:submit.prevent="storeCategory" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Category Name *</label>
                    <input type="text" wire:model="newCategoryName" placeholder="e.g. Mortise Handles" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('newCategoryName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea wire:model="newCategoryDescription" rows="2" placeholder="Optional category details..." class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    @error('newCategoryDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" wire:click="closeCategoryModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition">Cancel</button>
                    <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition shadow flex items-center gap-2">
                        <span wire:loading.remove wire:target="storeCategory">Save Category</span>
                        <span wire:loading wire:target="storeCategory">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
    <!-- Create / Edit Modal -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $isEditMode ? 'Edit Product' : 'Add New Product' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl">&times;</button>
                </div>

                <form wire:submit.prevent="store" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Product Name *</label>
                            <input type="text" wire:model="name" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">SKU Code *</label>
                            <input type="text" wire:model="sku" placeholder="e.g. DIORA-MH-01" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 uppercase">
                            @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <button type="button" wire:click="openCategoryModal" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add New Category
                                </button>
                            </div>
                            <select wire:model="category_id" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Unit Type</label>
                            <select wire:model="unit" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="pcs">Pieces (pcs)</option>
                                <option value="pair">Pair</option>
                                <option value="box">Box</option>
                                <option value="set">Set</option>
                                <option value="kg">Kilogram (kg)</option>
                            </select>
                            @error('unit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cost Price (₹)</label>
                            <input type="number" step="0.01" wire:model="cost_price" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('cost_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Selling Price (₹)</label>
                            <input type="number" step="0.01" wire:model="selling_price" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('selling_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Stock Quantity</label>
                            <input type="number" wire:model="stock_quantity" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('stock_quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Alert Quantity</label>
                            <input type="number" wire:model="alert_quantity" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('alert_quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select wire:model="status" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea wire:model="description" rows="2" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition shadow flex items-center gap-2">
                            <span wire:loading.remove wire:target="store">Save Product</span>
                            <span wire:loading wire:target="store">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>