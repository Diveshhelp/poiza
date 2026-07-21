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
                    Manage your customers and their information
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <button type="button" 
                    @click="isFormOpen = !isFormOpen; $wire.resetForm()"
                    class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 rounded-md">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add New Customer
                </button>
            </div>
        </div>

        <!-- Customer Form -->
        
            <div x-show="isFormOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="mt-6 bg-white shadow rounded-lg p-4">
                
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" x-text="mode === 'add' ? 'Add New Customer' : 'Edit Customer'"></h3>
                    <button type="button" @click="toggleForm()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="{{ $uuid ? 'updateCustomer' : 'saveCustomer' }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" wire:model="name" id="name" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" wire:model="email" id="email" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" wire:model="phone" id="phone" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-3">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea wire:model="address" id="address" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" wire:model="city" id="city" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                            <input type="text" wire:model="state" id="state" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('state') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                            <input type="text" wire:model="postal_code" id="postal_code" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('postal_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" wire:model="country" id="country" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('country') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status" id="status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-3">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea wire:model="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="toggleForm()"
                            class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                            {{ $uuid ? 'Update Customer' : 'Add Customer' }}
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
                        <input type="text" wire:model.live="searchQuery" placeholder="Search customers..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model.live="filterStatus"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" wire:model.live="filterCity"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">State</label>
                        <input type="text" wire:model.live="filterState"
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

        <!-- Customers Table -->
        <div class="mt-6 bg-white shadow overflow-hidden rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Location
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
                    @forelse($customers as $customer)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                <div class="text-sm text-gray-500">Added {{ $customer->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                                <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $customer->city }}, {{ $customer->state }}</div>
                                <div class="text-sm text-gray-500">{{ $customer->country }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <button wire:click="editCustomer('{{ $customer->uuid }}')" @click="toggleForm('edit')"
                                    class="text-indigo-600 hover:text-indigo-900"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg></button>
                                <button wire:click="deleteCustomer('{{ $customer->uuid }}')"
                                    wire:confirm="Are you sure you want to delete this customer?"
                                    class="ml-3 text-red-600 hover:text-red-900"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No customers found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>