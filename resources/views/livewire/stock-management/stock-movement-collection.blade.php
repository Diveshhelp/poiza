<div>
    <div class="mx-auto py-5 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-medium text-gray-900">{{ $moduleTitle }}</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Track and manage product stock movements
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <button wire:click="create" 
                    class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 rounded-md">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Stock Movement
                </button>
            </div>
        </div>

        <!-- Stock Movement Modal -->
        <div x-data="{ show: @entangle('showModal') }">
            <div x-show="show" 
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            </div>

            <div x-show="show" 
            class="fixed inset-0 z-10 overflow-y-auto"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>

                <form wire:submit="save" class="w-full">
                    <h3 class="text-base font-medium text-gray-900 mb-3">
                    {{ $uuid ? 'Edit Stock Movement' : 'Add New Stock Movement' }}
                    </h3>

                    <div class="space-y-4">
                    <!-- Product Selection -->
                    @if(!$selectedProduct)
                        <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Product</label>
                        <select wire:model="product_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('product_id') border-red-300 @enderror">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->product_name }} (Current Stock: {{ $product->quantity }})
                            </option>
                            @endforeach
                        </select>
                        @error('product_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-700">{{ $selectedProduct->product_name }}</h4>
                        <p class="text-sm text-gray-500">Current Stock: {{ $selectedProduct->quantity }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Movement Type -->
                        <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Movement Type</label>
                        <select wire:model="type" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('type') border-red-300 @enderror">
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                        </select>
                        @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Quantity -->
                        <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" wire:model="stock" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('stock') border-red-300 @enderror">
                        @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                        <textarea wire:model="notes" rows="3" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('notes') border-red-300 @enderror"></textarea>
                        @error('notes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    </div>

                    <div class="mt-4 flex justify-end space-x-3">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        {{ $uuid ? 'Update Stock' : 'Add Stock' }}
                    </button>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>

        <!-- Stock Movements Table -->
        <div class="mt-6 bg-white shadow overflow-hidden rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantity
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Notes
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stockMovements as $movement)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $movement->product->product_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $movement->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($movement->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $movement->stock }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $movement->notes ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit('{{ $movement->uuid }}')" class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="delete('{{ $movement->uuid }}')" 
                                    wire:confirm="Are you sure you want to delete this stock movement?"
                                    class="ml-3 text-red-600 hover:text-red-900">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No stock movements found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $stockMovements->links() }}
            </div>
        </div>
    </div>
</div>