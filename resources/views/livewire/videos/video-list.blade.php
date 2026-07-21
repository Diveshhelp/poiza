<div class="mx-auto py-2 sm:px-6 lg:px-8" x-data="{
    showModal: false,
    todo: {
        status: '',  
        priority: '',  
        
    },    
    init() {
        this.$wire.on('show-todo-details', (data) => {
            console.log(data[0].todoData);
            this.todo = data[0].todoData;
            this.showModal = true;
        });
    },
    downloading: false,
    downloadFile(attachmentId) {
        this.downloading = true;
        this.$wire.downloadFile(attachmentId)
            .then(() => {
                this.downloading = false;
            })
            .catch(() => {
                this.downloading = false;
            });
    },

}" @keydown.escape.window="showModal = false" x-cloak>
    <div class="flex items-center justify-between">
        <div class="min-w-0 flex-1">
            <h3 class="text-sm md:text-lg font-medium text-gray-900 truncate">{{ $moduleTitle }} List</h3>
            <p class=" md:block mt-2 text-sm text-gray-600 leading-relaxed">
                Manage your videos efficiently
            </p>
        </div>
        <div class="ml-2 md:ml-4">
            <a href="{{ route('videos') }}"
                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-1 md:mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="hidden sm:inline">Add New Video</span>
                <span class="inline sm:hidden">Add</span>
            </a>
        </div>
    </div>
    <div x-data="{
    showFilters: false,
    search: @entangle('searchTitle'),
    priority: @entangle('filterPriority'),
    status: @entangle('filterStatus'),
    dueDate: @entangle('filterDueDate')
}" class="mt-2" wire:key="todo-manager-module-{{ time() }}">

        <!-- Filter Toggle Button -->
        <a @click="showFilters = !showFilters"
            class="w-full mb-4 inline-flex items-center justify-between px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <div class="flex items-center p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>

                <!-- Active filters counter -->
                <span x-show="search || priority || status || dueDate"
                    class="ml-2 text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">
                    <span x-text="[search, priority, status, dueDate].filter(Boolean).length"></span>
                </span>
            </div>
            <!-- Toggle arrow -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200"
                :class="showFilters ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </a>

        <!-- Filter Panel -->
        <div x-show="showFilters" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95" class="bg-white p-4 rounded-lg shadow-md mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" wire:model="searchTitle"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Search todos...">
                </div>

                <!-- Priority Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select wire:model="filterPriority"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model="filterStatus"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Status</option>
                        <option value="not_started">Not Started</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <!-- Due Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                    <select wire:model="filterDueDate"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 flex justify-end space-x-2">
                <button wire:click="resetFilters"
                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                    Clear
                </button>
                <button wire:click="applyFilters"
                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>

            </div>
        </div>

        <!-- Todo List Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div>
                @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Rest of your todos list content -->
            </div>
            <div
                class="bg-white dark:bg-gray-950 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Uploads</h3>
                    <div class="flex gap-2">
                        <button class="p-2 text-gray-400 hover:text-indigo-600"><i
                                class="bi bi-grid-3x3-gap-fill"></i></button>
                        <button class="p-2 text-gray-400 hover:text-indigo-600"><i class="bi bi-sort-down"></i></button>
                    </div>
                </div>

             <div class="p-6">
    {{-- 1. Alpine Component --}}
    <div x-data="{ 
        open: false, 
        currentIndex: 0, 
        attachments: [],
        openModal(photoAttachments, index) {
            this.attachments = photoAttachments;
            this.currentIndex = index;
            this.open = true;
        },
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.attachments.length;
        },
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.attachments.length) % this.attachments.length;
        },
        {{-- Helper to check if the current file is a video --}}
        isVideo(path) {
            if(!path) return false;
            const videoExtensions = ['mp4', 'webm', 'ogg', 'mov'];
            const extension = path.split('.').pop().toLowerCase();
            return videoExtensions.includes(extension);
        }
    }" class="relative">

        {{-- Album Loop --}}
        <div class="space-y-6">
            @foreach($photos as $photo)
                <div class="album-group mb-8">
                    {{-- Enhanced Header --}}
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-3 px-1">
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-base font-bold text-gray-900 tracking-tight">{{ $photo->album_title }}</h3>
                            <span class="text-gray-300">•</span>
                            <span class="text-xs font-medium text-gray-500">{{ $photo->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-[11px] font-semibold text-indigo-600 uppercase tracking-wider">
                            <i class="bi bi-collection-play"></i>
                            {{ $photo->attachments->count() }}
                        </div>
                    </div>

                    {{-- Grid Container --}}
                    <div class="flex flex-wrap gap-1.5">
                        @php
                            $jsAttachments = $photo->attachments->map(fn($a) => asset('storage/'.$a->file_path));
                        @endphp

                        @foreach($photo->attachments as $index => $attachment)
                            @php 
                                $isVid = in_array(pathinfo($attachment->file_path, PATHINFO_EXTENSION), ['mp4', 'webm', 'ogg', 'mov']); 
                            @endphp
                            
                            <div wire:key="attachment-{{ $attachment->id }}" 
                                 @click="openModal({{ $jsAttachments->toJson() }}, {{ $index }})"
                                 class="group relative h-20 w-20 flex-shrink-0 cursor-pointer overflow-hidden rounded-md bg-black shadow-sm transition-all hover:ring-2 hover:ring-indigo-500">
                                
                                @if($isVid)
                                    {{-- Video Thumbnail --}}
                                    <video class="size-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                        <source src="{{ asset('storage/'. $attachment->file_path) }}#t=0.1">
                                    </video>
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <i class="bi bi-play-fill text-white text-xl"></i>
                                    </div>
                                @else
                                    {{-- Image Thumbnail --}}
                                    <img src="{{ asset('storage/'. $attachment->file_path) }}" 
                                         class="size-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                                @endif

                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- MODAL VIEW --}}
        <div x-show="open" 
             x-transition.opacity
             @keydown.escape.window="open = false"
             @keydown.right.window="next()"
             @keydown.left.window="prev()"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/95 backdrop-blur-sm"
             style="display: none;">
            
            <button @click="open = false" class="absolute top-5 right-5 text-white/70 hover:text-white z-[60]">
                <i class="bi bi-x-lg text-3xl"></i>
            </button>

            <div class="relative w-full h-full flex items-center justify-center">
                {{-- Navigation --}}
                <template x-if="attachments.length > 1">
                    <div class="absolute inset-x-0 flex justify-between px-4 z-50">
                        <button @click="prev()" class="p-3 rounded-full bg-white/10 text-white hover:bg-white/20 transition">
                            <i class="bi bi-chevron-left text-2xl"></i>
                        </button>
                        <button @click="next()" class="p-3 rounded-full bg-white/10 text-white hover:bg-white/20 transition">
                            <i class="bi bi-chevron-right text-2xl"></i>
                        </button>
                    </div>
                </template>

                {{-- Dynamic Media Player --}}
                <div class="max-h-[90vh] max-w-full flex items-center justify-center">
                    {{-- If Video --}}
                    <template x-if="isVideo(attachments[currentIndex])">
                        <video :src="attachments[currentIndex]" 
                               controls 
                               autoplay 
                               class="max-h-[90vh] rounded shadow-2xl">
                        </video>
                    </template>

                    {{-- If Image --}}
                    <template x-if="!isVideo(attachments[currentIndex])">
                        <img :src="attachments[currentIndex]" 
                             class="max-h-[90vh] max-w-full rounded shadow-2xl object-contain">
                    </template>
                </div>

                <div class="absolute bottom-5 bg-black/50 px-3 py-1 rounded-full text-white/80 text-sm">
                    <span x-text="currentIndex + 1"></span> / <span x-text="attachments.length"></span>
                </div>
            </div>
        </div>
    </div>
</div>

                    @if($photos->hasMorePages())
                    <div x-data="{
                observe() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                @this.call('loadMore')
                            }
                        })
                    }, { threshold: 0.1 })
                    observer.observe($refs.loadMoreTrigger)
                }
            }" x-init="observe" x-ref="loadMoreTrigger" class="mt-10 flex justify-center">
                        <div wire:loading class="flex items-center gap-2 text-indigo-500">
                            <div class="animate-spin size-4 border-2 border-current border-t-transparent rounded-full">
                            </div>
                            <span class="text-xs font-medium">Loading more...</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>