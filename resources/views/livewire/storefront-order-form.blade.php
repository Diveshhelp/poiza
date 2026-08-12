<div class="max-w-5xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-xl rounded-2xl my-8 border border-gray-200 dark:border-gray-700">
    
    <!-- IF NOT VERIFIED: MOBILE NUMBER ENTRY SCREEN -->
    @if(!$isAuthorized)
        <div class="max-w-md mx-auto py-12 text-center space-y-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Enter Mobile Number to Order</h2>
            <p class="text-sm text-gray-500">Please enter your mobile phone number to unlock the Diora hardware catalog and place your order.</p>
            
            <form wire:submit.prevent="verifyMobile" class="space-y-3 pt-2">
                <div>
                    <input type="text" wire:model="auth_phone" placeholder="e.g. 9876543210" class="w-full px-4 py-2.5 border rounded-lg dark:bg-gray-700 dark:text-white text-sm text-center font-semibold tracking-wide focus:ring-2 focus:ring-indigo-500">
                    @error('auth_phone') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-lg transition shadow">
                    Continue to Catalog &rarr;
                </button>
            </form>
        </div>
    @else

    <!-- AUTHORIZED: FULL ORDER WORKFLOW -->
    <!-- Header Steps Bar -->
    <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200 dark:border-gray-700">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Diora Direct Order Portal</h1>
            <p class="text-xs text-gray-500">Verified Mobile: <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $customer_phone }}</span></p>
        </div>
        <div class="flex items-center gap-2 text-sm font-semibold">
            <span class="px-3 py-1 rounded-full {{ $step === 1 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600' }}">1. Products</span>
            <span>&rarr;</span>
            <span class="px-3 py-1 rounded-full {{ $step === 2 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600' }}">2. Details</span>
            <span>&rarr;</span>
            <span class="px-3 py-1 rounded-full {{ $step === 3 ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600' }}">3. Success</span>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-emerald-100 text-emerald-800 rounded-lg text-sm">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- STEP 1: SELECT PRODUCTS & SELECTION LIST -->
    @if($step === 1)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-4">
                
                <!-- Search & Filter Bar with Embedded Loader Spinner -->
                <div class="flex gap-2 relative">
                    <div class="relative w-full">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, code, alias, or finish..." class="w-full px-3 py-2 text-sm border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 pr-10">
                        
                        <!-- Search Spinner Icon -->
                        <div wire:loading wire:target="search, selectedCategory" class="absolute right-3 top-2.5">
                            <svg class="animate-spin h-5 w-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <select wire:model.live="selectedCategory" class="px-3 py-2 text-sm border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <h3 class="font-bold text-gray-800 dark:text-gray-200">Hardware Catalog</h3>
                
                <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2 relative">
                    
                    <!-- Full Area Loading Overlay State -->
                    <div wire:loading wire:target="search, selectedCategory" class="absolute inset-0 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xs flex items-center justify-center z-10 rounded-xl">
                        <div class="flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-900 px-4 py-2 rounded-lg shadow border dark:border-gray-700">
                            <svg class="animate-spin h-5 w-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Searching Catalog...
                        </div>
                    </div>

                    @if(empty(trim($search)) && empty($selectedCategory))
                        <div class="text-center py-12 text-gray-400 dark:text-gray-500 italic text-sm">
                            🔍 Please type a product name, code, or finish above to search the catalog.
                        </div>
                    @else
                        @forelse($products as $product)
                            <div class="flex justify-between items-center p-3 border rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 gap-4">
                                
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="w-12 h-12 object-cover rounded-lg border dark:border-gray-600">
                                @endif

                                <div class="flex-1">
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $product->product_name }}</h4>
                                    <div class="flex flex-wrap gap-2 text-xs text-indigo-600 dark:text-indigo-400 font-mono mt-0.5">
                                        <span>Code: {{ $product->product_code }}</span>
                                        @if($product->product_alias)<span>| Alias: {{ $product->product_alias }}</span>@endif
                                    </div>
                                    <div class="flex gap-3 text-xs text-gray-600 dark:text-gray-300 mt-1">
                                        @if($product->finish)<span><strong>Finish:</strong> {{ $product->finish }}</span>@endif
                                        @if($product->size)<span><strong>Size:</strong> {{ $product->size }}</span>@endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <input type="number" min="1" wire:model="productQuantities.{{ $product->id }}" class="w-16 px-2 py-1 text-sm border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                    <button wire:click="addToCart({{ $product->id }})" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition shadow">Add</button>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-8">No products found matching your search parameters.</p>
                        @endforelse

                        <!-- Pagination Links -->
                        @if(method_exists($products, 'links'))
                            <div class="mt-4">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Selection / Order Summary Panel -->
            <div class="bg-gray-50 dark:bg-gray-700/20 p-4 rounded-xl border dark:border-gray-700 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-3">Selected Items</h3>
                    @if(empty($this->cart))
                        <p class="text-xs text-gray-500 italic">No products added yet.</p>
                    @else
                        <div class="space-y-2 mb-4 max-h-[350px] overflow-y-auto">
                            @foreach($this->cartItems as $item)
                                <div class="flex justify-between items-center text-xs border-b pb-2 dark:border-gray-700">
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $item['product']->product_name }}</div>
                                        <div class="text-gray-500 font-mono text-[10px]">Code: {{ $item['product']->product_code }}</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 font-bold px-2 py-0.5 rounded">Qty: {{ $item['quantity'] }}</span>
                                        <button wire:click="removeFromCart({{ $item['product']->id }})" class="text-red-500 hover:text-red-700 font-bold text-base">&times;</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <button wire:click="proceedToCheckout" @if(empty($this->cart)) disabled @endif class="w-full mt-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-semibold text-sm rounded-lg transition shadow">
                    Proceed to Details &rarr;
                </button>
            </div>
        </div>
    @endif

    <!-- STEP 2: CUSTOMER DETAILS & CHECKOUT -->
    @if($step === 2)
        <form wire:submit.prevent="placeOrder" class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-gray-800 dark:text-gray-200">Customer & Shipping Information</h3>
                <button type="button" wire:click="$set('step', 1)" class="text-xs text-indigo-600 underline">&larr; Back to Products</button>
            </div>
            
            <!-- INVISIBLE SPAM BOT TRAP (HONEYPOT) -->
            <div style="display:none;" aria-hidden="true">
                <label>Website URL</label>
                <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
                    <input type="text" wire:model="customer_name" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number *</label>
                    <input type="text" wire:model="customer_phone" readonly class="w-full px-3 py-2 border rounded-md bg-gray-100 dark:bg-gray-600 dark:text-white text-sm cursor-not-allowed">
                    @error('customer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address (Optional)</label>
                    <input type="email" wire:model="customer_email" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method</label>
                    <select wire:model="payment_method" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        <option value="cod">Cash on Delivery (COD)</option>
                        <option value="bank_transfer">Direct Bank Transfer (NEFT/RTGS)</option>
                        <option value="online">Online Payment Gateway</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Shipping Address *</label>
                <textarea wire:model="shipping_address" rows="2" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white text-sm" placeholder="Full delivery address..."></textarea>
                @error('shipping_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Order Notes / Special Instructions</label>
                <textarea wire:model="notes" rows="1" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white text-sm"></textarea>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" wire:loading.attr="disabled" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-lg transition shadow flex items-center gap-2">
                    <span wire:loading.remove wire:target="placeOrder">Place Order Now</span>
                    <span wire:loading wire:target="placeOrder">Processing Order...</span>
                </button>
            </div>
        </form>
    @endif

    <!-- STEP 3: SUCCESS CONFIRMATION -->
    @if($step === 3 && $placedOrder)
        <div class="text-center space-y-4 py-8">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">&check;</div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Order Placed Successfully!</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300">Thank you for your order. Your order tracking reference number is:</p>
            <div class="inline-block bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-xl text-lg font-mono font-bold text-indigo-600 dark:text-indigo-400">
                {{ $placedOrder->order_number }}
            </div>
            <p class="text-xs text-gray-500">We have received your request and will contact you shortly regarding fulfillment.</p>
            <div class="pt-4">
                <button wire:click="resetOrder" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold shadow">Place Another Order</button>
            </div>
        </div>
    @endif
    
    @endif
</div>