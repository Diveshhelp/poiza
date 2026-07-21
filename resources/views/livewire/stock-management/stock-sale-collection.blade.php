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
                    Manage your sales and transactions
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <button type="button" 
                    @click="toggleForm('add')"
                    class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 rounded-md">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add New Sale
                </button>
            </div>
        </div>

        <!-- Sale Form -->
        <div x-show="isFormOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4"
            class="mt-6 bg-white shadow rounded-lg p-4">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" x-text="mode === 'add' ? 'Add New Sale' : 'Edit Sale'"></h3>
                <button type="button" @click="toggleForm()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit="{{ $uuid ? 'updateSale' : 'saveSale' }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Product Selection -->
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                        <select wire:model.live="product_id" id="product_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->product_name }} (Stock: {{ $product->quantity }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Customer Selection -->
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                        <select wire:model="customer_id" id="customer_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Sale Date -->
                    <div>
                        <label for="sale_date" class="block text-sm font-medium text-gray-700">Sale Date</label>
                        <input type="date" wire:model="sale_date" id="sale_date" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('sale_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" wire:model.live="price" id="price" step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" wire:model.live="quantity" id="quantity" min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @if($availableStock)
                            <span class="text-xs text-gray-500">Available: {{ $availableStock }}</span>
                        @endif
                        @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Total Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                        <input type="text" readonly value="{{ number_format($total_amount ?? 0, 2) }}"
                            class="mt-1 block w-full rounded-md bg-gray-50 border-gray-300 shadow-sm sm:text-sm">
                    </div>

                    <!-- Invoice Number -->
                    <div>
                        <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                        <input type="text" wire:model="invoice_number" id="invoice_number"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('invoice_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <select wire:model="payment_status" id="payment_status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                        @error('payment_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Paid Amount -->
                    <div>
                        <label for="paid_amount" class="block text-sm font-medium text-gray-700">Paid Amount</label>
                        <input type="number" wire:model="paid_amount" id="paid_amount" step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('paid_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="draft">Draft</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea wire:model="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="toggleForm()"
                        class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 rounded-md">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 rounded-md">
                        {{ $uuid ? 'Update Sale' : 'Create Sale' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Filters -->
        <div x-data="{ isFilterOpen: false }" class="mt-6">
            <button @click="isFilterOpen = !isFilterOpen" type="button"
                class="w-full bg-white shadow rounded-lg px-4 py-3 text-left text-sm font-medium text-gray-700 flex justify-between items-center">
                <span class="flex items-center">
                    <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                </span>
                <svg class="h-5 w-5 transform transition-transform duration-300"
                    :class="{ 'rotate-180': isFilterOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="isFilterOpen" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="mt-2 bg-white shadow rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" wire:model.live="searchQuery" placeholder="Search sales..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model.live="filterStatus"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">All</option>
                            <option value="draft">Draft</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <select wire:model.live="filterPaymentStatus"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date From</label>
                        <input type="date" wire:model.live="filterDateFrom"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date To</label>
                        <input type="date" wire:model.live="filterDateTo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button wire:click="resetFilters"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="mt-6 bg-white shadow overflow-hidden rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sale Details
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
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
                    @forelse($sales as $sale)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $sale->product->product_name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Invoice: {{ $sale->invoice_number ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Date: {{ $sale->sale_date->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $sale->customer->name }}</div>
                                <div class="text-sm text-gray-500">Qty: {{ $sale->quantity }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    ₹{{ number_format($sale->total_amount, 2) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Paid: ₹{{ number_format($sale->paid_amount, 2) }}
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    {{ $sale->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                                       ($sale->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($sale->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' :
                                       ($sale->status === 'draft' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <button wire:click="editSale('{{ $sale->uuid }}')" @click="toggleForm('edit')"
                                    class="text-indigo-600 hover:text-indigo-900"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg></button>
                                <button wire:click="deleteSale('{{ $sale->uuid }}')"
                                    wire:confirm="Are you sure you want to delete this sale?"
                                    class="ml-3 text-red-600 hover:text-red-900"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No sales found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>