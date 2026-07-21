<div x-data="{ 
    isDetailsFormOpen: false,
    isBillingFormOpen: false,
    mode: 'add',
    toggleDetailsForm(action = 'add') {
        this.isDetailsFormOpen = !this.isDetailsFormOpen;
        this.mode = action;
        if (!this.isDetailsFormOpen) {
            $wire.resetDetailsForm();
        }
    },
    toggleBillingForm(action = 'add') {
        this.isBillingFormOpen = !this.isBillingFormOpen;
        this.mode = action;
        if (!this.isBillingFormOpen) {
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
                    Manage your billing information and invoices
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0 space-x-3">
                <button type="button" 
                    @click="toggleDetailsForm('add')"
                    class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                    <svg class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ $isNewRecord ? 'Add Billing Details' : 'Update Billing Details' }}
                </button>
                @if($this->isAdmin())
                    <button type="button" 
                        @click="toggleBillingForm('add')"
                        class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        <svg class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create New Invoice
                    </button>
                @endif
            </div>
        </div>

        <!-- Billing Details Modal -->
        <div x-show="isDetailsFormOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4"
            class="mt-6 bg-white shadow rounded-lg p-4">
            
            <div class="flex justify-between items-center mb-4">
                {{-- <h3 class="text-lg font-medium text-gray-900" x-text="mode === 'add' ? 'Add Billing Details' : 'Edit Billing Details'"></h3> --}}
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $isNewRecord ? 'Add Billing Details' : 'Update Billing Details' }}
                </h3>
                <button type="button" @click="toggleDetailsForm()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="saveBillingDetails" class="space-y-4">
                <!-- billing details form fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- GST Number -->
                    <div>
                        <label for="gst_number" class="block text-sm font-medium text-gray-700">GST Number</label>
                        <input type="text" wire:model="gst_number" id="gst_number"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('gst_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Joining Date -->
                    <div>
                        <label for="joining_date" class="block text-sm font-medium text-gray-700">Joining Date</label>
                        <input type="date" wire:model="joining_date" id="joining_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('joining_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="detail_status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model="detail_status" id="detail_status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="decline">Decline</option>
                        </select>
                        @error('detail_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Billing Address -->
                    <div class="md:col-span-3">
                        <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                        <textarea wire:model="billing_address" id="billing_address" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        @error('billing_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="toggleDetailsForm()"
                        class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        Close
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        {{ $isNewRecord ? 'Save Details' : 'Update Details' }}
                    </button>
                </div>
            </form>

             @if(!$isNewRecord && $currentBillingDetail)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <p>Currently editing record created at: {{ $currentBillingDetail->created_at->format('M d, Y H:i') }}</p>
                        <p>Last updated: {{ $currentBillingDetail->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            @endif

        </div>

        <!-- Billing Master Modal -->
        @if($this->isAdmin())
            <div x-show="isBillingFormOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="mt-6 bg-white shadow rounded-lg p-4">
                
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" x-text="mode === 'add' ? 'Create New Invoice' : 'Edit Invoice'"></h3>
                    <button type="button" @click="toggleBillingForm()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="{{ $uuid ? 'updateBilling' : 'saveBilling' }}" class="space-y-4">
                
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <!-- Team Selection-->
                        <div>
                            <label for="selected_team_id" class="block text-sm font-medium text-gray-700">Select Team</label>
                            <div class="relative">
                                <select wire:model.live="selected_team_id" id="selected_team_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    {{ $viewMode ? 'disabled' : '' }}>
                                    <option value="">Select Team</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('selected_team_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Billing Details Selection -->
                        <div class="md:col-span-2">
                            <label for="billing_details_id" class="block text-sm font-medium text-gray-700">Select Billing Details</label>
                            <select wire:model.live="billing_details_id" id="billing_details_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                {{ $viewMode ? 'disabled' : '' }}>
                                <option value="">Select Details</option>
                                @foreach($billingDetails as $detail)
                                    <option value="{{ $detail->id }}">{{ $detail->gst_number }} - {{ Str::limit($detail->billing_address, 30) }}</option>
                                @endforeach
                            </select>
                            @error('billing_details_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₹</span>
                                </div>
                                <input type="number" wire:model="amount" id="amount" step="0.01"
                                    class="pl-7 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    {{ $viewMode ? 'disabled' : '' }}>
                            </div>
                            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Billing Start Date -->
                        <div>
                            <label for="billing_start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" wire:model="billing_start_date" id="billing_start_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                {{ $viewMode ? 'disabled' : '' }}>
                            @error('billing_start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Billing End Date -->
                        <div>
                            <label for="billing_end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" wire:model="billing_end_date" id="billing_end_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                {{ $viewMode ? 'disabled' : '' }}>
                            @error('billing_end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                {{ $viewMode ? 'disabled' : '' }}>
                                <option value="raised">Raised</option>
                                <option value="in_progress">In Progress</option>
                                <option value="paid">Paid</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Invoice Matter -->
                        <div class="md:col-span-2">
                            <label for="invoice_matter" class="block text-sm font-medium text-gray-700">Invoice Matter</label>
                            <textarea wire:model="invoice_matter" id="invoice_matter" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter invoice matter details"
                                {{ $viewMode ? 'disabled' : '' }}></textarea>
                            @error('invoice_matter') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>


                        <!-- Cancelled Reason (shown only when status is cancelled) -->
                        <div x-show="$wire.status === 'cancelled'" class="md:col-span-3">
                            <label for="cancelled_reason" class="block text-sm font-medium text-gray-700">Cancellation Reason</label>
                            <textarea wire:model="cancelled_reason" id="cancelled_reason" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                {{ $viewMode ? 'disabled' : '' }}></textarea>
                            @error('cancelled_reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if(!$viewMode)
                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="toggleBillingForm()"
                                class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                {{ $uuid ? 'Update Invoice' : 'Create Invoice' }}
                            </button>
                        </div>
                    @else
                        <div class="flex justify-end">
                            <button type="button" @click="toggleBillingForm()"
                                class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                Close
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        @endif

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
                        <input type="text" wire:model.live="searchQuery" placeholder="Search by GST number..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model.live="filterStatus"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">All</option>
                            <option value="raised">Raised</option>
                            <option value="in_progress">In Progress</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
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
                        class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Billing Table -->
        <div class="mt-6 bg-white shadow overflow-hidden rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Billing Details
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount & Duration
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        @if($this->isAdmin())
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                        @else
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Download Invoice
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($billings as $billing)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $billing->billingDetail->gst_number }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ Str::limit($billing->billingDetail->billing_address, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    ₹{{ number_format($billing->amount, 2) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $billing->billing_start_date->format('M d, Y') }} - 
                                    {{ $billing->billing_end_date->format('M d, Y') }}
                                </div>
                            </td>

                            <!-- Status with Dropdown for Admins -->
                            
                            <td class="px-6 py-4" x-data="{ open: false, loading: false }">
                                @if($this->isAdmin())
                                    <button @click="$event.preventDefault(); {{ $billing->status === 'paid' ? '' : 'open = !open' }}" 
                                        type="button"
                                        :disabled="loading || '{{ $billing->status }}' === 'paid'"
                                        class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-sm font-medium
                                            {{ $billing->status === 'paid' ? 'bg-green-50 text-green-700 ring-1 ring-green-600/20 cursor-not-allowed' : 
                                            ($billing->status === 'cancelled' ? 'bg-red-50 text-red-700 ring-1 ring-red-600/20' : 
                                            ($billing->status === 'in_progress' ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20' : 
                                                'bg-gray-50 text-gray-700 ring-1 ring-gray-600/20')) }}">
                                        
                                        <!-- Status Indicator Dot -->
                                        <span class="flex-shrink-0 w-1.5 h-1.5 rounded-full
                                            {{ $billing->status === 'paid' ? 'bg-green-500' :
                                            ($billing->status === 'cancelled' ? 'bg-red-500' :
                                            ($billing->status === 'in_progress' ? 'bg-blue-500' :
                                                'bg-gray-500')) }}">
                                        </span>

                                        <!-- Status Text -->
                                        <span x-show="!loading">{{ ucfirst($billing->status) }}</span>
                                        
                                        <!-- Loading Spinner -->
                                        <svg x-show="loading" class="animate-spin h-4 w-4 text-current" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        
                                        <!-- Only show dropdown arrow for non-paid status -->
                                        @if($billing->status !== 'paid')
                                            <svg class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        @endif
                                    </button>

                                    <!-- Dropdown Menu - Only show for non-paid status -->
                                    @if($billing->status !== 'paid')
                                    <div x-show="open" 
                                        @click.away="open = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute z-50 mt-2 w-56 rounded-lg bg-white shadow-xl ring-1 ring-black/5 divide-y divide-gray-100">
                                        
                                        <div class="p-1 space-y-0.5">
                                            <!-- Status Options -->
                                            @foreach(['raised' => ['gray', 'Raised'], 
                                                    'in_progress' => ['blue', 'In Progress'], 
                                                    'paid' => ['green', 'Paid'], 
                                                    'cancelled' => ['red', 'Cancelled']] as $status => $config)
                                                <button 
                                                    @click="loading = true; $wire.updateStatus('{{ $billing->uuid }}', '{{ $status }}').then(() => { 
                                                        loading = false; 
                                                        open = false;
                                                    })"
                                                    class="group flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm transition-colors duration-150
                                                        {{ $billing->status === $status 
                                                            ? 'bg-'.$config[0].'-50 text-'.$config[0].'-700' 
                                                            : 'text-gray-700 hover:bg-gray-50' }}">
                                                    
                                                    <!-- Status Indicator -->
                                                    <span class="flex items-center justify-center">
                                                        <span class="w-2 h-2 rounded-full bg-{{ $config[0] }}-500 
                                                            {{ $billing->status === $status ? 'ring-2 ring-'.$config[0].'-200 ring-offset-1' : '' }}">
                                                        </span>
                                                    </span>

                                                    <!-- Status Text -->
                                                    <span class="flex-grow font-medium">{{ $config[1] }}</span>

                                                    <!-- Active Status Check Mark -->
                                                    @if($billing->status === $status)
                                                        <svg class="h-5 w-5 text-{{ $config[0] }}-600" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" 
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" 
                                                                clip-rule="evenodd"/>
                                                        </svg>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>

                                        <!-- Loading Overlay -->
                                        <div x-show="loading" 
                                            class="absolute inset-0 flex items-center justify-center bg-white/75 rounded-lg">
                                            <svg class="animate-spin h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                <path class="opacity-75" fill="currentColor" 
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                                @else
                                    <!-- Read-only status badge -->
                                    <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-sm font-medium
                                        {{ $billing->status === 'paid' ? 'bg-green-50 text-green-700' :
                                        ($billing->status === 'cancelled' ? 'bg-red-50 text-red-700' :
                                        ($billing->status === 'in_progress' ? 'bg-blue-50 text-blue-700' :
                                            'bg-gray-50 text-gray-700')) }}">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            {{ $billing->status === 'paid' ? 'bg-green-500' :
                                            ($billing->status === 'cancelled' ? 'bg-red-500' :
                                            ($billing->status === 'in_progress' ? 'bg-blue-500' :
                                                'bg-gray-500')) }}">
                                        </span>
                                        {{ ucfirst($billing->status) }}
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <!-- Download Invoice Button with loader -->
                                    <button wire:click="downloadInvoice('{{ $billing->uuid }}')"
                                        wire:loading.attr="disabled"
                                        class="text-blue-600 hover:text-blue-900 relative"
                                        title="Download Invoice">
                                        <!-- Loading spinner -->
                                        <div wire:loading wire:target="downloadInvoice('{{ $billing->uuid }}')"
                                            class="absolute inset-0 flex items-center justify-center">
                                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <!-- Icon -->
                                        <svg wire:loading.class="opacity-0" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </button>

                                    <!-- Edit and Delete Buttons - Only visible to admin and when not paid -->
                                    @if($this->isAdmin() && $billing->status !== 'paid')
                                        <button wire:click="editBilling('{{ $billing->uuid }}')"
                                            class="text-green-600 hover:text-green-900">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        
                                        <button wire:click="deleteBilling('{{ $billing->uuid }}')"
                                            wire:confirm="Are you sure you want to delete this billing?"
                                            class="text-red-600 hover:text-red-900">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                No billings found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $billings->links() }}
            </div>
        </div>
    </div>
</div>