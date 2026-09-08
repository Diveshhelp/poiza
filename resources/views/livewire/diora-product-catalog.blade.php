<div class="min-h-screen bg-[#f8fafc] text-slate-800 antialiased py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-5">

        <!-- Header & Filter Navigation -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-4 sm:p-5">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Live Catalog</span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $products->total() }} Products
                        </span>
                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5">Real-time inventory and finish availability.</p>
                </div>

                <!-- Instant Search Field -->
                <div class="w-full lg:w-80 relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Search model, code, finish..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10 transition outline-none"
                    />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    @if($search)
                        <button wire:click="$set('search', '')" class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-600 text-xs">✕</button>
                    @endif
                </div>
            </div>

            <!-- Tab Row & Finish Filter -->
            <div class="mt-4 pt-3.5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <!-- Sleek Segmented Tabs -->
                <nav class="flex items-center gap-1 bg-slate-100/90 p-1 rounded-xl border border-slate-200/60 text-xs font-medium">
                    <button 
                        type="button"
                        wire:click="$set('stockFilter', 'all')" 
                        class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 {{ $stockFilter === 'all' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <span>All</span>
                    </button>
                    
                    <button 
                        type="button"
                        wire:click="$set('stockFilter', 'in_stock')" 
                        class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 {{ $stockFilter === 'in_stock' ? 'bg-white text-emerald-700 font-bold shadow-xs' : 'text-slate-600 hover:text-emerald-700' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>In Stock</span>
                    </button>

                    <button 
                        type="button"
                        wire:click="$set('stockFilter', 'out_of_stock')" 
                        class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 {{ $stockFilter === 'out_of_stock' ? 'bg-white text-rose-700 font-bold shadow-xs' : 'text-slate-600 hover:text-rose-700' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        <span>Zero Stock</span>
                    </button>
                </nav>

                <!-- Finish Selector -->
                @if($finishes->isNotEmpty())
                    <div class="flex items-center gap-2">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Finish</label>
                        <select wire:model.live="selectedFinish" class="bg-slate-50 border border-slate-200 rounded-xl py-1.5 pl-3 pr-8 text-xs font-medium text-slate-700 focus:border-indigo-600 focus:bg-white transition">
                            <option value="">All Finishes</option>
                            @foreach($finishes as $f)
                                <option value="{{ $f }}">{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Cards Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
            @forelse($products as $product)
                @php
                    $stock = (int) ($product->total_stock ?? 0);
                    $images = $this->resolveImages($product);
                    $thumbnail = $images[0] ?? null;
                @endphp

                <div 
                    wire:click="openProductModal({{ $product->id }})"
                    class="group bg-white rounded-2xl border border-slate-200/90 hover:border-indigo-400/90 shadow-xs hover:shadow-md transition-all duration-150 cursor-pointer flex flex-col justify-between overflow-hidden"
                >
                    <div>
                        <!-- Hardware Product Showcase Box -->
                        <div class="relative w-full aspect-[4/3] bg-white border-b border-slate-100 p-3 flex items-center justify-center overflow-hidden">
                            @if($thumbnail)
                                <img 
                                    src="{{ $thumbnail }}" 
                                    alt="{{ $product->product_name }}" 
                                    loading="lazy" 
                                    class="w-full h-full object-contain object-center group-hover:scale-108 transition-transform duration-200 ease-out"
                                >
                            @else
                                <div class="flex flex-col items-center justify-center text-slate-300">
                                    <svg class="w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[9px] text-slate-400 mt-1">No Image</span>
                                </div>
                            @endif

                            <!-- Finish Badge -->
                            @if($product->finish)
                                <span class="absolute top-2 left-2 bg-slate-900/75 backdrop-blur-xs text-white text-[9px] font-medium px-1.5 py-0.5 rounded tracking-wide">
                                    {{ $product->finish }}
                                </span>
                            @endif

                            <!-- Multiple Images Indicator -->
                            @if(count($images) > 1)
                                <span class="absolute bottom-1.5 right-1.5 bg-slate-100 text-slate-600 border border-slate-200 text-[9px] font-bold px-1 py-0.5 rounded">
                                    +{{ count($images) - 1 }}
                                </span>
                            @endif
                        </div>

                        <!-- Product Code & Name -->
                        <div class="p-2.5">
                            <span class="font-mono text-[10px] font-extrabold text-indigo-600 tracking-wider block uppercase">
                                {{ $product->product_code }}
                            </span>
                            <h3 class="font-bold text-xs text-slate-800 line-clamp-1 group-hover:text-indigo-600 transition-colors mt-0.5" title="{{ $product->product_name }}">
                                {{ $product->product_name }}
                            </h3>
                        </div>
                    </div>

                    <!-- Compact Stock Strip -->
                    <div class="px-2.5 pb-2.5 pt-0">
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px]">
                            <span class="text-slate-400 font-medium">Stock</span>
                            @if($stock > 0)
                                <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $stock }}
                                </span>
                            @else
                                <span class="inline-flex items-center font-semibold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded-md border border-rose-200/60 text-[10px]">
                                    Out
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200">
                    <p class="text-sm font-semibold text-slate-700">No products found</p>
                    <p class="text-xs text-slate-400 mt-1">Try clearing your filters or search keywords.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $products->links() }}
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- HIGH-PRECISION MODAL WITH INSTANT ALPINE GALLERY -->
    <!-- ========================================================= -->
    @if($selectedProduct)
        @php
            $modalStock = (int) ($selectedProduct->total_stock ?? 0);
            $modalImages = $this->resolveImages($selectedProduct);
        @endphp

        <div 
            class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-950/60 backdrop-blur-xs"
            x-data="{ 
                images: {{ json_encode($modalImages) }}, 
                activeImg: '{{ $modalImages[0] ?? '' }}',
                setImg(url) { this.activeImg = url; }
            }"
            @keydown.escape.window="$wire.closeModal()"
        >
            <!-- Backdrop -->
            <div class="fixed inset-0" wire:click="closeModal"></div>

            <!-- Modal Card Frame -->
            <div 
                class="relative bg-white w-full sm:max-w-2xl rounded-t-3xl sm:rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-10 max-h-[90vh] flex flex-col"
                @click.away="$wire.closeModal()"
            >
                <!-- Top Navigation Bar -->
                <div class="flex items-center justify-between px-5 py-3.5 bg-slate-50 border-b border-slate-200">
                    <div class="flex items-center gap-2 truncate pr-4">
                        <span class="font-mono text-xs font-black text-indigo-600 uppercase tracking-wide">{{ $selectedProduct->product_code }}</span>
                        <span class="text-slate-300">|</span>
                        <span class="text-xs font-bold text-slate-800 truncate">{{ $selectedProduct->product_name }}</span>
                    </div>
                    <button wire:click="closeModal" class="w-7 h-7 rounded-full bg-slate-200/80 hover:bg-slate-300 text-slate-600 flex items-center justify-center text-xs transition">
                        ✕
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-5 overflow-y-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-start">
                        
                        <!-- Left: Instant Switch Gallery -->
                        <div class="space-y-3">
                            <!-- Main Stage (Pure white canvas, pristine handle display) -->
                            <div class="relative w-full aspect-square bg-white border border-slate-200 rounded-xl p-4 flex items-center justify-center overflow-hidden shadow-2xs">
                                <template x-if="activeImg">
                                    <img :src="activeImg" class="w-full h-full object-contain transition-all duration-150">
                                </template>
                                <template x-if="!activeImg">
                                    <span class="text-xs text-slate-400">No Image Available</span>
                                </template>
                            </div>

                            <!-- Interactive Thumbnails Strip -->
                            <template x-if="images.length > 1">
                                <div class="flex items-center gap-2 overflow-x-auto pb-1">
                                    <template x-for="(img, idx) in images" :key="idx">
                                        <button 
                                            type="button" 
                                            @click="setImg(img)"
                                            :class="activeImg === img ? 'border-indigo-600 ring-2 ring-indigo-500/20 shadow-xs' : 'border-slate-200 opacity-60 hover:opacity-100'"
                                            class="w-12 h-12 shrink-0 rounded-lg border bg-white p-1 overflow-hidden transition-all"
                                        >
                                            <img :src="img" class="w-full h-full object-contain">
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Right: Specifications & Inventory -->
                        <div class="space-y-4">
                            <div>
                                <span class="font-mono text-xs font-extrabold text-indigo-600 tracking-wider uppercase">
                                    {{ $selectedProduct->product_code }}
                                </span>
                                <h2 class="text-lg font-black text-slate-900 mt-0.5 leading-snug">
                                    {{ $selectedProduct->product_name }}
                                </h2>
                            </div>

                            <!-- Specs Grid -->
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Finish</span>
                                    <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $selectedProduct->finish ?? 'Standard' }}</span>
                                </div>
                                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Item ID</span>
                                    <span class="font-mono font-bold text-slate-800 text-sm mt-0.5 block">#{{ $selectedProduct->id }}</span>
                                </div>
                            </div>

                            <!-- Warehouse Stock Card -->
                            <div class="rounded-xl p-3.5 border {{ $modalStock > 0 ? 'bg-emerald-50/80 border-emerald-200' : 'bg-rose-50/80 border-rose-200' }}">
                                <div class="flex items-center justify-between text-[11px] font-bold {{ $modalStock > 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                    <span class="uppercase tracking-wider">Warehouse Inventory</span>
                                    <span>{{ $modalStock > 0 ? 'Ready to Dispatch' : 'Out of Stock' }}</span>
                                </div>
                                <div class="text-2xl font-black mt-1 {{ $modalStock > 0 ? 'text-emerald-950' : 'text-rose-950' }}">
                                    {{ max(0, $modalStock) }} <span class="text-xs font-normal opacity-80">pcs available</span>
                                </div>
                            </div>

                            @if(!empty($selectedProduct->notes) || !empty($selectedProduct->description))
                                <div class="text-xs text-slate-500 bg-slate-50 rounded-xl p-3 border border-slate-200/60 leading-relaxed">
                                    {{ $selectedProduct->notes ?? $selectedProduct->description }}
                                </div>
                            @endif

                            <button wire:click="closeModal" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                                Close Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>