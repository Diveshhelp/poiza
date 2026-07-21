<div  class="mx-auto py-2 sm:px-6 lg:px-8"  x-data="{
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
      Manage and track your tasks efficiently
    </p>
  </div>
  <div class="ml-2 md:ml-4">
    <a href="{{ route('todos') }}"
      class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-1 md:mr-2" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
      </svg>
      <span class="hidden sm:inline">Add New Todo</span>
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
}" class="mt-8" wire:key="todo-manager-module-{{ time() }}">
    
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
    <!-- Elegantly styled responsive tabs with advanced effects -->
    <div class="w-full mb-4">
    <div class="border-b border-gray-200 overflow-x-auto overflow-y-hidden scrollbar-hide bg-gradient-to-r from-white to-gray-50 rounded-t-lg shadow-sm">
        <nav class="-mb-px flex space-x-1 md:space-x-6 px-2 py-2">
        @foreach ($tabs as $key => $tab)
            <button
            wire:click="$set('currentTab', '{{ $key }}')"
            class="relative group flex items-center whitespace-nowrap py-2.5 md:py-3.5 px-3 md:px-4 text-xs md:text-sm font-medium transition-all duration-300 rounded-t-lg {{ $currentTab === $key
                ? 'bg-white text-indigo-600 shadow-sm'
                : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-500' }}"
            >
            <span class="relative z-10 flex items-center">
                {{ $tab['name'] }}
                @if($currentTab === $key)
                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-500 rounded-full"></span>
                @else
                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-400 rounded-full opacity-0 group-hover:opacity-50 transform scale-x-0 group-hover:scale-x-100 transition-all duration-300 ease-in-out"></span>
                @endif
            </span>
            <span class="ml-1.5 md:ml-2.5 rounded-full {{ $currentTab === $key ? 'bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200' : 'bg-gray-100 text-gray-600' }} px-1.5 md:px-2.5 py-0.5 text-xs font-medium shadow-sm transition-all duration-300">
                {{ $tabCounts[$key] }}
            </span>
            </button>
        @endforeach
        </nav>
    </div>
    </div>
    <!-- Filter Panel -->
    <div x-show="showFilters" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100" 
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95" 
        class="bg-white p-4 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                    wire:model="searchTitle"
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
            <div class=" md:min-h-screen">
                <table class="hidden md:table min-w-full h-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th wire:click="sortBy('title')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Title
                                @if ($sortField === 'title')
                                    <span class="ml-1">
                                        @if ($sortDirection === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    </span>
                                @endif
                            </th>
                            <th wire:click="sortBy('due_date')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Due Date
                                @if ($sortField === 'due_date')
                                    <span class="ml-1">
                                        @if ($sortDirection === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    </span>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Notes
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($todos as $todo)
                        <tr class="hover:bg-gray-50 
                            {{ $todo->status === 'completed' ? 'bg-green-50' : '' }}
                            {{ $todo->status === 'cancelled' ? 'bg-red-50' : '' }}">

                                <td class="px-6 py-4 whitespace-nowrap cursor-pointer"
                                    wire:click="showTaskDetails('{{ $todo->uuid }}')" @click="open = false">
                                    <div class="text-sm font-medium text-gray-900 max-w-xs truncate">{{ $todo->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($todo->description, 50) }}</div>
                                    <div class="text-xs text-gray-500">
                                        Created at {{ $todo->created_at->format('M d, Y h:i A') }}
                                    </div>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $todo->due_date->format('M d, Y h:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $todo->due_date->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-150 
                                                    {{ strtolower($todo->priority) === 'high'
                                                        ? 'bg-rose-50 text-rose-700 ring-1 ring-rose-600/20 shadow-sm hover:bg-rose-100'
                                                        : (strtolower($todo->priority) === 'medium'
                                                            ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20 shadow-sm hover:bg-amber-100'
                                                            : 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20 shadow-sm hover:bg-emerald-100') }}">
                                        {{ ucwords($todo->priority) }}
                                    </span>
                                </td>

                                
                                <td class="px-3 py-4 text-sm text-gray-500  z-[9999]" x-cloak>
                                    <div x-data="{ open: false, loading: false }" class="relative">
                                        <!-- Status Badge/Button -->
                                    <button @click="open = !open" type="button"
                                                                            :disabled="loading || '{{ $todo->status }}' === 'completed'"
                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset 
                                                                @if ($todo->status === 'pending') bg-gray-50 text-gray-700 ring-gray-600/20
                                                                @elseif($todo->status === 'in_progress') bg-blue-50 text-blue-700 ring-blue-600/20
                                                                @elseif($todo->status === 'completed') bg-green-50 text-green-700 ring-green-600/20
                                                                @else bg-red-50 text-red-700 ring-red-600/20 @endif">
                                            <span
                                                x-show="!loading">{{ ucwords(str_replace('_', ' ', $todo->status ?? '')) }}</span>
                                            <!-- Loading Spinner -->
                                            <svg x-show="loading" class="animate-spin h-4 w-4 text-current"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                    <div x-show="open && '{{ $todo->status }}' !== 'completed'" @click.away="open = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden">
                                        
                                        <div class="py-1 border-b border-gray-100">
                                            <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Update Status</div>
                                        </div>
                                        
                                        <div class="py-1">
                                            <!-- Pending -->
                                            <button wire:click="updateStatus('{{ $todo->uuid }}', 'pending')"
                                                @click="loading = true; open = false"
                                                class="flex items-center w-full px-4 py-2 text-left text-sm hover:bg-gray-50 transition-colors duration-150"
                                                :class="{ 'bg-blue-50': '{{ $todo->status }}' === 'pending' }">
                                                <span class="flex-shrink-0 w-2 h-2 mr-3 rounded-full bg-yellow-400"></span>
                                                <span class="flex-grow font-medium" :class="{ 'text-blue-600': '{{ $todo->status }}' === 'pending' }">Pending</span>
                                                <svg x-show="'{{ $todo->status }}' === 'pending'" class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>

                                            <!-- In Progress -->
                                            <button wire:click="updateStatus('{{ $todo->uuid }}', 'in_progress')"
                                                @click="loading = true; open = false"
                                                class="flex items-center w-full px-4 py-2 text-left text-sm hover:bg-gray-50 transition-colors duration-150"
                                                :class="{ 'bg-blue-50': '{{ $todo->status }}' === 'in_progress' }">
                                                <span class="flex-shrink-0 w-2 h-2 mr-3 rounded-full bg-blue-400"></span>
                                                <span class="flex-grow font-medium" :class="{ 'text-blue-600': '{{ $todo->status }}' === 'in_progress' }">In Progress</span>
                                                <svg x-show="'{{ $todo->status }}' === 'in_progress'" class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>

                                            <!-- Completed -->
                                            <button wire:click="updateStatus('{{ $todo->uuid }}', 'completed')"
                                                @click="loading = true; open = false"
                                                class="flex items-center w-full px-4 py-2 text-left text-sm hover:bg-gray-50 transition-colors duration-150"
                                                :class="{ 'bg-blue-50': '{{ $todo->status }}' === 'completed' }">
                                                <span class="flex-shrink-0 w-2 h-2 mr-3 rounded-full bg-green-400"></span>
                                                <span class="flex-grow font-medium" :class="{ 'text-blue-600': '{{ $todo->status }}' === 'completed' }">Completed</span>
                                                <svg x-show="'{{ $todo->status }}' === 'completed'" class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>

                                            <!-- Cancelled -->
                                            <button wire:click="updateStatus('{{ $todo->uuid }}', 'cancelled')"
                                                @click="loading = true; open = false"
                                                class="flex items-center w-full px-4 py-2 text-left text-sm hover:bg-gray-50 transition-colors duration-150"
                                                :class="{ 'bg-blue-50': '{{ $todo->status }}' === 'cancelled' }">
                                                <span class="flex-shrink-0 w-2 h-2 mr-3 rounded-full bg-red-400"></span>
                                                <span class="flex-grow font-medium" :class="{ 'text-blue-600': '{{ $todo->status }}' === 'cancelled' }">Cancelled</span>
                                                <svg x-show="'{{ $todo->status }}' === 'cancelled'" class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="openNoteModal({{ $todo->id }})"
                                        class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                        <span class="truncate">{{ $todo->notes()->count() }} Notes</span>
                                        <svg class="ml-1 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </td>
                                <td x-cloak class="relative py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <div x-data="{
                                        open: false,
                                        menuStyle: {
                                            top: '0px',
                                            left: '0px'
                                        },
                                        initializeMenu() {
                                            const button = this.$refs.menuButton;
                                            const buttonRect = button.getBoundingClientRect();
                                            const windowHeight = window.innerHeight;
                                    
                                            // Calculate if menu should appear above or below
                                            const spaceBelow = windowHeight - buttonRect.bottom;
                                            const menuHeight = 150; // Approximate height of menu
                                    
                                            if (spaceBelow < menuHeight) {
                                                // Position above the button
                                                this.menuStyle = {
                                                    top: `${buttonRect.top - menuHeight}px`,
                                                    left: `${buttonRect.left - 180}px` // Menu width adjustment
                                                };
                                            } else {
                                                // Position below the button
                                                this.menuStyle = {
                                                    top: `${buttonRect.bottom}px`,
                                                    left: `${buttonRect.left - 180}px` // Menu width adjustment
                                                };
                                            }
                                        }
                                    }" @click.away="open = false"
                                        class="relative inline-block text-left">
                                        <!-- Menu Button -->
                                        <button @click="open = !open; $nextTick(() => initializeMenu())"
                                            x-ref="menuButton" type="button"
                                            class="p-2 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="open" x-ref="menuItems"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95" :style="menuStyle"
                                            class="fixed w-48 rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none z-[9999]">
                                            <div class="py-1 bg-white rounded-md divide-y divide-gray-100">
                                                <!-- View Details -->
                                                <button wire:click="showTaskDetails('{{ $todo->uuid }}')"
                                                    @click="open = false"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 group">
                                                    <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View Details
                                                </button>

                                                <!-- Edit -->
                                                <a href="{{ route('todos', ['uuid' => $todo->uuid]) }}"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 group">
                                                    <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>

                                                <!-- Delete -->
                                                @if($todo->status !== 'completed' && $todo->status !== 'cancelled')
                                                    <button wire:confirm="Are you sure you want to delete this todo?"
                                                        wire:click="deleteTodo('{{ $todo->uuid }}')" @click="open = false"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 group">
                                                        <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-500"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete
                                                    </button>
                                                    @endif
                                            </div>


                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    No todos found. Create your first todo to get started!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>  
        <!-- Mobile Todo List View -->
            <div class="md:hidden">
                @forelse ($todos as  $index =>$todo)
               <!-- Modern Todo Card Component -->
                <div class="bg-white rounded-lg shadow-md border border-gray-100 mb-5 overflow-hidden">
                    <!-- Card Header with Priority Indicator -->
                    <div class="relative">
                        <!-- Priority Indicator (colored bar at top) -->
                        <div class="h-1.5 w-full 
                            {{ strtolower($todo->priority) === 'high' 
                                ? 'bg-rose-500' 
                                : (strtolower($todo->priority) === 'medium' 
                                    ? 'bg-amber-500' 
                                    : 'bg-emerald-500') }}">
                        </div>
                        
                        <!-- Header Content -->
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <!-- Title and Creator Info -->
                                <div class="flex-1 pr-2">
                                    <h3 class="text-base font-semibold text-gray-900 line-clamp-1">
                                        {{ $todo->title }}
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">
                                    Created at {{ $todo->created_at->format('M d, Y h:i:s') }}
                                        @if(isset($todo->created_user->name))
                                            by {{ $todo->created_user->name }}
                                        @endif
                                    </p>
                                </div>
                                
                                <!-- Priority Badge -->
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                    {{ strtolower($todo->priority) === 'high' 
                                        ? 'bg-rose-50 text-rose-700 ring-1 ring-rose-600/20' 
                                        : (strtolower($todo->priority) === 'medium' 
                                            ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20' 
                                            : 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20') }}">
                                    {{ ucwords($todo->priority) }}
                                </span>
                            </div>
                            
                            <!-- Description -->
                            <p class="text-sm text-gray-600 line-clamp-2 mt-1">
                                {{ $todo->description ?? $todo->work_detail ?? '' }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Task Details Section -->
                    <div class="px-4 py-3 bg-gray-50 border-t border-b border-gray-100">
                        <!-- Info Grid (2 columns on mobile) -->
                        <div class="grid grid-cols-1 gap-x-4 gap-y-2">
                        <!-- Full-width mobile due date component -->
                            <div class="w-full px-2 py-1.5 bg-gray-50 rounded-md">
                                <!-- First line with label, date and status -->
                                <div class="flex items-center justify-between w-full">
                                    <!-- Left side: Label with icon -->
                                    <div class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-xs font-medium text-gray-700">Due Date:</span>
                                    </div>
                                    
                                    <!-- Right side: Status indicator -->
                                    @if(isset($todo->due_date))
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-2xs font-medium {{ $todo->due_date->isPast() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $todo->due_date->isPast() ? 'Overdue' : 'On Schedule' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Second line with the actual date and time -->
                                <div class="flex items-center justify-between w-full mt-1">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ isset($todo->due_date) ? $todo->due_date->format('M d, Y') : ($todo->deadline ?? 'Not set') }}
                                    </div>
                                    
                                    @if(isset($todo->due_date))
                                        <div class="flex items-center gap-1.5">
                                            <div class="text-xs text-gray-600">
                                                {{ $todo->due_date->format('h:i A') }}
                                            </div>
                                            <div class="text-xs {{ $todo->due_date->isPast() ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                                                ({{ $todo->due_date->diffForHumans(['short' => true]) }})
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- Type/Category (if available) -->
                            @if(isset($todo->work_type))
                            <div class="flex items-center space-x-2">
                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <div class="min-w-0">
                                    <div class="text-xs text-gray-500">Type</div>
                                    <div class="text-xs font-medium text-gray-900 truncate">
                                        {{ $todo->work_type }}
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Repetition (if available) -->
                            @if(isset($todo->repetition))
                            <div class="flex items-center space-x-2">
                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <div class="min-w-0">
                                    <div class="text-xs text-gray-500">Repeat</div>
                                    <div class="text-xs font-medium text-gray-900 truncate">
                                        {{ $todo->repetition }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Status Section -->
                    <div x-data="{ loading: false, selectedStatus: '{{ $todo->status ?? 'pending' }}' }" class="px-4 py-3 bg-white border-b border-gray-100">
                        <div class="flex flex-col">
                            <div class="text-xs font-medium text-gray-500 mb-2">Status</div>
                            
                            <!-- Status Pills -->
                            <div class="flex flex-wrap gap-2 w-full">
                                @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $status)
                                <button 
                                    wire:click="updateStatus('{{ $todo->uuid }}', '{{ $status }}')"
                                    @click="loading = true; selectedStatus = '{{ $status }}'"
                                    :disabled="loading"
                                    :class="[
                                    'px-3 py-1.5 rounded-md text-xs font-medium transition-all duration-200 flex items-center',
                                    selectedStatus === '{{ $status }}' ? 
                                        '{{ $status === 'pending' ? 'bg-gray-200 text-gray-800 ring-1 ring-gray-300' : 
                                        ($status === 'in_progress' ? 'bg-blue-100 text-blue-800 ring-1 ring-blue-300' : 
                                        ($status === 'completed' ? 'bg-green-100 text-green-800 ring-1 ring-green-300' : 
                                        'bg-red-100 text-red-800 ring-1 ring-red-300')) }}' : 
                                        '{{ $status === 'pending' ? 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' : 
                                        ($status === 'in_progress' ? 'bg-white text-blue-600 border border-blue-200 hover:bg-blue-50' : 
                                        ($status === 'completed' ? 'bg-white text-green-600 border border-green-200 hover:bg-green-50' : 
                                        'bg-white text-red-600 border border-red-200 hover:bg-red-50')) }}'
                                    ]"
                                >
                                    <!-- Status icon -->
                                    <span x-show="!loading || selectedStatus !== '{{ $status }}'" class="mr-1.5">
                                        @if($status === 'pending')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        @elseif($status === 'in_progress')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        @elseif($status === 'completed')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        @endif
                                    </span>
                                    
                                    <!-- Loading spinner -->
                                    <svg x-show="loading && selectedStatus === '{{ $status }}'" class="animate-spin h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    
                                    <!-- Status label -->
                                    <span class="truncate">
                                        {{ $status === 'pending' ? 'Pending' : 
                                        ($status === 'in_progress' ? 'In Progress' : 
                                        ($status === 'completed' ? 'Done' : 'Cancelled')) }}
                                    </span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes Toggle Section (Collapsible) -->
                    @if(isset($todo->notes))
                    <div class="px-4 py-3 bg-white border-b border-gray-100" x-data="{ showNotes: false }">
                        <button @click="showNotes = !showNotes" class="w-full flex items-center justify-between">
                            <span class="text-xs font-medium text-gray-500">Notes & Comments</span>
                            <div class="flex items-center space-x-1">
                                <span class="text-xs text-indigo-600">{{ $todo->notes()->count() ?? 0 }} Notes</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path :d="showNotes ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                </svg>
                            </div>
                        </button>
                        
                        <!-- Notes Content (Collapsible) -->
                        <div x-show="showNotes" class="mt-2 space-y-2">
                            <button wire:click="openNoteModal({{ $todo->id }})"class="w-full py-2 text-sm text-indigo-600 flex items-center justify-center gap-1 border border-indigo-200 rounded-md bg-indigo-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Comment
                            </button>
                            
                            <!-- Notes would render here -->
                            @if(isset($todo->notes) && $todo->notes()->count() > 0)
                                @foreach($todo->notes()->orderBy('created_at', 'desc')->get() as $note)
                                    <div class="bg-gray-50 rounded-md p-3">
                                        <div class="flex justify-between items-start gap-2">
                                            <p class="text-sm text-gray-900 break-words flex-1">
                                                {{ $note->content }}
                                            </p>
                                            <button class="text-gray-400 hover:text-red-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            Added by {{ $note->user->name }} • {{ $note->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center text-sm text-gray-500 py-2">
                                    No notes yet. Add your first note.
                                </p>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="grid grid-cols-3 divide-x divide-gray-100">
                        <button wire:click="showTaskDetails('{{ $todo->uuid }}')"
                            @click="open = false"
                            class="flex items-center justify-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View
                        </button>

                        <a href="{{ route('todos', ['uuid' => $todo->uuid]) }}"
                            class="flex items-center justify-center px-4 py-3 text-sm font-medium text-blue-600 hover:bg-blue-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>

                        <button wire:confirm="Are you sure you want to delete this todo?"
                            wire:click="deleteTodo('{{ $todo->uuid }}')" @click="open = false"
                            class="flex items-center justify-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
              
                @empty
                    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
                        <div class="mt-6">
                        <a href="{{ route('todos') }}"
                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Todo
            </a>
                        </div>
                    </div>
                @endforelse

                <!-- Pagination -->
                @if($todos->hasPages())
                    <div class="mb-5 mt-4 px-4">
                        {{ $todos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Backdrop -->
    <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
    </div>
    <!-- Modal Content -->
    <div x-data="{
        downloading: false,
        alerts: [],
        init() {
            this.$watch('showModal', value => {
                if (!value) this.todo = null;
            });
        },
        formatDate(date, format = 'full') {
            if (!date) return '';
            const d = new Date(date);
            if (format === 'full') {
                return d.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            }
            return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
        },
        handleDownload(attachmentId) {
            this.downloading = true;
            // Simulate download - replace with your actual download logic
            setTimeout(() => {
                this.downloading = false;
                @this.download(attachmentId).then(() => {
                    this.downloading = false;
                    this.addAlert('File downloaded successfully!', 'success');
                });
    
            }, 1500);
        },
    
        addAlert(message, type = 'success') {
            const alert = { id: Date.now(), message, type };
            this.alerts.push(alert);
            setTimeout(() => {
                this.alerts = this.alerts.filter(a => a.id !== alert.id);
            }, 3000);
        },
        getStatusClass(status) {
            return {
                'pending': 'bg-gray-100 text-gray-700 ring-gray-600/20',
                'in_progress': 'bg-blue-100 text-blue-700 ring-blue-600/20',
                'completed': 'bg-green-100 text-green-700 ring-green-600/20',
                'cancelled': 'bg-red-100 text-red-700 ring-red-600/20'
            } [status] || 'bg-gray-100 text-gray-700 ring-gray-600/20';
        },
        getPriorityClass(priority) {
            return {
                'high': 'bg-rose-100 text-rose-700 ring-rose-600/20',
                'medium': 'bg-amber-100 text-amber-700 ring-amber-600/20',
                'low': 'bg-emerald-100 text-emerald-700 ring-emerald-600/20'
            } [priority.toLowerCase()] || 'bg-gray-100 text-gray-700 ring-gray-600/20';
        }
    }" x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="showModal = false"></div>

        <!-- Modal Container -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all w-full max-w-4xl">
                <!-- Header Section with Gradient -->
                <div class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-800" x-text="todo?.title"></h3>
                        <button @click="showModal = false"
                            class="rounded-full p-1 text-gray-700 hover:bg-black/10 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="px-6 py-6">
                    <!-- Alerts -->
                    <template x-for="alert in alerts" :key="alert.id">
                        <div :class="{
                            'mb-4 rounded-lg p-4 text-sm ring-1': true,
                            'bg-green-50 text-green-700 ring-green-600/20': alert.type === 'success',
                            'bg-red-50 text-red-700 ring-red-600/20': alert.type === 'error'
                        }"
                            role="alert">
                            <span x-text="alert.message"></span>
                            <button type="button" class="float-right"
                                @click="alerts = alerts.filter(a => a.id !== alert.id)">×</button>
                        </div>
                    </template>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column - Description and Attachments -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Description -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Description</h4>
                                <p class="text-sm text-gray-900" x-text="todo?.description"></p>
                            </div>

                            <!-- Attachments -->
                            <div x-show="todo?.attachments?.length > 0" class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <span x-text="`Attachments (${todo?.attachments?.length})`"></span>
                                    </div>
                                </h4>
                                <ul
                                    class="divide-y divide-gray-200 bg-white rounded-lg overflow-hidden border border-gray-200">
                                    <template x-for="attachment in todo?.attachments" :key="attachment.id">
                                        <li
                                            class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                                            <div class="flex items-center space-x-3">
                                                <svg class="h-6 w-6 text-gray-400" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900"
                                                        x-text="attachment.file_name"></p>
                                                    <p class="text-xs text-gray-500" x-text="attachment.size"></p>
                                                </div>
                                            </div>

                                            <button @click="handleDownload(attachment.id)" :disabled="downloading"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-500 hover:bg-indigo-50 rounded-md transition-colors disabled:opacity-50">
                                                <template x-if="downloading">
                                                    <svg class="animate-spin h-4 w-4 mr-1.5 text-indigo-600"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                </template>
                                                <template x-if="!downloading">
                                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </template>
                                                <span x-text="downloading ? 'Downloading...' : 'Download'"></span>
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <!-- Notes Section -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        <span x-text="`Notes (${todo?.notes?.length || 0})`"></span>
                                    </div>
                                </h4>
                                <!-- Notes List -->
                                <div class="space-y-2">
                                    <template x-if="todo?.notes?.length === 0">
                                        <div class="text-center text-gray-500 text-sm py-4">
                                            No notes available.
                                        </div>
                                    </template>
                                    <template x-for="note in todo?.notes" :key="note.id">
                                        <div class="bg-white rounded-md p-3 border border-gray-200">
                                            <div class="flex justify-between items-start gap-2">
                                                <p class="text-sm text-gray-900 break-words flex-1 line-clamp-2"
                                                    x-text="note.content" :title="note.content"></p>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500">
                                                <span x-text="`Added by ${note.user?.name}`"></span> •
                                                <span class="text-xs text-gray-500 ml-1" x-text="note?.created_at && !note.created_at.includes('T') ? note.created_at : formatReadableDate(note?.created_at, true)"></span>
                                                <span class="mx-1">•</span>
                                                <span class="text-xs text-gray-500 ml-1" x-text="note?.created_at ? extractTimeFromIso(note.created_at) : ''"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>



                        <!-- Right Column - Status, Priority, and Dates -->
                        <div class="space-y-6">
                            <!-- Status and Priority -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                <!-- Status -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Status</h4>
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium ring-1 ring-inset"
                                        :class="{
                                            'bg-yellow-50 text-yellow-800 ring-yellow-600/20': todo
                                                ?.status === 'pending',
                                            'bg-blue-50 text-blue-800 ring-blue-600/20': todo
                                                ?.status === 'in_progress',
                                            'bg-green-50 text-green-800 ring-green-600/20': todo
                                                ?.status === 'completed',
                                            'bg-red-50 text-red-800 ring-red-600/20': todo?.status === 'cancelled'
                                        }">
                                        <span class="relative flex h-2 w-2">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                                :class="{
                                                    'bg-yellow-400': todo?.status === 'pending',
                                                    'bg-blue-400': todo?.status === 'in_progress',
                                                    'bg-green-400': todo?.status === 'completed',
                                                    'bg-red-400': todo?.status === 'cancelled'
                                                }"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2"
                                                :class="{
                                                    'bg-yellow-400': todo?.status === 'pending',
                                                    'bg-blue-400': todo?.status === 'in_progress',
                                                    'bg-green-400': todo?.status === 'completed',
                                                    'bg-red-400': todo?.status === 'cancelled'
                                                }"></span>
                                        </span>
                                        <span
                                            x-text="todo.status
                                                    .split('_')
                                                    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                                                    .join(' ')"></span>
                                    </span>
                                </div>

                                <!-- Priority -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Priority</h4>
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium ring-1 ring-inset"
                                        :class="{
                                            'bg-red-50 text-red-800 ring-red-600/20': todo?.priority === 'high',
                                            'bg-yellow-50 text-yellow-800 ring-yellow-600/20': todo
                                                ?.priority === 'medium',
                                            'bg-green-50 text-green-800 ring-green-600/20': todo
                                                ?.priority === 'low'
                                        }">
                                        <svg class="h-3 w-3"
                                            :class="{
                                                'text-red-600': todo?.priority === 'high',
                                                'text-yellow-600': todo?.priority === 'medium',
                                                'text-green-600': todo?.priority === 'low'
                                            }"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                clip-rule="evenodd" />
                                        </svg>

                                        <span
                                            x-text="todo.priority
                                                    .split('_')
                                                    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                                                    .join(' ')"></span>
                                    </span>
                                </div>
                            </div>
                            <!-- Due Date Information -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                <div>
                                    <div class="text-sm text-gray-900 font-medium">
                                        <div>
                                        <h4 class="text-sm font-medium text-gray-500 mb-2">Due Date</h4>
                                        <div class="text-sm text-gray-900 font-medium">
                                            <!-- For human-readable date format (Wednesday, March 26, 2025) -->
                                            <div x-text="todo?.due_date && !todo.due_date.includes('T') ? todo.due_date : formatReadableDate(todo?.due_date)"></div>
                                            
                                            <!-- For ISO format (extract just the time portion) -->
                                            <div class="text-gray-500 mt-1" 
                                                x-text="todo?.due_date ? extractTimeFromIso(todo.due_date) : ''">
                                            </div>
                                        </div>
                                    </div>

                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Time Remaining</h4>
                                    <div class="text-sm font-medium"
                                        :class="todo?.is_overdue ? 'text-red-600' : 'text-gray-900'"
                                        x-text="todo?.time_remaining">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Information -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-xs text-gray-500">
                            <div class="flex items-center">
                                <span>Created:</span>
                                <span class="font-medium ml-1" x-text="todo?.created_at && !todo.created_at.includes('T') ? todo.created_at : formatReadableDate(todo?.created_at, true)"></span>
                                <span class="mx-1">•</span>
                                <span x-text="todo?.created_at ? extractTimeFromIso(todo.created_at) : ''"></span>
                            </div>
                            
                            <div class="flex items-center">
                                <span>Updated:</span>
                                <span class="font-medium ml-1" x-text="todo?.updated_at && !todo.updated_at.includes('T') ? todo.updated_at : formatReadableDate(todo?.updated_at, true)"></span>
                                <span class="mx-1">•</span>
                                <span x-text="todo?.updated_at ? extractTimeFromIso(todo.updated_at) : ''"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button @click="showModal = false" type="button"
                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div x-data="{ showPreview: false, previewFile: null }" @show-preview.window="showPreview = true; previewFile = $event.detail"
        x-show="showPreview" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>

        <!-- Preview Backdrop -->
        <div class="fixed inset-0 bg-black/75 backdrop-blur-sm"></div>

        <!-- Preview Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-5xl w-full mx-auto">
                <!-- Preview Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900" x-text="previewFile?.file_name"></h3>
                    <button @click="showPreview = false"
                        class="rounded-full p-1 text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Preview Area -->
                <div class="p-4">
                    <!-- PDF Preview -->
                    <template x-if="previewFile?.mime_type === 'application/pdf'">
                        <iframe :src="previewFile?.file_path" class="w-full h-[calc(100vh-300px)] min-h-[600px]"
                            type="application/pdf"></iframe>
                    </template>

                    <!-- Image Preview -->
                    <template x-if="previewFile?.mime_type?.startsWith('image/')">
                        <img :src="previewFile?.file_path" class="max-w-full h-auto mx-auto max-h-[calc(100vh-200px)]"
                            :alt="previewFile?.file_name" />
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ show: @entangle('showNoteModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Background backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl"
                    @click.away="show = false">

                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-base font-semibold text-gray-900">
                                Notes for: {{ $selectedTodo->title ?? '' }}
                            </h3>
                            <button @click="show = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Add Note Form -->
                    <div class="p-4 bg-gray-50">
                        <textarea wire:model.defer="newNote" placeholder="Type your note here..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            rows="3"></textarea>
                        <div class="flex justify-end space-x-2 mt-2">
                            <button wire:click="addNote" wire:loading.attr="disabled"
                                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                <span wire:loading.remove>Add Note</span>
                                <span wire:loading>Adding...</span>
                            </button>
                        </div>
                    </div>

                    <!-- Notes List -->
                    <div class="px-3 py-2">
                            <div class="space-y-3">
                                @if ($selectedTodo)
                                    @forelse($selectedTodo->notes()->with('user')->latest()->get() as $note)
                                        <div class="transform hover:-translate-y-0.5 transition-all duration-200">
                                            <div class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md relative overflow-hidden group">
                                                <!-- Animated gradient background on hover -->
                                                <div class="absolute inset-0 opacity-0 group-hover:opacity-5 transition-opacity duration-300 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                                                
                                                <!-- Gradient top line -->
                                                <div class="h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                                                
                                                <div class="p-3 relative">
                                                    <!-- Header with user info -->
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <!-- Animated avatar -->
                                                        <div class="relative group/avatar">
                                                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center transform group-hover/avatar:scale-110 transition-transform">
                                                                <span class="text-white text-xs font-medium">
                                                                    {{ strtoupper(substr($note->user->name, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center gap-2">
                                                                <h4 class="text-sm font-medium text-gray-900 truncate">{{ $note->user->name }}</h4>
                                                            </div>
                                                            <div class="flex items-center gap-1 mt-0.5">
                                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                <div class="text-xs text-gray-500 flex gap-2">
                                                                    <span>{{ $note->created_at->format('M d, Y') }}</span>
                                                                    <span class="text-gray-400">•</span>
                                                                    <span>{{ $note->created_at->format('h:i A') }}</span>
                                                                    <span class="text-gray-400">•</span>
                                                                    <span class="text-gray-400 italic">{{ $note->created_at->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Animated delete button -->
                                                        <button wire:click="deleteNote('{{ $note->id }}')"
                                                            class="p-1.5 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all duration-200 hover:rotate-12">
                                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Note content with left border -->
                                                    <div class="pl-9 border-l-2 border-gray-100">
                                                        <p class="text-sm text-gray-700 break-words leading-relaxed">{{ $note->content }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-6 bg-white rounded-lg border border-gray-100 shadow-sm">
                                            <div class="w-12 h-12 mx-auto mb-3 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-600 text-sm font-medium">No notes yet</p>
                                            <p class="text-gray-400 text-xs mt-1">Be the first to add a note to this task</p>
                                        </div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
