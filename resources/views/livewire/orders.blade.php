<div class="p-6 max-w-7xl mx-auto">
    <!-- Header & Action Row -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Order Management</h2>
        <!-- <button wire:click="create" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
            + Add New Order
        </button> -->
    </div>
    
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-lg shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search & Status Filter Row -->
    <div class="mb-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="w-full md:w-1/3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by order number, customer name, phone..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:bg-gray-800 dark:text-white text-sm">
        </div>
        <div class="w-full md:w-auto flex items-center gap-2">
            <select wire:model.live="statusFilter" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white text-sm focus:outline-none">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 uppercase text-xs font-semibold tracking-wider border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-3">Order ID & Date</th>
                        <th class="px-6 py-3">Customer Info</th>
                        <th class="px-6 py-3">Total Amount</th>
                        <th class="px-6 py-3">Payment</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-200">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold font-mono text-indigo-600 dark:text-indigo-400">{{ $order->order_number }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $order->customer_name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                                <span class="text-[11px] text-gray-400 block mt-1 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <select wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)" class="text-xs font-semibold px-2 py-1 rounded-lg border dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                <button wire:click="view({{ $order->id }})" title="View Order Details" class="p-1.5 bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 hover:bg-sky-100 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Create Order Modal -->
        @if($isOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-2xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto shadow-2xl">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Create New Order</h3>
                    
                    <form wire:submit.prevent="store">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Order Number</label>
                                <input wire:model="order_number" type="text" readonly class="mt-1 block w-full border border-gray-300 bg-gray-100 rounded-md p-2 text-gray-600 cursor-not-allowed">
                                @error('order_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                                <input wire:model="customer_name" type="text" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                                @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Customer Phone</label>
                                <input wire:model="customer_phone" type="text" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                                @error('customer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Customer Email</label>
                                <input wire:model="customer_email" type="email" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                                @error('customer_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                <textarea wire:model="shipping_address" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></textarea>
                                @error('shipping_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Order Items Dynamic Section -->
                        <div class="mt-6">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-sm font-bold text-gray-700 uppercase">Order Items</h4>
                                <button type="button" wire:click="addItemRow" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded font-medium">+ Add Item</button>
                            </div>
                            @error('orderItems') <span class="block text-red-500 text-xs mb-2">{{ $message }}</span> @enderror

                            <div class="space-y-3">
                                @foreach($orderItems as $index => $item)
                                    <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded-xl border border-gray-200">
                                        <div class="flex-1">
                                            <select wire:model.live="orderItems.{{ $index }}.my_product_id" class="w-full border border-gray-300 rounded-md p-2 text-sm">
                                                <option value="">Select Product</option>
                                                @foreach($productsList as $product)
                                                    <option value="{{ $product->id }}">{{ $product->product_name }} ({{ $product->product_code }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-24">
                                            <input wire:model.live="orderItems.{{ $index }}.quantity" type="number" min="1" placeholder="Qty" class="w-full border border-gray-300 rounded-md p-2 text-sm">
                                        </div>
                                        <div class="w-28">
                                            <input wire:model="orderItems.{{ $index }}.price" type="number" step="0.01" placeholder="Price" class="w-full border border-gray-300 rounded-md p-2 text-sm">
                                        </div>
                                        <div>
                                            <button type="button" wire:click="removeItemRow({{ $index }})" class="text-red-500 hover:text-red-700 p-2 font-bold">&times;</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">Cancel</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">Save Order</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    <!-- View Order Details Modal -->
    @if($isViewModalOpen && $viewingOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Order Details: <span class="font-mono text-indigo-600">{{ $viewingOrder->order_number }}</span></h3>
                        <span class="text-xs text-gray-500">{{ $viewingOrder->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                    <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl">&times;</button>
                </div>

                <div class="space-y-4 text-sm">
                    <!-- Customer & Shipping Summary -->
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block font-semibold">Customer Details</span>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $viewingOrder->customer_name }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-300">{{ $viewingOrder->customer_phone }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-300">{{ $viewingOrder->customer_email ?? 'No email provided' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block font-semibold">Shipping Address</span>
                            <p class="text-xs text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $viewingOrder->shipping_address }}</p>
                        </div>
                    </div>

                    <!-- Payment & Status Options Bar -->
                    <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-700/50 p-3 rounded-lg">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">Payment Method</span>
                            <span class="font-semibold text-xs uppercase">{{ str_replace('_', ' ', $viewingOrder->payment_method) }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Payment Status</span>
                            <select wire:change="updatePaymentStatus({{ $viewingOrder->id }}, $event.target.value)" class="text-xs font-semibold px-2 py-1 rounded border dark:bg-gray-800 dark:text-white">
                                <option value="unpaid" {{ $viewingOrder->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $viewingOrder->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ $viewingOrder->payment_status === 'partial' ? 'selected' : '' }}>Partial</option>
                            </select>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">Order Status</span>
                            <span class="font-bold uppercase text-xs text-indigo-600 dark:text-indigo-400">{{ ucfirst($viewingOrder->status) }}</span>
                        </div>
                    </div>

                    <!-- Order Items Table -->
                    <div>
                        <h4 class="font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase mb-2">Ordered Items</h4>
                        <div class="border rounded-lg overflow-hidden dark:border-gray-700">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    <tr>
                                        <th class="p-2.5">Product / SKU</th>
                                        <th class="p-2.5 text-center">Qty</th>
                                        <th class="p-2.5 text-right">Unit Price</th>
                                        <th class="p-2.5 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y dark:divide-gray-700">
                                    @foreach($viewingOrder->items as $item)
                                        <tr>
                                            <td class="p-2.5">
                                                <div class="font-bold text-gray-900 dark:text-white">{{ $item->product_name }}</div>
                                                <div class="text-[10px] font-mono text-gray-500">{{ $item->sku }}</div>
                                            </td>
                                            <td class="p-2.5 text-center font-semibold">{{ $item->quantity }}</td>
                                            <td class="p-2.5 text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="p-2.5 text-right font-bold">₹{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totals Breakdown -->
                    <div class="flex justify-end pt-2">
                        <div class="w-48 space-y-1 text-xs">
                            <div class="flex justify-between"><span>Subtotal:</span><span>₹{{ number_format($viewingOrder->subtotal, 2) }}</span></div>
                            <div class="flex justify-between"><span>GST Tax:</span><span>₹{{ number_format($viewingOrder->tax_amount, 2) }}</span></div>
                            <div class="flex justify-between font-bold text-sm text-gray-900 dark:text-white border-t pt-1"><span>Total:</span><span>₹{{ number_format($viewingOrder->total_amount, 2) }}</span></div>
                        </div>
                    </div>

                    @if($viewingOrder->notes)
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block font-semibold">Customer Notes</span>
                            <p class="text-xs bg-gray-50 dark:bg-gray-700/20 p-2 rounded text-gray-800 dark:text-gray-200">{{ $viewingOrder->notes }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" wire:click="closeViewModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition">Close</button>
                </div>
            </div>
        </div>
    @endif
</div>