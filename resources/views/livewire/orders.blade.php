<div class="p-4 sm:p-6 mx-auto max-w-7xl">
    <!-- Header & Action Row -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Order Management</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400">Track customer orders, update statuses, and review complete activity audit trails.</p>
        </div>
        <a href="{{ route('storefront.order') }}" wire:navigate class="w-full sm:w-auto px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 rounded-lg shadow-sm">
            + Add New Order
        </a>
    </div>
    
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm text-xs sm:text-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Compact Filters Row -->
    <div class="mb-6 p-3 sm:p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            
            <!-- Left Side: Inputs & Selects Group -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-center gap-2.5 flex-1">
                
                <!-- General Search -->
                <div class="w-full lg:w-52">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search order #, name..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:bg-gray-700 dark:text-white text-xs">
                </div>

                <!-- Order Status -->
                <select wire:model.live="statusFilter" class="w-full lg:w-auto px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-xs focus:outline-none">
                    <option value="">Status: All</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <!-- Payment Status -->
                <select wire:model.live="paymentStatusFilter" class="w-full lg:w-auto px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-xs focus:outline-none">
                    <option value="">Payment: All</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="paid">Paid</option>
                    <option value="partial">Partial</option>
                    <option value="refunded">Refunded</option>
                </select>

                <!-- Customer Filter -->
                <select wire:model.live="customerFilter" class="w-full lg:w-auto px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-xs focus:outline-none">
                    <option value="">Customer: All</option>
                    @foreach($customersList as $custName)
                        <option value="{{ $custName }}">{{ $custName }}</option>
                    @endforeach
                </select>

                <!-- Date Range (Compact Inline) -->
                <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 col-span-full sm:col-span-2 lg:col-span-1">
                    <input wire:model.live="dateFrom" type="date" title="From Date" class="w-full px-2 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-xs focus:outline-none">
                    <span>-</span>
                    <input wire:model.live="dateTo" type="date" title="To Date" class="w-full px-2 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-xs focus:outline-none">
                </div>

            </div>

            <!-- Right Side: Reset Action -->
            <div class="flex justify-end lg:justify-start">
                @if($search || $statusFilter || $paymentStatusFilter || $dateFrom || $dateTo || $customerFilter)
                    <button wire:click="resetFilters" class="w-full lg:w-auto px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-medium transition text-center">
                        Reset Filters
                    </button>
                @endif
            </div>

        </div>
    </div>

    <!-- Orders Table / Mobile Card Layout Container -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        
        <!-- Desktop Table View (Hidden on Small Screens) -->
        <div class="hidden md:block overflow-x-auto">
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
                        <tr wire:key="order-row-{{ $order->id }}" class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4" wire:click="view({{ $order->id }})" title="View Order Details & Audit History">
                                <div class="font-bold font-mono text-indigo-600 dark:text-indigo-400">{{ $order->order_number }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ Carbon\Carbon::parse($order->created_at)->setTimezone('Asia/Kolkata')->format('d M Y, h:i:s A') }}
                                </div>
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
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : ($order->payment_status === 'partial' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400') }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                                <span class="text-[11px] text-gray-400 block mt-1 uppercase">{{ str_replace('_', ' ', $order->payment_method ?? 'cod') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-start gap-1">
                                    <select wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)" 
                                            class="text-xs font-semibold px-2.5 py-1 rounded-lg border focus:outline-none transition 
                                            {{ 
                                                match($order->status) {
                                                    'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800',
                                                    'processing' => 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                                                    'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-300 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800',
                                                    'confirmed' => 'bg-purple-100 text-purple-800 border-purple-300 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800',
                                                    'cancelled' => 'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800',
                                                    default => 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800'
                                                }
                                            }}">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <!-- SHOW PROCESS BUTTON ONLY IF STATUS IS CONFIRMED -->
                                    @if($order->status === 'processing')
                                        <a href="{{ route('order-process', ['order' => $order->id]) }}" 
                                        class="px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-medium rounded-lg transition inline-flex items-center gap-1.5 hover:bg-indigo-100 dark:hover:bg-indigo-900/50">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            Process
                                        </a>
                                    @endif
                                    
                                    <!-- VIEW ORDER DETAILS BUTTON -->
                                    <button wire:click="view({{ $order->id }})" title="View Order Details & Audit History" class="p-1.5 bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 hover:bg-sky-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>

                                    @php
                                        $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                                        $message = match($order->status) {
                                            'pending' => "Hello {$order->customer_name}, your order ({$order->order_number}) has been placed and is currently pending review. Thanks!",
                                            'confirmed' => "Hello {$order->customer_name}, your order ({$order->order_number}) has been confirmed and is now entering production tracking. Thanks!",
                                            'processing' => "Hello {$order->customer_name}, your order ({$order->order_number}) is now being processed. Thanks!",
                                            'completed' => "Hello {$order->customer_name}, your order ({$order->order_number}) has been completed successfully. Thanks for shopping with us!",
                                            'cancelled' => "Hello {$order->customer_name}, your order ({$order->order_number}) has been cancelled. Please contact us for details.",
                                            default => "Hello {$order->customer_name}, regarding your order ({$order->order_number}), current status is: " . ucfirst($order->status) . ". Thanks!"
                                        };
                                        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
                                    @endphp

                                    <!-- WHATSAPP BUTTON -->
                                    <a href="{{ $whatsappUrl }}" target="_blank" title="Send WhatsApp" class="inline-flex items-center p-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                    </a>
                                </div>
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

        <!-- Mobile Stacked Card View (Visible only on Small Screens) -->
        <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($orders as $order)
                <div wire:key="mobile-order-row-{{ $order->id }}" class="p-4 space-y-3 bg-white dark:bg-gray-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold font-mono text-indigo-600 dark:text-indigo-400 text-sm">{{ $order->order_number }}</div>
                            <div class="text-[11px] text-gray-400">
                                {{ Carbon\Carbon::parse($order->created_at)->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                            </div>
                        </div>
                        <div class="text-right font-bold text-gray-900 dark:text-white text-sm">
                            ₹{{ number_format($order->total_amount, 2) }}
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/30 p-2.5 rounded-lg text-xs space-y-1">
                        <div class="font-bold text-gray-900 dark:text-white">{{ $order->customer_name }}</div>
                        <div class="text-gray-500 dark:text-gray-400">{{ $order->customer_phone }}</div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : ($order->payment_status === 'partial' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                            <span class="text-[10px] text-gray-400 uppercase">({{ str_replace('_', ' ', $order->payment_method ?? 'cod') }})</span>
                        </div>

                        <select wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)" 
                                class="text-xs font-semibold px-2 py-1 rounded-md border focus:outline-none 
                                {{ 
                                    match($order->status) {
                                        'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'processing' => 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-900/30 dark:text-blue-400',
                                        'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-300 dark:bg-indigo-900/30 dark:text-indigo-400',
                                        'confirmed' => 'bg-purple-100 text-purple-800 border-purple-300 dark:bg-purple-900/30 dark:text-purple-400',
                                        'cancelled' => 'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-900/30 dark:text-rose-400',
                                        default => 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-900/30 dark:text-amber-400'
                                    }
                                }}">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                 
                        
                        <button wire:click="view({{ $order->id }})" class="px-3 py-1.5 bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 text-xs font-medium rounded-lg transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Details
                        </button>

                        @php
                            $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                            $message = match($order->status) {
                                'pending' => "Hello {$order->customer_name}, your order ({$order->order_number}) has been placed and is currently pending review. Thanks!",
                                'processing' => "Hello {$order->customer_name}, your order ({$order->order_number}) is now being processed. Thanks!",
                                'completed' => "Hello {$order->customer_name}, your order ({$order->order_number}) has been completed successfully. Thanks for shopping with us!",
                                'cancelled' => "Hello {$order->customer_name}, your order ({$order->order_number}) has been cancelled. Please contact us for details.",
                                default => "Hello {$order->customer_name}, regarding your order ({$order->order_number}), current status is: " . ucfirst($order->status) . ". Thanks!"
                            };
                            $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
                        @endphp

                        <a href="{{ $whatsappUrl }}" target="_blank" class="px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs font-medium rounded-lg transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    No orders found.
                </div>
            @endforelse
        </div>

    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>

    <!-- Create Order Modal -->
    @if($isOpen)
        <div wire:ignore.self class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-3 sm:p-4 overflow-y-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-3xl p-4 sm:p-6 max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Create New Order</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
                
                <form wire:submit.prevent="store">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Order Number</label>
                            <input wire:model="order_number" type="text" readonly class="mt-1 block w-full border border-gray-300 bg-gray-100 dark:bg-gray-700 dark:border-gray-600 rounded-md p-2 text-gray-600 dark:text-gray-300 cursor-not-allowed text-xs sm:text-sm">
                            @error('order_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name</label>
                            <input wire:model="customer_name" type="text" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 dark:bg-gray-700 dark:text-white text-xs sm:text-sm">
                            @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Customer Phone</label>
                            <input wire:model="customer_phone" type="text" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 dark:bg-gray-700 dark:text-white text-xs sm:text-sm">
                            @error('customer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Customer Email</label>
                            <input wire:model="customer_email" type="email" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 dark:bg-gray-700 dark:text-white text-xs sm:text-sm">
                            @error('customer_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Shipping Address</label>
                            <textarea wire:model="shipping_address" rows="2" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 dark:bg-gray-700 dark:text-white text-xs sm:text-sm"></textarea>
                            @error('shipping_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Order Items Dynamic Section -->
                    <div class="mt-6">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Order Items</h4>
                            <button type="button" wire:click="addItemRow" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded-lg font-medium shadow-sm transition">+ Add Item</button>
                        </div>
                        @error('orderItems') <span class="block text-red-500 text-xs mb-2">{{ $message }}</span> @enderror

                        <div class="space-y-3">
                            @foreach($orderItems as $index => $item)
                                <div wire:key="order-item-row-{{ $index }}" class="flex flex-col sm:flex-row items-center gap-2 bg-gray-50 dark:bg-gray-700/30 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <div class="flex-1 w-full">
                                        <select wire:model.live="orderItems.{{ $index }}.my_product_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-xs sm:text-sm dark:bg-gray-700 dark:text-white">
                                            <option value="">Select Product</option>
                                            @foreach($productsList as $product)
                                                <option value="{{ $product->id }}">{{ $product->product_name }} ({{ $product->product_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-full sm:w-28">
                                        <select wire:model.live="orderItems.{{ $index }}.unit_type" class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-xs sm:text-sm dark:bg-gray-700 dark:text-white">
                                            <option value="piece">Piece</option>
                                            <option value="box">Box</option>
                                        </select>
                                    </div>
                                    <div class="w-full sm:w-24">
                                        <input wire:model.live="orderItems.{{ $index }}.quantity" type="number" min="1" placeholder="Qty" class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-xs sm:text-sm dark:bg-gray-700 dark:text-white text-center">
                                    </div>
                                    <div class="w-full sm:w-28">
                                        <input wire:model="orderItems.{{ $index }}.price" type="number" step="0.01" placeholder="Price" class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 text-xs sm:text-sm dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div class="w-full sm:w-auto text-right">
                                        <button type="button" wire:click="removeItemRow({{ $index }})" class="text-red-500 hover:text-red-700 p-1 font-bold text-lg">&times;</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" wire:click="closeModal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="store" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-xs sm:text-sm font-medium shadow transition flex items-center gap-2">
                            <span wire:loading.remove wire:target="store">Save Order</span>
                            <span wire:loading wire:target="store">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- View Order Details Modal with Activity History Timeline -->
    @if($isViewModalOpen && $viewingOrder)
        <div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-3xl w-full p-4 sm:p-6 shadow-2xl border border-gray-200 dark:border-gray-700 max-h-[92vh] overflow-y-auto">
                
                <!-- Modal Top Header -->
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                            <span>Order Details:</span>
                            <span class="font-mono text-indigo-600 dark:text-indigo-400">{{ $viewingOrder->order_number }}</span>
                        </h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                         {{ Carbon\Carbon::parse($viewingOrder->created_at)->setTimezone('Asia/Kolkata')->format('d M Y, h:i:s A') }}
                        </span>
                    </div>
                    <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-2xl font-semibold focus:outline-none">&times;</button>
                </div>

                <div class="space-y-4 text-xs sm:text-sm">
                    
                    <!-- Customer & Shipping Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700/30 p-3.5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <div>
                            <span class="text-[11px] text-gray-400 dark:text-gray-400 block font-bold uppercase tracking-wider mb-1">Customer Details</span>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $viewingOrder->customer_name }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">{{ $viewingOrder->customer_phone }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-300">{{ $viewingOrder->customer_email ?? 'No email provided' }}</p>
                        </div>
                        <div>
                            <span class="text-[11px] text-gray-400 dark:text-gray-400 block font-bold uppercase tracking-wider mb-1">Shipping Address</span>
                            <p class="text-xs text-gray-800 dark:text-gray-200 whitespace-pre-line leading-relaxed">{{ $viewingOrder->shipping_address }}</p>
                        </div>
                    </div>

                    <!-- Payment & Status Options Bar -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-100 dark:bg-gray-700/50 p-3.5 rounded-xl gap-3 border border-gray-200 dark:border-gray-600">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">Payment Method</span>
                            <span class="font-semibold text-xs uppercase text-gray-900 dark:text-white">{{ str_replace('_', ' ', $viewingOrder->payment_method ?? 'cod') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1 font-medium">Payment Status</span>
                            <select wire:change="updatePaymentStatus({{ $viewingOrder->id }}, $event.target.value)" class="text-xs font-semibold px-2.5 py-1.5 rounded-lg border dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:outline-none">
                                <option value="unpaid" {{ $viewingOrder->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $viewingOrder->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ $viewingOrder->payment_status === 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="refunded" {{ $viewingOrder->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">Current Order Status</span>
                            <span class="inline-block mt-0.5 px-2.5 py-1 text-xs font-bold uppercase rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">
                                {{ ucfirst($viewingOrder->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Items Table -->
                    <div>
                        <h4 class="font-bold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Ordered Items</h4>
                        <div class="border rounded-xl overflow-x-auto dark:border-gray-700 shadow-sm">
                            <table class="w-full text-left text-xs min-w-[500px]">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    <tr>
                                        <th class="p-3">Product / Key / Code</th>
                                        <th class="p-3 text-center">Unit Type</th>
                                        <th class="p-3 text-center">Qty</th>
                                        <th class="p-3 text-right">Unit Price</th>
                                        <th class="p-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-200">
                                    @foreach($viewingOrder->items as $item)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20">
                                            <td class="p-3">
                                                <div class="font-bold text-gray-900 dark:text-white">{{ $item->product_name }}</div>
                                                <div class="text-[10px] font-mono text-gray-500 dark:text-gray-400">
                                                    {{ $item->sku }} @if(!empty($item->model_key)) • Key: {{ $item->model_key }} @endif
                                                </div>
                                            </td>
                                            <td class="p-3 text-center">
                                                <span class="px-2 py-0.5 text-[11px] font-semibold rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase">
                                                    {{ $item->unit_type ?? 'Piece' }}
                                                </span>
                                            </td>
                                            <td class="p-3 text-center font-bold text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                                            <td class="p-3 text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="p-3 text-right font-bold text-gray-900 dark:text-white">₹{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totals Breakdown -->
                    <div class="flex justify-end pt-1">
                        <div class="w-full sm:w-60 space-y-1.5 text-xs bg-gray-50 dark:bg-gray-700/30 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between text-gray-600 dark:text-gray-300"><span>Subtotal:</span><span>₹{{ number_format($viewingOrder->subtotal, 2) }}</span></div>
                            <div class="flex justify-between text-gray-600 dark:text-gray-300"><span>Tax / GST:</span><span>₹{{ number_format($viewingOrder->tax_amount, 2) }}</span></div>
                            <div class="flex justify-between font-bold text-sm text-gray-900 dark:text-white border-t border-gray-200 dark:border-gray-600 pt-1.5">
                                <span>Grand Total:</span>
                                <span class="text-emerald-600 dark:text-emerald-400">₹{{ number_format($viewingOrder->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($viewingOrder->notes)
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wider block font-bold mb-1">Customer Order Notes</span>
                            <p class="text-xs bg-gray-50 dark:bg-gray-700/30 p-3 rounded-xl border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200">{{ $viewingOrder->notes }}</p>
                        </div>
                    @endif

                    <!-- ACTIVITY & STATUS HISTORY AUDIT LOG TIMELINE -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Activity & Status History
                            </h4>
                            <span class="text-[11px] text-gray-400">
                                {{ isset($viewingOrder->activities) ? $viewingOrder->activities->count() : 0 }} records
                            </span>
                        </div>

                        @if(isset($viewingOrder->activities) && $viewingOrder->activities->count() > 0)
                            <div class="relative pl-4 border-l-2 border-indigo-200 dark:border-indigo-800 space-y-3 max-h-56 overflow-y-auto pr-1">
                                @foreach($viewingOrder->activities as $activity)
                                    <div wire:key="activity-log-{{ $activity->id }}" class="relative group">
                                        <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-indigo-600 border-2 border-white dark:border-gray-800 ring-2 ring-indigo-200 dark:ring-indigo-900"></div>

                                        <div class="bg-gray-50 dark:bg-gray-700/40 p-2.5 rounded-xl border border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between sm:items-center gap-1">
                                            <div class="space-y-0.5">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded {{ $activity->activity_type === 'created' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : ($activity->activity_type === 'payment_status_update' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300') }}">
                                                        {{ str_replace('_', ' ', $activity->activity_type) }}
                                                    </span>
                                                    <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                        {{ $activity->description }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="text-[10px] font-mono text-gray-400 dark:text-gray-400 shrink-0">
                                                {{ Carbon\Carbon::parse($activity->created_at)->setTimezone('Asia/Kolkata')->format('d M Y, h:i:s A') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 bg-gray-50 dark:bg-gray-700/20 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                                <p class="text-xs text-gray-400 italic">No activity history recorded for this order yet.</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" wire:click="closeViewModal" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-xs font-semibold rounded-xl transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>