<div x-data="{ 
    isFormOpen: false,
    mode: 'add',
    toggleForm(action = 'add') {
        this.isFormOpen = !this.isFormOpen;
        this.mode = action;
        if (!this.isFormOpen) {
            $wire.resetForm();
        }
    }
}">
    <div class="mx-auto py-5 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-medium text-gray-900">{{ $moduleTitle }}</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Manage your products inventory and track stock levels
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                {{-- <button type="button" 
                wire:click="toggleForm"
                class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 rounded-md">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add New Product
                </button> --}}
                <button type="button" 
                    @click="isFormOpen = !isFormOpen; $wire.resetForm()"
                    class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 rounded-md">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add New Product
                </button>
            </div>
        </div>
        

        <!-- Quick Add/Edit Form -->
        
        {{-- <div x-data="{ show: @entangle('isFormOpen') }">
        <div x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4"
            class="mt-6 bg-white shadow rounded-lg p-4">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $uuid ? 'Edit Product' : 'Add New Product' }}
                </h3>
                <button type="button" wire:click="toggleForm" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>     --}}
        <div x-show="isFormOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4"
            class="mt-6 bg-white shadow rounded-lg p-4">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" x-text="mode === 'add' ? 'Add New Product' : 'Edit Product'"></h3>
                <button type="button" @click="toggleForm()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>    
            <form wire:submit="{{ $uuid ? 'updateProduct' : 'saveProduct' }}" class="w-full">
                {{-- <h3 class="text-base font-medium text-gray-900 mb-3">
                    {{ $uuid ? 'Edit Product' : 'Add New Product' }}
                </h3> --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Product Name -->
                    <div>
                        <label for="product_name" class="block text-xs font-medium text-gray-700 mb-1">Product Name</label>
                        <input type="text" wire:model="product_name" id="product_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                            @error('product_name') border-red-300 @enderror">
                        @error('product_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                        <select wire:model="category_id" id="category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                            @error('category_id') border-red-300 @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-xs font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" wire:model="sku" id="sku"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                            @error('sku') border-red-300 @enderror">
                        @error('sku')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-xs font-medium text-gray-700 mb-1">Price</label>
                        <input type="number" wire:model="price" id="price" step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                            @error('price') border-red-300 @enderror">
                        @error('price')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" wire:model="quantity" id="quantity"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                            @error('quantity') border-red-300 @enderror">
                        @error('quantity')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                            @error('status') border-red-300 @enderror">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-4">
                    <label for="description" class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" id="description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                        @error('description') border-red-300 @enderror"></textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Images -->
                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Product Images</label>
                    <input type="file" wire:model="images" multiple
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('images.*')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <!-- Existing Images -->
                    @if(!empty($existingImages))
                        <div class="mt-2 grid grid-cols-4 gap-4">
                            @foreach($existingImages as $index => $imageName)
                                <div class="relative">
                                    @if($currentProduct)
                                        <img src="{{ $currentProduct->getImageUrl($imageName) }}" 
                                            alt="Product image" 
                                            class="h-24 w-24 object-cover rounded-lg"
                                            onerror="this.src='{{ asset('images/placeholder.png') }}'">
                                    @endif
                                    <button type="button" wire:click="removeImage({{ $index }})"
                                        class="absolute -top-2 -right-2 bg-red-100 text-red-600 rounded-full p-1 hover:bg-red-200">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="mt-4 flex justify-end space-x-3">
                    @if($uuid)
                        <button type="button" wire:click="resetForm"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Cancel
                        </button>
                    @endif
                    <button type="submit"
                        class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        {{ $uuid ? 'Update Product' : 'Add Product' }}
                    </button>
                </div>
            </form>
        </div>
        {{-- </div> --}}

        <!-- Include the same Filters section as in stock-category-collection.blade.php -->
        {{-- @include('livewire.stock-category.partials.filters') --}}

        <!-- Products Table -->
        <div class="mt-6 bg-white shadow overflow-hidden rounded-lg">
            <div class="px-4 py-4 sm:px-6 flex items-center justify-between bg-gray-50 border-b border-gray-200">
                <h3 class="text-base font-medium text-gray-900">Products List</h3>
                <div class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> to
                    <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> of
                    <span class="font-medium">{{ $products->total() ?? 0 }}</span> results
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            SKU
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantity
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if(!empty($product->images))
                                        <img src="{{ $product->getImageUrl($product->images[0]) }}" 
                                            alt="{{ $product->product_name }}"
                                            class="h-10 w-10 rounded-full object-cover mr-3"
                                            onerror="this.src='{{ asset('images/placeholder.png') }}'">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="text-sm font-medium text-gray-900">{{ $product->product_name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $product->category->category_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $product->sku ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ₹{{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $product->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    
                                    <a href="{{ route('stock-movements', $product->uuid) }}"
                                        class="text-indigo-600 hover:text-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-full p-1"
                                        title="Manage Stock">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </a>
                                    
                                    {{-- <button wire:click="editProduct('{{ $product->uuid }}')"
                                        class="text-blue-600 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full p-1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button> --}}
                                    <button wire:click="editProduct('{{ $product->uuid }}')"
                                        @click="toggleForm('edit')"
                                        class="text-blue-600 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full p-1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="deleteProduct('{{ $product->uuid }}')"
                                        wire:confirm="Are you sure you want to delete this product?"
                                        class="text-red-600 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 rounded-full p-1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Get started by creating a new product using the form above.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>