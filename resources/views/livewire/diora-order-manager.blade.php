<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">Diora Order Management</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage customer orders, track fulfillment stages, and sync inventory automatically.</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="statusFilter" class="rounded-xl border-gray-300 text-xs py-2 bg-white font-medium text-gray-600">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirm">Confirm</option>
                <option value="process">Process</option>
                <option value="ready_for_dispatch">Ready for Dispatch</option>
                <option value="dispatched">Dispatched</option>
                <option value="done">Done</option>
            </select>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search order #, customer..." 
                class="rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 w-52 py-2"
            >
            <button 
                wire:click="openModal" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition whitespace-nowrap">
                + New Order
            </button>
        </div>
    </div>

    <!-- Success Message Banner -->
    @if (session()->has('message'))
        <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-3.5 rounded-xl text-xs text-emerald-800 shadow-sm flex items-center justify-between">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Error Message Banner -->
    @if (session()->has('error'))
        <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 p-3.5 rounded-xl text-xs text-rose-800 shadow-sm flex items-center justify-between">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold">Order Details</th>
                        <th class="px-4 py-3 text-left font-bold">Customer & Company</th>
                        <th class="px-4 py-3 text-center font-bold">Total Amount</th>
                        <th class="px-4 py-3 text-center font-bold">Fulfillment Status</th>
                        <th class="px-4 py-3 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                        @php
                            $statusColors = [
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'confirm' => 'bg-sky-50 text-sky-700 border-sky-200',
                                'process' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'ready_for_dispatch' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'dispatched' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900 text-xs">{{ $order->order_no }}</div>
                                <div class="text-[11px] text-gray-400">Date: <span class="text-gray-600 font-medium">{{ $order->order_date }}</span></div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900 text-xs">{{ $order->customer->customer_name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-gray-400">Co: <span class="text-gray-600 font-medium">{{ $order->customer->company_name ?? 'N/A' }}</span></div>
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-gray-900 text-xs">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <select wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)" 
                                        class="text-[11px] font-bold rounded-lg border py-1 px-2 {{ $statusColors[$order->status] ?? 'bg-gray-50 text-gray-700' }}">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirm" {{ $order->status == 'confirm' ? 'selected' : '' }}>Confirm</option>
                                    <option value="process" {{ $order->status == 'process' ? 'selected' : '' }}>Process</option>
                                    <option value="ready_for_dispatch" {{ $order->status == 'ready_for_dispatch' ? 'selected' : '' }}>Ready for Dispatch</option>
                                    <option value="dispatched" {{ $order->status == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                                    <option value="done" {{ $order->status == 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <button wire:click="view({{ $order->id }})" title="View Order" class="p-1.5 bg-sky-50 text-sky-600 hover:bg-sky-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button wire:click="delete({{ $order->id }})" onclick="confirm('Are you sure you want to delete this order and restore its inventory?') || event.stopImmediatePropagation()" title="Delete Order" class="p-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-400 text-xs">
                                No orders found. Click "+ New Order" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 text-xs">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- CREATE ORDER MODAL --><!-- CREATE ORDER MODAL -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-6 space-y-4 my-8">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-base">Create New Order</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
                </div>

                <!-- Modal Error Banner -->
                @if (session()->has('modal_error'))
                    <div class="bg-rose-50 border-l-4 border-rose-500 p-3 rounded-xl text-xs text-rose-800 shadow-sm">
                        <span class="font-bold">Error:</span> {{ session('modal_error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Select Customer *</label>
                        <select wire:model="diora_customer_id" class="w-full rounded-lg border-gray-300 py-1.5 px-2 bg-white">
                            <option value="">-- Choose Customer --</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}">{{ $cust->customer_name }} ({{ $cust->company_name ?? 'Individual' }})</option>
                            @endforeach
                        </select>
                        @error('diora_customer_id') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Order Date *</label>
                        <input type="date" wire:model="order_date" class="w-full rounded-lg border-gray-300 py-1.5 px-2">
                        @error('order_date') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Initial Status</label>
                        <select wire:model="status" class="w-full rounded-lg border-gray-300 py-1.5 px-2 bg-white">
                            <option value="pending">Pending (No Stock Deduct)</option>
                            <option value="confirm">Confirm (Deducts Stock)</option>
                            <option value="process">Process (Deducts Stock)</option>
                        </select>
                    </div>

                    <!-- Dynamic Order Items Repeater -->
                    <div class="sm:col-span-3 space-y-3 border-t pt-3">
                        <div class="flex justify-between items-center">
                            <label class="font-bold text-gray-800 text-xs uppercase tracking-wider">Order Items</label>
                            <button type="button" wire:click="addOrderItem" class="px-2.5 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-semibold">
                                + Add Product Item
                            </button>
                        </div>

                        @foreach($orderItems as $index => $item)
                            <div class="flex items-center gap-2 bg-gray-50 p-3 rounded-xl border border-gray-200">
                                <div class="flex-1">
                                    <select wire:model.live="orderItems.{{ $index }}.product_id" class="w-full rounded-lg border-gray-300 py-1 px-2 bg-white text-xs">
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->product_name }} (Code: {{ $prod->product_code }}) - ₹{{ $prod->price }}</option>
                                        @endforeach
                                    </select>
                                    @error("orderItems.{$index}.product_id") <span class="text-rose-500 text-[10px] block mt-0.5">Required</span> @enderror
                                </div>
                                <div class="w-20">
                                    <input type="number" wire:model="orderItems.{{ $index }}.quantity" min="1" placeholder="Qty" class="w-full rounded-lg border-gray-300 py-1 text-center text-xs">
                                </div>
                                <div class="w-24">
                                    <input type="number" step="0.01" wire:model="orderItems.{{ $index }}.price" placeholder="Price" class="w-full rounded-lg border-gray-300 py-1 text-center text-xs">
                                </div>
                                <button type="button" wire:click="removeOrderItem({{ $index }})" class="p-1 text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                            </div>
                        @endforeach
                        @error('orderItems') <span class="text-rose-500 text-[10px] block">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block font-bold text-gray-700 mb-1">Order Notes / Remarks</label>
                        <textarea wire:model="notes" rows="2" placeholder="Optional notes..." class="w-full rounded-lg border-gray-300 p-2"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-semibold">Cancel</button>
                    <button wire:click="storeOrder" wire:loading.attr="disabled" class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition disabled:opacity-50">
                        <svg wire:loading wire:target="storeOrder" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove wire:target="storeOrder">Place Order</span>
                        <span wire:loading wire:target="storeOrder">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
    <!-- VIEW ORDER DETAILS MODAL -->
    @if($isViewModalOpen && $viewOrder)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">Order Invoice #{{ $viewOrder->order_no }}</h3>
                        <p class="text-[11px] text-gray-500">Placed on {{ $viewOrder->order_date }}</p>
                    </div>
                    <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3.5 rounded-2xl border">
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Customer Name</span>
                            <span class="font-bold text-gray-900 text-sm">{{ $viewOrder->customer->customer_name ?? 'N/A' }}</span>
                            <div class="text-[11px] text-gray-500">Co: {{ $viewOrder->customer->company_name ?? 'N/A' }}</div>
                            <div class="text-[11px] text-gray-500">Phone: {{ $viewOrder->customer->customer_phone ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Shipping Address</span>
                            <p class="text-gray-700 font-medium">{{ $viewOrder->customer->shipping_address ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    <div class="border rounded-2xl overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-[10px]">
                                <tr>
                                    <th class="px-3 py-2 text-left">Product</th>
                                    <th class="px-3 py-2 text-center">Qty</th>
                                    <th class="px-3 py-2 text-right">Price</th>
                                    <th class="px-3 py-2 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($viewOrder->items as $row)
                                    <tr>
                                        <td class="px-3 py-2 font-medium text-gray-900">
                                            {{ $row->product->product_name ?? 'Product' }}
                                            <div class="text-[10px] text-gray-400">Code: {{ $row->product->product_code ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-3 py-2 text-center">{{ $row->quantity }}</td>
                                        <td class="px-3 py-2 text-right">₹{{ number_format($row->price, 2) }}</td>
                                        <td class="px-3 py-2 text-right font-bold">₹{{ number_format($row->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-center bg-indigo-50 p-3.5 rounded-2xl border border-indigo-100">
                        <span class="font-bold text-indigo-900 text-xs uppercase">Grand Total Amount</span>
                        <span class="font-extrabold text-indigo-700 text-base">₹{{ number_format($viewOrder->total_amount, 2) }}</span>
                    </div>

                    @if($viewOrder->notes)
                        <div class="bg-gray-50 p-3 rounded-xl border">
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Notes</span>
                            <p class="text-gray-700">{{ $viewOrder->notes }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end pt-3 border-t">
                    <button wire:click="closeViewModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-semibold">Close</button>
                </div>
            </div>
        </div>
    @endif
</div>