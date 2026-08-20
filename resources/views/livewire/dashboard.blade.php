<div class="p-5 dark:bg-dark-bg dark:border dark:border-gray-600 rounded-xl bg-white shadow-xl transition-all duration-300 space-y-6">
    
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm text-xs sm:text-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Top Header with Prominent Brand Logo & Welcome Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center w-full gap-4 pb-5 border-b border-gray-100 dark:border-gray-700">
        
        <!-- Left Side: Prominent Brand Logo & Welcome Message -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full lg:w-auto">
            <!-- Highly Visible Brand Logo Box -->
            <div class="px-4 py-2.5 bg-white dark:bg-slate-800 rounded-2xl shadow-md border-2 border-indigo-500/20 dark:border-indigo-500/40 shrink-0 flex items-center justify-center">
                <img src="{{ asset('logo-.png') }}" alt="Diora Enterprise Logo" class="h-14 sm:h-16 w-auto object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div style="display: none;" class="w-12 h-12 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white items-center justify-center font-black text-xl shadow">D</div>
            </div>

            <!-- Welcome Text & Status -->
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-extrabold text-lg sm:text-xl text-gray-900 dark:text-white tracking-tight">Welcome back, {{ auth()->user()->name ?? 'Valued User' }}! 👋</h1>
                    <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">Online</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Here is what's happening with your business operations today.</p>
            </div>
        </div>

        <!-- Right Side: ERP Monitoring Badge -->
        <div class="flex items-center gap-3 w-full lg:w-auto justify-end">
            <div class="flex items-center gap-2.5 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-pink-500/10 dark:bg-dark-bg/65 backdrop-blur-md px-4 py-2 rounded-xl border border-indigo-500/20 shadow-inner">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500"></span>
                </span>
                <span class="font-bold text-xs uppercase tracking-wider text-indigo-700 dark:text-indigo-300">ERP Monitoring Live</span>
            </div>
        </div>
    </div>

    <!-- Main Banner Content with Rich Design -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 rounded-xl p-6 sm:p-8 text-white shadow-lg">
        <!-- Decorative background glow blobs -->
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-purple-500/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-blue-500/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
            <div class="lg:col-span-2 space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 backdrop-blur-md border border-white/20 text-indigo-200">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Enterprise Control Center
                </div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Streamline Your Orders & Production Flow</h2>
                <p class="text-sm text-indigo-100/90 leading-relaxed max-w-2xl">
                    Welcome to your comprehensive Enterprise Resource Planning (ERP) tracking system. Monitor incoming orders, analyze live production pipelines, oversee material allocation, and manage every moving part of your business securely from a single centralized dashboard.
                </p>
            </div>

            <!-- Quick Action Box / Pending Orders Preview Card -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-5 border border-white/15 flex flex-col justify-between space-y-4 shadow-xl">
                <div>
                    <span class="text-xs uppercase tracking-wider text-amber-300 font-medium">Action Needed</span>
                    <h3 class="text-lg font-bold text-white mt-1">{{ $pendingOrdersCount }} Pending Orders</h3>
                    <p class="text-xs text-indigo-200/80 mt-1">Review incoming pending orders awaiting your confirmation.</p>
                </div>
                <div class="pt-2 border-t border-white/10 flex justify-between items-center text-xs">
                    <span class="text-indigo-200">Requires processing</span>
                    <a href="{{ route('orders') }}" wire:navigate class="font-bold text-amber-300 hover:underline">View All &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Recent Pending Orders Section -->
    @if(isset($pendingOrders) && count($pendingOrders) > 0)
        <div class="bg-gradient-to-br from-amber-50/50 via-white to-gray-50 dark:from-gray-800/60 dark:via-gray-800 dark:to-gray-900 rounded-2xl p-5 border border-amber-200/60 dark:border-gray-700 shadow-sm">
            
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Recent Pending Orders Awaiting Review</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Action required to confirm or process these customer requests.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 rounded-full text-xs font-bold">
                    {{ count($pendingOrders) }} Pending
                </span>
            </div>

            <!-- Orders Grid / List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($pendingOrders as $order)
                    <div wire:key="dashboard-pending-{{ $order->id }}" class="group bg-white dark:bg-gray-800/90 p-4 rounded-xl border border-gray-200/80 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500/50 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-3">
                        
                        <!-- Top Info -->
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="font-mono font-extrabold text-indigo-600 dark:text-indigo-400 text-sm tracking-tight block">{{ $order->order_number }}</span>
                                <span class="text-gray-800 dark:text-gray-200 font-semibold text-xs mt-0.5 block">{{ $order->customer_name }}</span>
                                <span class="text-gray-400 dark:text-gray-500 text-[11px] block">{{ $order->customer_phone }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-extrabold text-gray-900 dark:text-white text-sm block">₹{{ number_format($order->total_amount, 2) }}</span>
                                <span class="inline-block mt-1 px-2 py-0.5 text-[10px] uppercase font-bold rounded bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                    {{ $order->payment_status }}
                                </span>
                            </div>
                        </div>

                        <!-- Footer: Date & Review Button -->
                        <div class="flex items-center justify-between pt-2.5 border-t border-gray-100 dark:border-gray-700 text-xs">
                            <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400 text-[11px]">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ Carbon\Carbon::parse($order->created_at)->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</span>
                            </div>
                            
                            <button wire:click="reviewOrder({{ $order->id }})" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-sm transition flex items-center gap-1 text-xs">
                                Review Details &rarr;
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Comprehensive Review Order Details Modal -->
    @if($isViewModalOpen && $viewingOrder)
        <div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-4xl w-full p-4 sm:p-6 shadow-2xl border border-gray-200 dark:border-gray-700 max-h-[92vh] overflow-y-auto">
                
                <!-- Modal Top Header -->
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                                <span>Reviewing Order:</span>
                                <span class="font-mono text-indigo-600 dark:text-indigo-400">{{ $viewingOrder->order_number }}</span>
                            </h3>
                            <span class="px-2 py-0.5 text-[11px] font-bold rounded uppercase bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                {{ ucfirst($viewingOrder->status) }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 block">
                            Placed on: {{ Carbon\Carbon::parse($viewingOrder->created_at)->setTimezone('Asia/Kolkata')->format('d M Y, h:i:s A') }}
                        </span>
                    </div>
                    <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-2xl font-semibold focus:outline-none">&times;</button>
                </div>

                <div class="space-y-4 text-xs sm:text-sm">
                    
                    <!-- Customer & Shipping Summary Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700/30 p-3.5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <div>
                            <span class="text-[11px] text-gray-400 dark:text-gray-400 block font-bold uppercase tracking-wider mb-1">Customer Details</span>
                            <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $viewingOrder->customer_name }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">📞 {{ $viewingOrder->customer_phone }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">✉️ {{ $viewingOrder->customer_email ?? 'No email provided' }}</p>
                        </div>
                        <div>
                            <span class="text-[11px] text-gray-400 dark:text-gray-400 block font-bold uppercase tracking-wider mb-1">Shipping Address</span>
                            <p class="text-xs text-gray-800 dark:text-gray-200 whitespace-pre-line leading-relaxed">{{ $viewingOrder->shipping_address ?? 'No address provided' }}</p>
                        </div>
                    </div>

                    <!-- Payment & Status Management Options Bar -->
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
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1 font-medium">Update Order Status</span>
                            <select wire:change="updateOrderStatus({{ $viewingOrder->id }}, $event.target.value)" class="text-xs font-semibold px-2.5 py-1.5 rounded-lg border dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:outline-none">
                                <option value="pending" {{ $viewingOrder->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $viewingOrder->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $viewingOrder->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $viewingOrder->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ $viewingOrder->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $viewingOrder->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <!-- Ordered Items Table -->
                    <div>
                        <h4 class="font-bold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Ordered Items Breakdown</h4>
                        <div class="border rounded-xl overflow-x-auto dark:border-gray-700 shadow-sm">
                            <table class="w-full text-left text-xs min-w-[500px]">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    <tr>
                                        <th class="p-3">Product Name / SKU</th>
                                        <th class="p-3 text-center">Unit Type</th>
                                        <th class="p-3 text-center">Quantity</th>
                                        <th class="p-3 text-right">Unit Price</th>
                                        <th class="p-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-200">
                                    @foreach($viewingOrder->items as $item)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20">
                                            <td class="p-3">
                                                <div class="font-bold text-gray-900 dark:text-white">{{ $item->product_name }}</div>
                                                <div class="text-[10px] font-mono text-gray-500 dark:text-gray-400">SKU: {{ $item->sku }}</div>
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
                        <div class="w-full sm:w-60 space-y-1.5 text-xs bg-gray-50 dark:bg-gray-700/30 p-3.5 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between text-gray-600 dark:text-gray-300"><span>Subtotal:</span><span>₹{{ number_format($viewingOrder->subtotal, 2) }}</span></div>
                            <div class="flex justify-between text-gray-600 dark:text-gray-300"><span>Tax / GST:</span><span>₹{{ number_format($viewingOrder->tax_amount, 2) }}</span></div>
                            <div class="flex justify-between font-bold text-sm text-gray-900 dark:text-white border-t border-gray-200 dark:border-gray-600 pt-1.5">
                                <span>Grand Total:</span>
                                <span class="text-emerald-600 dark:text-emerald-400">₹{{ number_format($viewingOrder->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Trail / Activity History -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="font-bold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2">Activity History Log</h4>
                        @if(isset($viewingOrder->activities) && $viewingOrder->activities->count() > 0)
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @foreach($viewingOrder->activities as $act)
                                    <div class="bg-gray-50 dark:bg-gray-700/30 p-2 rounded-lg text-xs flex justify-between items-center">
                                        <span class="text-gray-700 dark:text-gray-300">{{ $act->description }}</span>
                                        <span class="text-[10px] text-gray-400">{{ Carbon\Carbon::parse($act->created_at)->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">No activity history recorded.</p>
                        @endif
                    </div>

                </div>

                <!-- Modal Footer with WhatsApp & Close -->
                <div class="flex flex-wrap justify-between items-center pt-4 mt-4 border-t border-gray-200 dark:border-gray-700 gap-2">
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $viewingOrder->customer_phone);
                        $whatsappLink = "https://wa.me/{$cleanPhone}?text=" . urlencode("Hello {$viewingOrder->customer_name}, regarding your order ({$viewingOrder->order_number}), we are reviewing your requirements. Thanks!");
                    @endphp
                    <a href="{{ $whatsappLink }}" target="_blank" class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                        <span>💬</span> Send WhatsApp Notification
                    </a>
                    
                    <button type="button" wire:click="closeViewModal" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-xs font-semibold rounded-xl transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>