<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">Handle Costing & Reusable Templates</h2>
            <p class="text-xs text-gray-500 mt-0.5">Select a product to load its saved cost template, modify values in real time, and click update.</p>
        </div>
        <div class="flex items-center gap-3">
            @if($selectedProductId)
                <button wire:click="saveOrUpdateTemplate" wire:loading.attr="disabled" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm transition whitespace-nowrap">
                    Save / Update Template for Product
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-3.5 rounded-xl text-xs text-emerald-800 shadow-sm">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 p-3.5 rounded-xl text-xs text-rose-800 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT COLUMN: Inputs & Template Control -->
        <div class="lg:col-span-1 space-y-4">
            
            <!-- Product Selector -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 space-y-2 text-xs">
                <label class="block font-bold text-gray-800 uppercase tracking-wider">Select Product to Reuse/Update</label>
                <select wire:model.live="selectedProductId" class="w-full rounded-xl border-gray-300 py-2 px-2 bg-white font-medium text-gray-700">
                    <option value="">-- Choose Product Model --</option>
                    @foreach($allProductsForDropdown as $p)
                        <option value="{{ $p->id }}">{{ $p->product_name }} (Model: {{ $p->model_key ?? 'DS521' }})</option>
                    @endforeach
                </select>
            </div>

            <!-- 1. Base Assembly Costs -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 space-y-3 text-xs">
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="font-extrabold text-gray-900 uppercase">1. Base Assembly Costs</span>
                    <span class="text-[10px] bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded font-bold">Subtotal: ₹{{ $this->baseCommon }}</span>
                </div>
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Mortise Set</label>
                        <input type="number" step="0.01" wire:model.live="mortise_set" class="w-full rounded-lg border-gray-300 py-1.5 px-2 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Pinjaru + Dabi</label>
                        <input type="number" step="0.01" wire:model.live="pinjaru_dabi" class="w-full rounded-lg border-gray-300 py-1.5 px-2 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Box</label>
                        <input type="number" step="0.01" wire:model.live="box" class="w-full rounded-lg border-gray-300 py-1.5 px-2 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Packaging</label>
                        <input type="number" step="0.01" wire:model.live="packaging" class="w-full rounded-lg border-gray-300 py-1.5 px-2 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Masalo</label>
                        <input type="number" step="0.01" wire:model.live="masalo" class="w-full rounded-lg border-gray-300 py-1.5 px-2 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-indigo-600 font-bold mb-1">Profit %</label>
                        <input type="number" step="0.01" wire:model.live="profit_margin_percent" class="w-full rounded-lg border-indigo-200 bg-indigo-50/50 py-1.5 px-2 text-center font-extrabold text-indigo-700">
                    </div>
                </div>
            </div>

            <!-- 2. Buffing & Finish Extra Costs -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 space-y-3 text-xs">
                <span class="font-extrabold text-gray-900 uppercase block border-b pb-2">2. Buffing & Coatings</span>
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Buff Matt</label>
                        <input type="number" step="0.01" wire:model.live="buff_matt" class="w-full rounded-lg border-gray-300 py-1.5 px-2 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Buff Glossy</label>
                        <input type="number" step="0.01" wire:model.live="buff_glossy" class="w-full rounded-lg border-gray-300 py-1.5 px-2 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">PVD Cost</label>
                        <input type="number" step="0.01" wire:model.live="pvd_cost" class="w-full rounded-lg border-gray-300 py-1.5 px-2 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Black Color Extra</label>
                        <input type="number" step="0.01" wire:model.live="black_color" class="w-full rounded-lg border-gray-300 py-1.5 px-2 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-gray-400 font-bold mb-1">Antique Color Extra</label>
                        <input type="number" step="0.01" wire:model.live="antique_color" class="w-full rounded-lg border-gray-300 py-1.5 px-2 text-center font-bold">
                    </div>
                </div>
            </div>

            <!-- 3. Two-Piece Extra Components -->
            <!-- 3. Two-Piece Extra Components (Visible Textboxes) -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 space-y-3 text-xs">
                <span class="font-extrabold text-gray-900 uppercase block border-b pb-2">3. Two-Piece Extra Components</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    <!-- Matt 2-Piece Inputs -->
                    <div class="bg-amber-50/50 p-3 rounded-xl border border-amber-200 space-y-2">
                        <span class="font-extrabold text-amber-900 block text-[11px]">Matt 2-Piece Extras</span>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-gray-600 font-semibold mb-0.5">Color Cost</label>
                                <input type="number" step="0.01" wire:model.live="two_pc_matt_black" class="w-full rounded-lg border-gray-300 py-1.5 px-2 bg-white text-center font-bold">
                            </div>
                            <div>
                                <label class="block text-gray-600 font-semibold mb-0.5">Buff Cost</label>
                                <input type="number" step="0.01" wire:model.live="two_pc_matt_buff" class="w-full rounded-lg border-gray-300 py-1.5 px-2 bg-white text-center font-bold">
                            </div>
                            <div>
                                <label class="block text-gray-600 font-semibold mb-0.5">Bolt Cost</label>
                                <input type="number" step="0.01" wire:model.live="two_pc_matt_bolt" class="w-full rounded-lg border-gray-300 py-1.5 px-2 bg-white text-center font-bold">
                            </div>
                        </div>
                    </div>

                    <!-- PVD 2-Piece Inputs -->
                    <div class="bg-indigo-50/50 p-3 rounded-xl border border-indigo-200 space-y-2">
                        <span class="font-extrabold text-indigo-900 block text-[11px]">PVD 2-Piece Extras</span>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-gray-600 font-semibold mb-0.5">PVD Cost</label>
                                <input type="number" step="0.01" wire:model.live="two_pc_pvd_color" class="w-full rounded-lg border-gray-300 py-1.5 px-2 bg-white text-center font-bold">
                            </div>
                            <div>
                                <label class="block text-gray-600 font-semibold mb-0.5">Glossy Buff Cost</label>
                                <input type="number" step="0.01" wire:model.live="two_pc_pvd_buff" class="w-full rounded-lg border-gray-300 py-1.5 px-2 bg-white text-center font-bold">
                            </div>
                            <div>
                                <label class="block text-gray-600 font-semibold mb-0.5">Bolt Cost</label>
                                <input type="number" step="0.01" wire:model.live="two_pc_pvd_bolt" class="w-full rounded-lg border-gray-300 py-1.5 px-2 bg-white text-center font-bold">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Live Computed Output Matrix -->
        <div class="lg:col-span-2 space-y-4">
            
            <!-- Live Finish Rate Summary Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl text-center shadow-xs">
                    <span class="text-[11px] font-bold text-amber-800 uppercase block mb-1">1. SS Matt Final</span>
                    <span class="text-xl font-extrabold text-amber-900">₹{{ number_format($computedMatt, 1) }}</span>
                </div>
                <div class="bg-gray-900 border border-gray-800 p-4 rounded-2xl text-center shadow-xs">
                    <span class="text-[11px] font-bold text-gray-300 uppercase block mb-1">2. Black Final</span>
                    <span class="text-xl font-extrabold text-white">₹{{ number_format($computedBlack, 1) }}</span>
                </div>
                <div class="bg-orange-50 border border-orange-200 p-4 rounded-2xl text-center shadow-xs">
                    <span class="text-[11px] font-bold text-orange-800 uppercase block mb-1">3. Antique Final</span>
                    <span class="text-xl font-extrabold text-orange-900">₹{{ number_format($computedAntique, 1) }}</span>
                </div>
                <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-2xl text-center shadow-xs">
                    <span class="text-[11px] font-bold text-indigo-800 uppercase block mb-1">4. PVD Final</span>
                    <span class="text-xl font-extrabold text-indigo-900">₹{{ number_format($computedPvd, 1) }}</span>
                </div>
            </div>

            <!-- Two-Piece Extra Summary Card -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-emerald-50 border border-emerald-200 p-3.5 rounded-2xl flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-emerald-800 uppercase block">2-Piece Matt Extra Cost</span>
                        <span class="text-xs text-emerald-600">Color + Buff + Bolt</span>
                    </div>
                    <span class="text-lg font-extrabold text-emerald-900">+₹{{ number_format($computedTwoPcMatt, 0) }}</span>
                </div>
                <div class="bg-purple-50 border border-purple-200 p-3.5 rounded-2xl flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-purple-800 uppercase block">2-Piece PVD Extra Cost</span>
                        <span class="text-xs text-purple-600">PVD + Buff + Bolt</span>
                    </div>
                    <span class="text-lg font-extrabold text-purple-900">+₹{{ number_format($computedTwoPcPvdTotal, 0) }}</span>
                </div>
            </div>

            <!-- Live Catalog Overview Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-xs uppercase">Catalog Products & Template Status</h3>
                    <span class="text-[11px] text-gray-400">Click a product to load its template</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50/50 text-gray-500 uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left font-bold">Product Name & Model</th>
                                <th class="px-4 py-3 text-center font-bold text-gray-700">Template Status</th>
                                <th class="px-4 py-3 text-right font-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-gray-900 text-xs">{{ $product->product_name }}</div>
                                        <div class="text-[11px] text-gray-400">Model: <span class="text-gray-700 font-semibold">{{ $product->model_key ?? 'N/A' }}</span></div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($product->costTemplate)
                                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[10px] font-bold border border-emerald-200">Saved Template</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[10px] font-medium">No Template</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <button wire:click="$set('selectedProductId', {{ $product->id }})" class="px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-semibold transition">
                                            Load Template
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-12 text-center text-gray-400 text-xs">
                                        No products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100 text-xs">
                    {{ $products->links() }}
                </div>
            </div>

        </div>
    </div>
</div>