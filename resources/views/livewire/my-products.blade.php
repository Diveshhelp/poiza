<div class="p-6 mx-auto">
    <!-- Header & Action Row -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Product Management</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage products, variants, finishes, and bulk CSV imports.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="openCsvModal" class="px-3 py-2 text-white bg-primary dark:bg-secondary rounded-lg text-xs md:text-sm font-semibold hover:opacity-90 transition">
                Import CSV
            </button>
            <button wire:click="create" class="px-3 py-2 text-white bg-primary dark:bg-secondary rounded-lg text-xs md:text-sm font-semibold hover:opacity-90 transition">
                Add Product
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, code, alias, category..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
    </div>

    <!-- Data Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Product Name / Alias</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Code / Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Finish / Size</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Piece</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse ($myProducts as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="Product Image" wire:click="openImageModal('{{ Storage::url($product->image) }}')" class="h-10 w-10 rounded-lg object-cover cursor-pointer hover:opacity-80 transition-opacity shadow-sm">
                            @else
                                <span class="text-xs text-gray-400">No Image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $product->product_name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product->product_alias }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            <span class="block font-mono text-xs font-bold text-gray-700 dark:text-gray-200">{{ $product->product_code }}</span>
                            <span class="text-xs text-indigo-600 dark:text-indigo-400">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            <div>{{ $product->finish ?? '-' }}</div>
                            <div class="text-xs text-gray-400">{{ $product->size ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $product->piece ?? 1 }} Piece</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button wire:click="view({{ $product->id }})" title="View Details" class="p-1.5 bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 hover:bg-sky-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            <button wire:click="edit({{ $product->id }})" title="Edit Product" class="p-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="delete({{ $product->id }})" title="Delete Product" onclick="confirm('Are you sure you want to delete this product?') || event.stopImmediatePropagation()" class="p-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 bg-gray-50 border-t dark:bg-gray-700 dark:border-gray-600">
            {{ $myProducts->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-xl p-6 max-h-screen overflow-y-auto">
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">{{ $isEditMode ? 'Edit Product' : 'Add New Product' }}</h3>
                
                <form wire:submit.prevent="store">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Name</label>
                            <input wire:model="product_name" type="text" class="mt-1 block w-full border rounded-md p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('product_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Code</label>
                            <input wire:model="product_code" type="text" readonly class="mt-1 block w-full border rounded-md p-2 bg-gray-100 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                            @error('product_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Alias</label>
                            <input wire:model="product_alias" type="text" class="mt-1 block w-full border rounded-md p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('product_alias') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
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
<!-- Finish, Size, and Pieces in a single row -->
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Finish</label>
                                <input wire:model="finish" type="text" class="mt-1 block w-full border rounded-md p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('finish') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Size</label>
                                <input wire:model="size" type="text" class="mt-1 block w-full border rounded-md p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('size') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Of Pieces</label>
                                <input wire:model="piece" type="number" min="1" class="mt-1 block w-full border rounded-md p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('piece') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Image</label>
                            <input wire:model="image" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            
                            @if ($image && is_object($image))
                                <div class="mt-3 flex items-center space-x-3">
                                    <img src="{{ $image->temporaryUrl() }}" class="h-20 w-20 object-cover rounded-lg border shadow-sm">
                                    <button type="button" wire:click="$set('image', null)" class="text-xs text-red-600 hover:text-red-800 font-medium">Clear Upload</button>
                                </div>
                            @elseif($image && is_string($image))
                                <div class="mt-3 flex items-center space-x-3">
                                    <img src="{{ Storage::url($image) }}" class="h-20 w-20 object-cover rounded-lg border shadow-sm">
                                    <button type="button" wire:click="removeImage" onclick="confirm('Are you sure you want to delete this image?') || event.stopImmediatePropagation()" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-md text-xs font-semibold transition-colors">
                                        Remove Image
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" wire:click="closeModal" class="bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-md text-sm">Cancel</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm">Save Product</button>
                    </div>
                </form>
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
                        <button type="button" wire:click="closeCategoryModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:text-white text-gray-800 text-sm font-medium rounded-md transition">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition shadow flex items-center gap-2">
                            <span wire:loading.remove wire:target="storeCategory">Save Category</span>
                            <span wire:loading wire:target="storeCategory">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- View Details Modal -->
    @if($isViewModalOpen && $viewingMyProduct)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity">
            <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transform transition-all">
                <div class="bg-white dark:bg-gray-800 px-6 py-4 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white tracking-wide">Product Overview</h3>
                    <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 shadow-sm">
                        <div class="flex-shrink-0">
                            @if($viewingMyProduct->image)
                                <img src="{{ Storage::url($viewingMyProduct->image) }}" class="h-20 w-20 object-cover rounded-xl border-2 border-white shadow-md">
                            @else
                                <div class="h-20 w-20 bg-indigo-50 text-indigo-400 rounded-xl border-2 border-white shadow-md flex items-center justify-center text-xs font-medium">
                                    No Image
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white truncate">{{ $viewingMyProduct->product_name }}</h4>
                            <p class="text-sm font-mono text-indigo-600 dark:text-indigo-400 font-semibold">{{ $viewingMyProduct->product_code }}</p>
                            @if($viewingMyProduct->product_alias)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Alias: <span class="italic text-gray-700 dark:text-gray-300">{{ $viewingMyProduct->product_alias }}</span></p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-gray-100 dark:border-gray-600">
                            <span class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Category</span>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $viewingMyProduct->category->name ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-gray-100 dark:border-gray-600">
                            <span class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Finish</span>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $viewingMyProduct->finish ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-gray-100 dark:border-gray-600">
                            <span class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Size</span>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $viewingMyProduct->size ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-gray-100 dark:border-gray-600">
                            <span class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Pieces</span>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $viewingMyProduct->piece ?? 1 }}</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">System UUID</label>
                        <div class="bg-gray-100 dark:bg-gray-900 px-3 py-2 rounded-lg font-mono text-xs text-gray-600 dark:text-gray-300 break-all select-all">
                            {{ $viewingMyProduct->uuid }}
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex justify-end border-t border-gray-100 dark:border-gray-600">
                    <button wire:click="closeViewModal" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2 rounded-xl text-sm font-medium shadow-sm transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Enlarge Image Preview Modal -->
    @if($isImageModalOpen && $previewingImage)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 backdrop-blur-sm transition-opacity" wire:click="closeImageModal">
            <div class="relative max-w-3xl max-h-[90vh] p-2" wire:click.stop>
                <button wire:click="closeImageModal" class="absolute -top-3 -right-3 bg-white text-gray-800 rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <img src="{{ $previewingImage }}" class="max-h-[85vh] max-w-full rounded-2xl shadow-2xl object-contain border-4 border-white">
            </div>
        </div>
    @endif

    <!-- CSV Import/Export Modal -->
    @if($isCsvModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-md p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Import/Export Products CSV</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Download Template</label>
                    <button wire:click="exportSampleCsv" class="bg-gray-500 hover:bg-gray-600 text-white text-xs px-3 py-2 rounded">
                        Download Sample CSV
                    </button>
                </div>

                <form wire:submit.prevent="importCsv">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload CSV File</label>
                        <input wire:model="csvFile" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('csvFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="closeCsvModal" class="bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-md text-sm">Cancel</button>
                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md text-sm">Import CSV</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>