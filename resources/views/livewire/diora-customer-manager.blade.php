<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">Diora Customers Management</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage customer directory, wholesalers, billing/shipping details, and contacts.</p>
        </div>
        <div class="flex items-center gap-3">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search customers, company..." 
                class="rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 w-64 py-2"
            >
            <button 
                wire:click="openModal" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition whitespace-nowrap">
                + Add Customer
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-3.5 rounded-xl text-xs text-emerald-800 shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Customers Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold">Customer & Company</th>
                        <th class="px-4 py-3 text-left font-bold">Contact Info</th>
                        <th class="px-4 py-3 text-center font-bold">Location</th>
                        <th class="px-4 py-3 text-center font-bold">GSTIN</th>
                        <th class="px-4 py-3 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50/50 transition">
                            <!-- Name & Company -->
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900 text-xs">{{ $customer->customer_name }}</div>
                                <div class="text-[11px] text-gray-400">Co: <span class="text-gray-600 font-medium">{{ $customer->company_name ?? 'N/A' }}</span></div>
                            </td>

                            <!-- Contact Info -->
                            <td class="px-4 py-3">
                                <div class="text-xs text-gray-800 font-medium">{{ $customer->customer_phone ?? 'N/A' }}</div>
                                <div class="text-[11px] text-gray-400">{{ $customer->customer_email ?? 'N/A' }}</div>
                            </td>

                            <!-- Location -->
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded">
                                    {{ $customer->city ? $customer->city . ', ' : '' }}{{ $customer->state ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- GSTIN -->
                            <td class="px-4 py-3 text-center text-xs font-mono text-gray-600">
                                {{ $customer->gstin ?? 'N/A' }}
                            </td>

                            <!-- Actions (Icons) -->
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <!-- View Details Button -->
                                    <button wire:click="view({{ $customer->id }})" title="View Details" class="p-1.5 bg-sky-50 text-sky-600 hover:bg-sky-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>

                                    <!-- Edit Button -->
                                    <button wire:click="edit({{ $customer->id }})" title="Edit Customer" class="p-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <button wire:click="delete({{ $customer->id }})" onclick="confirm('Are you sure you want to delete this customer?') || event.stopImmediatePropagation()" title="Delete Customer" class="p-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-400 text-xs">
                                No Diora customers found. Click "+ Add Customer" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 text-xs">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- CREATE / EDIT MODAL -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-xl max-w-xl w-full p-6 space-y-4 my-8">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-base">{{ $isEditMode ? 'Edit Diora Customer' : 'Add New Diora Customer' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Customer Name *</label>
                        <input type="text" wire:model="customer_name" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                        @error('customer_name') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Company / Firm Name</label>
                        <input type="text" wire:model="company_name" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Phone Number</label>
                        <input type="text" wire:model="customer_phone" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Email Address</label>
                        <input type="email" wire:model="customer_email" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">City</label>
                        <input type="text" wire:model="city" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">State</label>
                        <input type="text" wire:model="state" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-gray-700 mb-1">GSTIN</label>
                        <input type="text" wire:model="gstin" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-gray-700 mb-1">Billing Address</label>
                        <textarea wire:model="billing_address" rows="2" class="w-full rounded-lg border-gray-300 p-2"></textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-gray-700 mb-1">Shipping Address</label>
                        <textarea wire:model="shipping_address" rows="2" class="w-full rounded-lg border-gray-300 p-2"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-semibold">Cancel</button>
                    <button wire:click="store" wire:loading.attr="disabled" class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition disabled:opacity-50">
                        <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove wire:target="store">Save Customer</span>
                        <span wire:loading wire:target="store">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- VIEW DETAILS MODAL -->
    @if($isViewModalOpen && $viewCustomer)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-base">Customer Profile Overview</h3>
                    <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-xl border">
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Customer Name</span>
                            <span class="font-bold text-gray-900">{{ $viewCustomer->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Company Name</span>
                            <span class="font-semibold text-gray-700">{{ $viewCustomer->company_name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-xl border">
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Phone</span>
                            <span class="font-semibold text-gray-700">{{ $viewCustomer->customer_phone ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Email</span>
                            <span class="font-semibold text-gray-700">{{ $viewCustomer->customer_email ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 bg-gray-50 p-3 rounded-xl border">
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">City</span>
                            <span class="font-semibold text-gray-700">{{ $viewCustomer->city ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">State</span>
                            <span class="font-semibold text-gray-700">{{ $viewCustomer->state ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">GSTIN</span>
                            <span class="font-mono text-gray-700">{{ $viewCustomer->gstin ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-xl border space-y-1">
                        <span class="text-gray-400 block text-[10px] font-bold uppercase">Billing Address</span>
                        <p class="text-gray-700 font-medium">{{ $viewCustomer->billing_address ?? 'Not specified' }}</p>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-xl border space-y-1">
                        <span class="text-gray-400 block text-[10px] font-bold uppercase">Shipping Address</span>
                        <p class="text-gray-700 font-medium">{{ $viewCustomer->shipping_address ?? 'Not specified' }}</p>
                    </div>
                </div>

                <div class="flex justify-end pt-3 border-t">
                    <button wire:click="closeViewModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-semibold">Close</button>
                </div>
            </div>
        </div>
    @endif
</div>