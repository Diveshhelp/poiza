<div x-data="{
    showModal: false,
    task: null,
    init() {
        this.$wire.on('show-task-details', (data) => {
            console.log(data[0].taskData);
            this.task = data[0].taskData;
            this.showModal = true;
        });
    }
}" @keydown.escape.window="showModal = false">

<div  class="mx-auto py-2 sm:px-6 lg:px-8" wire:key="task-manager-module-{{ time() }}">
        <div>
            <div class="flex flex-wrap items-center justify-between">
                <div class="min-w-0 flex-1 pr-2">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">{{ $moduleTitle }}</h3>
                    <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600 leading-tight sm:leading-relaxed line-clamp-1 sm:line-clamp-none">
                    List all of your tasks and all other members tasks!
                    </p>
                </div>
                <div class="flex shrink-0 sm:ml-4">
                    <a href="{{ route('task-managers') }}"
                    class="inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="mr-1.5 size-4 sm:size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="whitespace-nowrap">Add Task</span>
                    </a>
                </div>
            </div>
            <div class="mt-10 sm:mt-0">
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="px-0 sm:px-0 lg:px-0">
                        @include('livewire.common.messages')

                        @if ($tasks == null)
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">No {{ $moduleTitle }} collections
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new {{ $moduleTitle }}
                                    collection.</p>
                            </div>
                        @else
                        <div class="sm:hidden">
                            <div class="flex p-1 rounded-lg bg-gray-50 shadow-sm overflow-x-auto mx-auto">
                                @foreach ($tabs as $key => $tab)
                                    <button 
                                        wire:click="$set('currentTab', '{{ $key }}')" 
                                        class="flex items-center justify-center px-4 py-2.5 mx-0.5 rounded-md min-w-20 transition-all duration-200 {{ $currentTab === $key ? 'bg-white shadow-md text-indigo-700 transform scale-105' : 'text-gray-600 hover:bg-gray-100' }}"
                                    >
                                        <div class="flex flex-col items-center">
                                            <span class="font-medium text-xs truncate w-full text-center">{{ $tab['name'] }}</span>
                                            <span class="mt-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold rounded-full {{ $currentTab === $key ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-200 text-gray-700' }}">
                                                {{ $tabCounts[$key] }}
                                            </span>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-1 mt-5 bg-white rounded-lg shadow cursor-pointer" >
                            <!-- Filter Toggle Button -->
                            <div class="p-4 border-b border-gray-200" wire:click="$toggle('showFilters')">
                                <button class="flex items-center justify-between w-full text-sm text-gray-600 hover:text-gray-900">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                        {{ $showFilters ? 'Hide Filters' : 'Show Filters' }}
                                        @if (array_filter($filters))
                                            <span class="ml-2 text-xs bg-primary text-white px-2 py-0.5 rounded-full">
                                                {{ count(array_filter($filters)) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Added rotating arrow -->
                                    <svg class="w-5 h-5 transform transition-transform duration-200" 
                                        :class="{'rotate-180': $wire.showFilters}"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" 
                                            stroke-linejoin="round" 
                                            stroke-width="2" 
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Filter Panel -->
                            <div x-show="$wire.showFilters" 
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="p-4 border-b border-gray-200">
                                <div class="grid grid-cols-2 md:grid-cols-6 lg:grid-cols-6 gap-4">
                                    <!-- Search by Title -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                        <input wire:model.debounce.300ms="filters.search" 
                                            type="text"
                                            placeholder="Search by title..."
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">
                                    </div>

                                    <!-- Status Filter -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select wire:model="filters.status"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="">All Statuses</option>
                                            <option value="not_started">Not Started</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="done">Done</option>
                                            <option value="delayed">Delayed</option>
                                        </select>
                                    </div>

                                    <!-- Priority Filter -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                        <select wire:model="filters.priority"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="">All Priorities</option>
                                            <option value="high">High</option>
                                            <option value="medium">Medium</option>
                                            <option value="low">Low</option>
                                        </select>
                                    </div>

                                    <!-- Department Filter -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                        <select wire:model="filters.department"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="">All Departments</option>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Date Range Filter -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                                        <select wire:model="filters.dateRange"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="">All Dates</option>
                                            <option value="today">Due Today</option>
                                            <option value="this_week">Due This Week</option>
                                            <option value="next_week">Due Next Week</option>
                                            <option value="overdue">Overdue</option>
                                        </select>
                                    </div>

                                    <!-- Assignee Filter -->
                                    @php
                                        $userRoles = explode(',', auth()->user()->user_role);
                                        $isAdmin = in_array('1', $userRoles) || in_array('2', $userRoles);
                                    @endphp
                                    @if($isAdmin)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Assignee</label>
                                        <select wire:model="filters.assignee"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="">All Assignees</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                </div>

                                <!-- Filter Actions -->
                                <div class="mt-4 flex justify-end space-x-3">
                                    <button wire:click="resetFilters" 
                                            type="button"
                                            class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                            Clear
                                    </button>
                                    <button wire:click="applyFilters" 
                                            type="button"
                                            class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                                Search
                                    </button>
                                </div>
                            </div>
                        </div>
                   
                        <div class="hidden sm:block">
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8">
                                    @foreach ($tabs as $key => $tab)
                                        <button
                                            wire:click="$set('currentTab', '{{ $key }}')"
                                            class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium {{ $currentTab === $key 
                                                ? 'border-indigo-500 text-indigo-600' 
                                                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}"
                                        >
                                            {{ $tab['name'] }}
                                            <span class="ml-2 rounded-full bg-{{ $currentTab === $key ? 'indigo' : 'gray' }}-100 px-2.5 py-0.5 text-xs font-medium text-{{ $currentTab === $key ? 'indigo' : 'gray' }}-600">
                                                {{ $tabCounts[$key] }}
                                            </span>
                                        </button>
                                    @endforeach
                                </nav>
                            </div>
                        </div>

                        <div class="mt-3 px-4 py-5 bg-white sm:p-1 shadow sm:rounded-tl-md sm:rounded-tr-md overflow-x-auto">
                            <div class="w-full">
                                <!-- Desktop Table View -->
                                <div class="hidden md:block">
                                    <div class="min-h-screen">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Title</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Details</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Department</th>
                                                    
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Assignee</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Deadline</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Priority</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Status</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Notes</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @if(count($tasks)>0)
                                                    @foreach ($tasks as $dataKey => $dataValue)
                                                    <tr
                                                        class="hover:bg-gray-50 cursor-pointer 
                                                        {{ \Carbon\Carbon::parse($dataValue->deadline)->isToday() ? 'bg-red-50' : '' }}
                                                        {{ $dataValue->status === 'done' ? 'bg-green-50' : '' }}
                                                        {{ $dataValue->status === 'delayed' ? 'bg-red-50' : '' }}">

                                                            <!-- Desktop Table Row Content -->
                                                            <td class="py-2 px-2 text-sm">
                                                                <a href="#" wire:click="showTaskDetails({{ $dataValue->id }})" class="flex flex-col hover:bg-gray-50 dark:hover:bg-zinc-700 rounded p-1 transition-colors">
                                                                    <div title="{{ $dataValue->title }}" class="font-medium text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                                                    {{ $dataValue->title }}
                                                                    </div>
                                                                    <div title="Created By & Time" class="text-gray-500 flex items-center gap-1">
                                                                        <span class="inline-flex items-center text-xs">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                            {{ date('d M Y h:i A',strtotime($dataValue->created_at)) }} By  {{ $dataValue->created_user->name ?? '' }}
                                                                        </span>
                                                                    
                                                                    </div>
                                                                </a>
                                                            </td>
                                                            <td wire:click="showTaskDetails({{ $dataValue->id }})"  class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">
                                                                {{ $dataValue->work_detail }}</td>
                                                            <td wire:click="showTaskDetails({{ $dataValue->id }})"  class="px-3 py-4 text-sm text-gray-500">
                                                                {{ $dataValue->department_object->department_name ?? '' }}
                                                            </td>
                                                           
                                                            <td wire:click="showTaskDetails({{ $dataValue->id }})"  class="px-3 py-4 text-sm text-gray-500">
                                                                {{ $dataValue->assign_to->name ?? '' }}</td>
                                                            <td wire:click="showTaskDetails({{ $dataValue->id }})" class="px-3 py-4 text-sm text-gray-500">
                                                                @if($dataValue->deadline)
                                                                    {{ $dataValue->deadline }}
                                                                    @php
                                                                        $deadline = \Carbon\Carbon::parse($dataValue->deadline);
                                                                        $today = \Carbon\Carbon::today();
                                                                        $diffDays = $today->diffInDays($deadline, false);
                                                                    @endphp

                                                                    <p class="ml-1 text-xs {{ $diffDays < 0 ? 'text-red-500' : ($diffDays == 0 ? 'text-amber-500' : 'text-green-500') }}">
                                                                        @if($diffDays > 0)
                                                                            ({{ $diffDays }} {{ $diffDays == 1 ? 'day' : 'days' }} remaining)
                                                                        @elseif($diffDays < 0)
                                                                            ({{ abs($diffDays) }} {{ abs($diffDays) == 1 ? 'day' : 'days' }} overdue)
                                                                        @else
                                                                            (Today)
                                                                        @endif
                                                                    </p>
                                                                @endif
                                                            </td>
                                                            <td wire:click="showTaskDetails({{ $dataValue->id }})"  class="px-3 py-4 text-sm text-gray-500">
                                                                <span
                                                                    class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                                                    {{ ucwords(str_replace('_', ' ', $dataValue->priority ?? '')) }}
                                                                </span>
                                                            </td>
                                                            
                                                                <td class="px-3 py-4 text-sm text-gray-500" x-cloak>
                                                                    <div x-data="{ open: false, loading: false }" class="relative">
                                                                        <!-- Status Badge/Button -->
                                                                        <button @click="open = !open" type="button"
                                                                            :disabled="loading || '{{ $dataValue->status }}' === 'done'"
                                                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset 
                                                                                    @if ($dataValue->status === 'not_started') bg-gray-50 text-gray-700 ring-gray-600/20
                                                                                    @elseif($dataValue->status === 'in_progress') bg-blue-50 text-blue-700 ring-blue-600/20
                                                                                    @elseif($dataValue->status === 'done') bg-green-50 text-green-700 ring-green-600/20
                                                                                    @else bg-red-50 text-red-700 ring-red-600/20 @endif
                                                                                    {{ $dataValue->status === 'done' ? 'cursor-not-allowed opacity-75' : '' }}">
                                                                            <span x-show="!loading">{{ ucwords(str_replace('_', ' ', $dataValue->status ?? '')) }}</span>
                                                                            <!-- Loading Spinner -->
                                                                            <svg x-show="loading" class="animate-spin h-4 w-4 text-current" fill="none" viewBox="0 0 24 24">
                                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                            </svg>
                                                                        </button>

                                                                        <!-- Dropdown Menu - Only show if status is not 'done' -->
                                                                        <div x-show="open && '{{ $dataValue->status }}' !== 'done'" @click.away="open = false"
                                                                            x-transition:enter="transition ease-out duration-100"
                                                                            x-transition:enter-start="transform opacity-0 scale-95"
                                                                            x-transition:enter-end="transform opacity-100 scale-100"
                                                                            x-transition:leave="transition ease-in duration-75"
                                                                            x-transition:leave-start="transform opacity-100 scale-100"
                                                                            x-transition:leave-end="transform opacity-0 scale-95"
                                                                            class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                                            <div class="py-1">
                                                                                <button
                                                                                    wire:click="updateStatus('{{ $dataValue->uuid }}', 'not_started')"
                                                                                    @click="loading = true; open = false"
                                                                                    class="flex items-center w-full px-4 py-2 text-left text-sm hover:bg-gray-100 transition-colors"
                                                                                    :class="{ 'bg-gray-50': '{{ $dataValue->status }}' === 'not_started' }">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                                                                    </svg>
                                                                                    <span>Not Started</span>
                                                                                </button>
                                                                                <button
                                                                                    wire:click="updateStatus('{{ $dataValue->uuid }}', 'in_progress')"
                                                                                    @click="loading = true; open = false"
                                                                                    class="flex items-center w-full px-4 py-2 text-left text-sm hover:bg-gray-100 transition-colors"
                                                                                    :class="{ 'bg-gray-50': '{{ $dataValue->status }}' === 'in_progress' }">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                                                    </svg>
                                                                                    <span>In Progress</span>
                                                                                </button>
                                                                                <button
                                                                                    wire:click="updateStatus('{{ $dataValue->uuid }}', 'done')"
                                                                                    @click="loading = true; open = false"
                                                                                    class="flex items-center w-full px-4 py-2 text-left text-sm hover:bg-gray-100 transition-colors"
                                                                                    :class="{ 'bg-gray-50': '{{ $dataValue->status }}' === 'done' }">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                                    </svg>
                                                                                    <span>Done</span>
                                                                                </button>
                                                                                <button
                                                                                    wire:click="updateStatus('{{ $dataValue->uuid }}', 'delayed')"
                                                                                    @click="loading = true; open = false"
                                                                                    class="flex items-center w-full px-4 py-2 text-left text-sm hover:bg-gray-100 transition-colors"
                                                                                    :class="{ 'bg-gray-50': '{{ $dataValue->status }}' === 'delayed' }">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                    </svg>
                                                                                    <span>Delayed</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-2 py-3 text-sm text-gray-500">
                                                                <!-- Main container with fixed positioning context -->
                                                                <div x-data="{ showNotes: false, showAddNote: false, newNote: '', loading: false }" x-cloak class="relative">
                                                                    <!-- Notes Summary Button -->
                                                                    <button @click="showNotes = !showNotes"
                                                                        class="inline-flex items-center text-indigo-600 hover:text-indigo-900 w-[100px]">
                                                                        <span
                                                                            class="truncate">{{ $dataValue->notes()->count() }}
                                                                            Follow Up</span>
                                                                        <svg class="ml-1 h-4 w-4 flex-shrink-0"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M19 9l-7 7-7-7" />
                                                                        </svg>
                                                                    </button>

                                                                    <!-- Modal Backdrop -->
                                                                    <div x-show="showNotes"
                                                                        x-transition:enter="transition ease-out duration-300"
                                                                        x-transition:enter-start="opacity-0"
                                                                        x-transition:enter-end="opacity-100"
                                                                        x-transition:leave="transition ease-in duration-200"
                                                                        x-transition:leave-start="opacity-100"
                                                                        x-transition:leave-end="opacity-0"
                                                                        class="fixed inset-0 bg-gray-500 bg-opacity-75 z-40"
                                                                        @click="showNotes = false">
                                                                    </div>

                                                                    <!-- Notes Modal -->
                                                                    <div x-show="showNotes"
                                                                        x-transition:enter="transition ease-out duration-300"
                                                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                                        x-transition:leave="transition ease-in duration-200"
                                                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                        class="fixed inset-0 z-50 overflow-y-auto"
                                                                    >

                                                                        <!-- Modal Centering Wrapper -->
                                                                        <div
                                                                            class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                                                            <!-- Modal Content -->
                                                                            <div
                                                                                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                                                <!-- Header with Add Follow Up Button -->
                                                                                <div
                                                                                    class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 px-6 py-4">
                                                                                    <div
                                                                                        class="flex justify-between items-center">
                                                                                        <h3
                                                                                            class="text-base font-semibold text-gray-900">
                                                                                            Follow Up</h3>
                                                                                        <div
                                                                                            class="flex items-center gap-2">
                                                                                            <button
                                                                                                @click="showAddNote = true"
                                                                                                x-show="!showAddNote"
                                                                                                class="text-indigo-600 hover:text-indigo-800 text-sm flex items-center">
                                                                                                <svg class="w-4 h-4 mr-1"
                                                                                                    fill="none"
                                                                                                    stroke="currentColor"
                                                                                                    viewBox="0 0 24 24">
                                                                                                    <path
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round"
                                                                                                        stroke-width="2"
                                                                                                        d="M12 4v16m8-8H4" />
                                                                                                </svg>
                                                                                                Add Follow up
                                                                                            </button>
                                                                                            <button
                                                                                                @click="showNotes = false"
                                                                                                class="text-gray-500 hover:text-gray-700">
                                                                                                <svg class="w-5 h-5"
                                                                                                    fill="none"
                                                                                                    stroke="currentColor"
                                                                                                    viewBox="0 0 24 24">
                                                                                                    <path
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round"
                                                                                                        stroke-width="2"
                                                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                                                </svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Add Follow Up Section -->
                                                                                <div x-show="showAddNote"
                                                                                    x-transition:enter="transition ease-out duration-200"
                                                                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                                                                    x-transition:enter-end="opacity-100 translate-y-0"
                                                                                    class="border-b">
                                                                                    <div class="p-4 bg-gray-50">
                                                                                        <textarea x-model="newNote" placeholder="Type your note here..."
                                                                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                                                            rows="3"></textarea>
                                                                                        <div class="flex justify-end space-x-2 mt-2">
                                                                                            <button
                                                                                                @click="showAddNote = false; newNote = ''"
                                                                                                class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900">
                                                                                                Cancel
                                                                                            </button>
                                                                                            <button
                                                                                                @click="loading = true; $wire.addNote('{{ $dataValue->uuid }}', newNote).then(() => { 
                                                                                                    loading = false; 
                                                                                                    showAddNote = true;
                                                                                                    newNote = ''; 
                                                                                                })"
                                                                                                :disabled="!newNote.trim() || loading"
                                                                                                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                                                                                <span x-show="!loading">Save Follow Up</span>
                                                                                                <svg x-show="loading"
                                                                                                    class="animate-spin h-4 w-4"
                                                                                                    fill="none"
                                                                                                    viewBox="0 0 24 24">
                                                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                                                </svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Notes List -->
                                                                                <div class="px-4 py-3">
                                                                                    <div class="space-y-3">
                                                                                        @forelse($dataValue->notes()->orderBy('created_at', 'desc')->get() as $note)
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
                                                                                            <div x-show="!showAddNote" class="text-center py-6 bg-white rounded-lg border border-gray-100 shadow-sm">
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
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td x-cloak class="relative py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                                                    <button @click="open = !open" type="button"
                                                                        class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                                                                        </svg>
                                                                    </button>
                                                                    <div x-show="open" @click.away="open = false"
                                                                        class="absolute right-0 z-10 mt-2 w-36 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                                        role="menu">
                                                                        <div class="py-1">
                                                                            <button wire:click="showTaskDetails({{ $dataValue->id }})"
                                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                                View Details
                                                                            </button>
                                                                            <a href="{{ route('task-managers-edit', $dataValue->uuid) }}"
                                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                                                role="menuitem">
                                                                                Edit
                                                                            </a>
                                                                            <button wire:confirm="Are you sure you want to delete this task?"
                                                                                wire:click="deleteTask('{{ $dataValue->uuid }}')"
                                                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                                                                role="menuitem">
                                                                                Delete
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="12" class="px-6 py-8 text-center">
                                                            <div class="flex flex-col items-center">
                                                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                <p class="text-gray-600 text-base font-medium">
                                                                    No tasks found in {{ $tabs[$currentTab]['name'] }}
                                                                </p>
                                                                <p class="text-gray-400 text-sm mt-1">
                                                                    Try adjusting your filters or switching to a different tab
                                                                </p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Pagination -->
                                    <div class="px-4 py-3 border-t border-gray-200">
                                        {{ $tasks->links() }}
                                    </div>
                                </div>
                                <!-- Mobile Card View -->
                                <div class="md:hidden space-y-4">
                                    @foreach ($tasks as $dataKey => $dataValue)
                                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                            <!-- Card Header -->
                                            <div class="p-4 border-b border-gray-200">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <a href="#"
                                                            wire:click="showTaskDetails({{ $dataValue->id }})">
                                                            <h3 class="text-base font-medium text-gray-900">
                                                                {{ $dataValue->title }}</h3>
                                                            <p class="mt-1 text-xs text-gray-500">
                                                                Created {{ $dataValue->created_at->diffForHumans() }}
                                                                by {{ $dataValue->created_user->name ?? '' }}
                                                            </p>
                                                        </a>
                                                    </div>
                                                    <!-- Status & Actions -->
                                                    <div class="flex items-start gap-2 ml-4" x-data="{ open: false }"
                                                        x-cloak>
                                                        <!-- Status Badge -->
                                                        <!-- Replace the static status badge in the mobile view with this interactive one -->
                                                        <div x-data="{ open: false, loading: false }" class="relative">
                                                            <!-- Status Badge/Button -->
                                                            <button @click="open = !open" type="button"
                                                                :disabled="loading"
                                                                class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset 
                                                                                    @if ($dataValue->status === 'not_started') bg-gray-50 text-gray-700 ring-gray-600/20
                                                                                    @elseif($dataValue->status === 'in_progress') bg-blue-50 text-blue-700 ring-blue-600/20
                                                                                    @elseif($dataValue->status === 'done') bg-green-50 text-green-700 ring-green-600/20
                                                                                    @else bg-red-50 text-red-700 ring-red-600/20 @endif">
                                                                <span
                                                                    x-show="!loading"> {{ ucwords(str_replace('_', ' ', $dataValue->status ?? '')) }}</span>
                                                                    
                                                                <!-- Loading Spinner -->
                                                                <svg x-show="loading"
                                                                    class="animate-spin h-4 w-4 text-current"
                                                                    fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                    </path>
                                                                </svg>
                                                            </button>

                                                            <!-- Status Dropdown Menu -->
                                                            <div x-show="open" @click.away="open = false"
                                                                x-transition:enter="transition ease-out duration-100"
                                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                                x-transition:leave="transition ease-in duration-75"
                                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                                class="absolute right-0 z-20 mt-2 w-40 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                                <div class="py-1">
                                                                    <button
                                                                        wire:click="updateStatus('{{ $dataValue->uuid }}', 'not_started')"
                                                                        @click="loading = true; open = false"
                                                                        class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                                                                        :class="{ 'bg-gray-50': '{{ $dataValue->status }}'
                                                                            === 'not_started' }">
                                                                        Not Started
                                                                    </button>
                                                                    <button
                                                                        wire:click="updateStatus('{{ $dataValue->uuid }}', 'in_progress')"
                                                                        @click="loading = true; open = false"
                                                                        class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                                                                        :class="{ 'bg-gray-50': '{{ $dataValue->status }}'
                                                                            === 'in_progress' }">
                                                                        In Progress
                                                                    </button>
                                                                    <button
                                                                        wire:click="updateStatus('{{ $dataValue->uuid }}', 'done')"
                                                                        @click="loading = true; open = false"
                                                                        class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                                                                        :class="{ 'bg-gray-50': '{{ $dataValue->status }}'
                                                                            === 'done' }">
                                                                        Done
                                                                    </button>
                                                                    <button
                                                                        wire:click="updateStatus('{{ $dataValue->uuid }}', 'delayed')"
                                                                        @click="loading = true; open = false"
                                                                        class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                                                                        :class="{ 'bg-gray-50': '{{ $dataValue->status }}'
                                                                            === 'delayed' }">
                                                                        Delayed
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Action Menu -->
                                                        <button @click="open = !open" type="button"
                                                            class="p-1 text-gray-400 hover:text-gray-500">
                                                            <svg class="h-5 w-5" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path
                                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                            </svg>
                                                        </button>
                                                        <!-- Dropdown -->
                                                        <div x-show="open" @click.away="open = false"
                                                            class="absolute right-4 z-10 mt-8 w-36 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                                            <div class="py-1">
                                                            <button wire:click="showTaskDetails({{ $dataValue->id }})"
                                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                                View Details
                                                                            </button>
                                                                <a href="{{ route('task-managers-edit', $dataValue->uuid) }}"
                                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                                    role="menuitem">
                                                                    Edit
                                                                </a>
                                                                <button wire:confirm="Are you sure you want to delete this task?"
                                                                                wire:click="deleteTask('{{ $dataValue->uuid }}')"
                                                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                                                                role="menuitem">
                                                                                Delete
                                                                            </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Card Content -->
                                            <div class="divide-y divide-gray-200">
                                                <!-- Details Section -->
                                                <div class="p-4">
                                                    <h4 class="text-xs font-medium text-gray-500 mb-2">Details</h4>
                                                    <p class="text-sm text-gray-900">{{ $dataValue->work_detail }}</p>
                                                </div>

                                                <!-- Key Information Grid -->
                                                <div class="p-4 grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label
                                                            class="text-xs font-medium text-gray-500">Department</label>
                                                        <p class="text-sm text-gray-900">
                                                            {{ $dataValue->department_object->department_name ?? '-' }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-medium text-gray-500">Type</label>
                                                        <p class="text-sm text-gray-900">
                                                            {{ $dataValue->work_type ?? '-' }}</p>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-medium text-gray-500">Assigned
                                                            To</label>
                                                        <p class="text-sm text-gray-900">
                                                            {{ $dataValue->assign_to->name ?? '-' }}</p>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-xs font-medium text-gray-500">Deadline</label>
                                                        <p class="text-sm text-gray-900">
                                                            {{ $dataValue->deadline ?? '-' }}</p>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-xs font-medium text-gray-500">Priority</label>
                                                        <span
                                                            class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                                            {{ $dataValue->priority ?? '-' }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-medium text-gray-500">Repeat
                                                            On</label>
                                                        <p class="text-sm text-gray-900">
                                                            {{ $dataValue->repetition ?? '-' }}</p>
                                                    </div>

                                                </div>

                                                <!-- Notes Section -->
                                                <div class="p-4" x-data="{ showNotes: false, showAddNote: false, newNote: '', loading: false }">
                                                    <div class="flex justify-between items-center mb-2">
                                                        <h4 class="text-xs font-medium text-gray-500">Notes</h4>
                                                        <button @click="showNotes = !showNotes"
                                                            class="text-sm text-indigo-600 flex items-center gap-1">
                                                            <span>{{ $dataValue->notes()->count() }} Notes</span>
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    :d="showNotes ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div x-show="showNotes" class="space-y-3">
                                                        <!-- Add Follow Up Button -->
                                                        <button @click="showAddNote = true" x-show="!showAddNote"
                                                            class="w-full py-2 text-sm text-indigo-600 flex items-center justify-center gap-1 border border-indigo-200 rounded-md bg-indigo-50">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                            Add Follow Up
                                                        </button>

                                                        <!-- Add Follow Up Form -->
                                                        <div x-show="showAddNote" class="space-y-2">
                                                            <textarea x-model="newNote" placeholder="Type your note here..."
                                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                                rows="3"></textarea>
                                                            <div class="flex justify-end gap-2">
                                                                <button @click="showAddNote = false; newNote = ''"
                                                                    class="px-3 py-1.5 text-sm text-gray-600">
                                                                    Cancel
                                                                </button>
                                                                <button
                                                                    @click="loading = true; $wire.addNote('{{ $dataValue->uuid }}', newNote).then(() => { 
                                                                        loading = false; 
                                                                        showAddNote = false;
                                                                        newNote = ''; 
                                                                    })"
                                                                    :disabled="!newNote.trim() || loading"
                                                                    class="px-3 py-1.5 text-sm text-white bg-indigo-600 rounded-md disabled:opacity-50 flex items-center">
                                                                    <span x-show="!loading">Save</span>
                                                                    <svg x-show="loading" class="animate-spin h-4 w-4"
                                                                        fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12"
                                                                            cy="12" r="10"
                                                                            stroke="currentColor" stroke-width="4" />
                                                                        <path class="opacity-75" fill="currentColor"
                                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <!-- Notes List -->
                                                        <div class="space-y-2">
                                                            @forelse($dataValue->notes()->orderBy('created_at', 'desc')->get() as $note)
                                                                <div class="bg-gray-50 rounded-md p-3">
                                                                    <div
                                                                        class="flex justify-between items-start gap-2">
                                                                        <p
                                                                            class="text-sm text-gray-900 break-words flex-1">
                                                                            {{ $note->content }}</p>
                                                                        <button
                                                                            wire:click="deleteNote('{{ $note->id }}')"
                                                                            class="text-gray-400 hover:text-red-600">
                                                                            <svg class="h-4 w-4" fill="none"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M6 18L18 6M6 6l12 12" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                    <div class="mt-1 text-xs text-gray-500">
                                                                        Added by {{ $note->user->name }} •
                                                                        {{ $note->created_at->diffForHumans() }}
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <p class="text-center text-sm text-gray-500 py-2">
                                                                    No notes yet. Add your first note.
                                                                </p>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="px-4 py-3 border-t border-gray-200">
                                        {{ $tasks->links() }}
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
        <template x-if="showModal">
            <div x-data="{ 
                downloading: false,
                alerts: [],
                handleDownload(id) {
                    this.downloading = true;
                    $wire.download(id)
                        .then(() => {
                            this.downloading = false;
                            this.addAlert('File downloaded successfully!', 'success');
                        })
                        .catch(() => {
                            this.downloading = false;
                            this.addAlert('Download failed. Please try again.', 'error');
                        });
                },
                addAlert(message, type = 'success') {
                    const alert = { id: Date.now(), message, type };
                    this.alerts.push(alert);
                    setTimeout(() => {
                        this.alerts = this.alerts.filter(a => a.id !== alert.id);
                    }, 3000);
                }
                }" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>

                <!-- Modal Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showModal = false"></div>

                <!-- Modal Content -->
                <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
                   
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">

                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 px-6 py-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900" x-text="task.title"></h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        <span>Created </span>
                                        <span x-text="task.created_at"></span>
                                        <span> by </span>
                                        <span x-text="task.created_user ? task.created_user.name : '-'"></span>
                                    </p>
                                </div>
                                <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-4 py-3">
                            <template x-if="task">
                                <div class="space-y-6">
                                    <!-- Status and Priority Section -->
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                        <!-- Left Side: Status -->
                                        <div class="flex items-center mb-4 sm:mb-0">
                                            <span class="text-sm font-medium text-gray-500 mr-2">Status:</span>
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold transition-all duration-200"
                                                :class="{
                                                    'bg-gray-50 text-gray-800 ring-1 ring-inset ring-gray-600/20 hover:bg-gray-100': task
                                                        .status === 'not_started',
                                                    'bg-blue-50 text-blue-800 ring-1 ring-inset ring-blue-600/20 hover:bg-blue-100': task
                                                        .status === 'in_progress',
                                                    'bg-green-50 text-green-800 ring-1 ring-inset ring-green-600/20 hover:bg-green-100': task
                                                        .status === 'done',
                                                    'bg-red-50 text-red-800 ring-1 ring-inset ring-red-600/20 hover:bg-red-100': task
                                                        .status === 'delayed'
                                                }">
                                                <span class="relative flex h-2 w-2"
                                                    :class="{
                                                        'text-gray-400': task.status === 'not_started',
                                                        'text-blue-400': task.status === 'in_progress',
                                                        'text-green-400': task.status === 'done',
                                                        'text-red-400': task.status === 'delayed'
                                                    }">
                                                    <span
                                                        class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                                        :class="{
                                                            'bg-gray-400': task.status === 'not_started',
                                                            'bg-blue-400': task.status === 'in_progress',
                                                            'bg-green-400': task.status === 'done',
                                                            'bg-red-400': task.status === 'delayed'
                                                        }"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2"
                                                        :class="{
                                                            'bg-gray-400': task.status === 'not_started',
                                                            'bg-blue-400': task.status === 'in_progress',
                                                            'bg-green-400': task.status === 'done',
                                                            'bg-red-400': task.status === 'delayed'
                                                        }"></span>
                                                </span>
                                                <span
                                                    x-text="task.status.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ')"></span>
                                            </span>
                                        </div>

                                        <!-- Right Side: Priority -->
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-500 mr-2">Priority:</span>
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold transition-all duration-200"
                                                :class="{
                                                    'bg-red-50 text-red-800 ring-1 ring-inset ring-red-600/20 hover:bg-red-100': task
                                                        .priority === 'highest',
                                                    'bg-orange-50 text-orange-800 ring-1 ring-inset ring-orange-600/20 hover:bg-orange-100': task
                                                        .priority === 'high',
                                                    'bg-green-50 text-green-800 ring-1 ring-inset ring-green-600/20 hover:bg-green-100': task
                                                        .priority === 'low',
                                                    'bg-gray-50 text-gray-800 ring-1 ring-inset ring-gray-600/20 hover:bg-gray-100': task
                                                        .priority === 'very_low'
                                                }">
                                                <svg class="h-3 w-3"
                                                    :class="{
                                                        'text-red-600': task.priority === 'highest',
                                                        'text-orange-600': task.priority === 'high',
                                                        'text-green-600': task.priority === 'low',
                                                        'text-gray-600': task.priority === 'very_low'
                                                    }"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span
                                                    x-text="task.priority.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ')"></span>
                                            </span>
                                        </div>
                                    </div>


                                    <!-- Basic Information -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Basic Information</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Department:</span>
                                                <span class="ml-2 text-sm text-gray-900"
                                                    x-text="task.department_object ? task.department_object.department_name : '-'"></span>
                                            </div>
                                            <div class="space-y-2">
                                                <span class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    Sub Departments
                                                </span>

                                                <div class="ml-6">
                                                    <template x-if="task.sub_departments_list && task.sub_departments_list.length">
                                                        <div class="flex flex-wrap gap-2">
                                                            <template x-for="subDept in task.sub_departments_list" :key="subDept.id">
                                                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium 
                                                                        bg-gradient-to-r from-blue-50 to-indigo-50 
                                                                        text-blue-700 border border-blue-200 
                                                                        shadow-sm hover:shadow-md transition-all duration-200
                                                                        group">
                                                                    <svg class="w-3 h-3 mr-2 text-blue-500 group-hover:text-blue-600" 
                                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                            d="M9 5l7 7-7 7"/>
                                                                    </svg>
                                                                    <span x-text="subDept.name" 
                                                                        class="group-hover:text-blue-800 transition-colors duration-200">
                                                                    </span>
                                                                </span>
                                                            </template>
                                                        </div>
                                                    </template>
                                                    
                                                    <template x-if="!task.sub_departments_list || !task.sub_departments_list.length">
                                                        <div class="flex items-center text-gray-400 italic px-3 py-2 bg-gray-50 rounded-lg">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <span>No sub-departments assigned</span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Assigned To:</span>
                                                <span class="ml-2 text-sm text-gray-900"
                                                    x-text="task.assign_to ? task.assign_to.name : '-'"></span>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Deadline:</span>
                                                <span class="ml-2 text-sm text-gray-900"
                                                    x-text="task.deadline || '-'"></span>
                                            </div>
                                            <div>
                                            <span class="text-sm font-medium text-gray-500">Work Type:</span>
                                            <template x-if="task.work_type">
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                                    :class="{
                                                        'bg-blue-50 text-blue-700 border border-blue-200': task.work_type === 'routine',
                                                        'bg-green-50 text-green-700 border border-green-200': task.work_type === 'easy',
                                                        'bg-amber-50 text-amber-700 border border-amber-200': task.work_type === 'medium',
                                                        'bg-red-50 text-red-700 border border-red-200': task.work_type === 'hard'
                                                    }">
                                                    <span x-text="task.work_type.charAt(0).toUpperCase() + task.work_type.slice(1)"></span>
                                                </span>
                                            </template>
                                            <template x-if="!task.work_type">
                                                <span class="ml-2 text-sm text-gray-400">-</span>
                                            </template>
                                        </div>
                                        </div>
                                    </div>

                                    <!-- Work Details -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Work Details</h4>
                                        <p class="text-sm text-gray-700 whitespace-pre-wrap"
                                            x-text="task.work_detail || '-'"></p>
                                    </div>

                                    <!-- Repetition Section -->
                                      <!-- Task Repetition -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Task Repetition</h4>
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-500">Frequency:</span>
                                            <span class="text-gray-900 ml-2" x-text="task.repetition_data.frequency_label"></span>
                                            
                                            <template x-if="task.repetition_data.frequency !== 'no'">
                                                <div class="mt-2 text-xs text-gray-600">
                                                    <template x-if="task.repetition_data.frequency === 'weekly' && task.repetition_data.days">
                                                        <div>
                                                            <span class="font-medium">Repeats on:</span>
                                                            <span x-text="task.repetition_data.days.join(', ')"></span>
                                                        </div>
                                                    </template>
                                                    
                                                    <template x-if="task.repetition_data.frequency === 'monthly' && task.repetition_data.day">
                                                        <div>
                                                            <span class="font-medium">Day of month:</span>
                                                            <span x-text="task.repetition_data.day"></span>
                                                        </div>
                                                    </template>
                                                    
                                                    <template x-if="task.repetition_data.frequency === 'quarterly' && task.repetition_data.quarter">
                                                        <div>
                                                            <span class="font-medium">Quarter:</span>
                                                            <span x-text="task.repetition_data.quarter"></span>
                                                        </div>
                                                    </template>
                                                    
                                                    <template x-if="task.repetition_data.frequency === 'half_yearly' && task.repetition_data.half">
                                                        <div>
                                                            <span class="font-medium">Period:</span>
                                                            <span x-text="task.repetition_data.half"></span>
                                                        </div>
                                                    </template>
                                                    
                                                    <!-- Display repeat until information -->
                                                    <div class="mt-1">
                                                        <span class="font-medium">Duration:</span>
                                                        <span x-text="task.repetition_data.until_text"></span>
                                                        <template x-if="task.repetition_data.progress">
                                                            <span class="ml-1" x-text="'(' + task.repetition_data.progress + ')'"></span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                     <!-- Repetition Section -->
                                     <!-- Status History Component -->
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                        <h4 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Task Status History
                                        </h4>

                                        <div class="space-y-6">
                                            <template x-if="task.status_histories && task.status_histories.length">
                                                <div class="divide-y divide-gray-100">
                                                    <template x-for="history in task.status_histories" :key="history.id">
                                                        <div class="py-4 first:pt-0 last:pb-0">
                                                            <div class="flex items-start space-x-3">
                                                                <div class="flex-1">
                                                                    <div class="flex items-center space-x-2">
                                                                        <span 
                                                                            class="px-2.5 py-0.5 rounded-full text-sm font-medium"
                                                                            :class="{
                                                                                'bg-gray-100 text-gray-800': history.old_status === 'not_started',
                                                                                'bg-blue-100 text-blue-800': history.old_status === 'in_progress',
                                                                                'bg-green-100 text-green-800': history.old_status === 'done',
                                                                                'bg-red-100 text-red-800': history.old_status === 'delayed'
                                                                            }"
                                                                            x-text="history.old_status">
                                                                        </span>

                                                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                                        </svg>

                                                                        <span 
                                                                            class="px-2.5 py-0.5 rounded-full text-sm font-medium"
                                                                            :class="{
                                                                                'bg-gray-100 text-gray-800': history.new_status === 'not_started',
                                                                                'bg-blue-100 text-blue-800': history.new_status === 'In Progress',
                                                                                'bg-green-100 text-green-800': history.new_status === 'done',
                                                                                'bg-red-100 text-red-800': history.new_status === 'delayed'
                                                                            }"
                                                                            x-text="history.new_status">
                                                                        </span>
                                                                    </div>

                                                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                                                        <span class="font-medium ml-1" x-text="history?.created_at && !history.created_at.includes('T') ? history.created_at : formatReadableDate(history?.created_at, true)"></span>
                                                                        <span class="mx-1">•</span>
                                                                        <span x-text="history?.created_at ? extractTimeFromIso(history.created_at) : ''"></span>
                                                                    </div>

                                                                    <template x-if="history.remarks">
                                                                        <p class="mt-2 text-sm text-gray-600" x-text="history.remarks"></p>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>

                                            <template x-if="!task.status_histories || !task.status_histories.length">
                                                <div class="text-center py-6">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    <p class="mt-2 text-sm text-gray-500">No status changes recorded yet</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-2 0a6 6 0 11-12 0 6 6 0 0112 0zm-7 4a1 1 0 100-2 1 1 0 000 2zm0-8a1 1 0 100-2 1 1 0 000 2zm0 4a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Task Comments
                                            </h4>
                                            <span class="text-xs text-gray-500"
                                                x-text="`${task.notes.length} comments`"></span>
                                        </div>

                                        <div class="space-y-2">
                                            <template x-for="note in task.notes" :key="note.id">
                                                <div
                                                    class="group bg-gray-50 hover:bg-gray-100 rounded-md p-3 transition-all duration-200">
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div class="flex-grow">
                                                            <div class="flex items-center gap-2 mb-1">
                                                                <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center text-xs font-medium"
                                                                    x-text="note.user.name.charAt(0).toUpperCase()">
                                                                </div>
                                                                <div>
                                                                    <span class="text-xs font-medium text-gray-900"
                                                                        x-text="note.user.name"></span>
                                                                    <span class="text-xs text-gray-500 ml-1" x-text="note?.created_at && !note.created_at.includes('T') ? note.created_at : formatReadableDate(note?.created_at, true)"></span>
                                                                    <span class="mx-1">•</span>
                                                                    <span class="text-xs text-gray-500 ml-1" x-text="note?.created_at ? extractTimeFromIso(note.created_at) : ''"></span>
                                                                </div>
                                                            </div>
                                                            <p class="text-xs text-gray-700 whitespace-pre-wrap"
                                                                x-text="note.content"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="!task.notes.length">
                                                <div class="text-center py-3">
                                                    <p class="text-gray-500 text-xs">No comments yet</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Attachments Section -->
                                    <template x-if="task.task_images && task.task_images.length > 0">
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Attachments</h4>

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
                                            <ul class="divide-y divide-gray-200">
                                                <template x-for="material in task.task_images" :key="material.id">
                                                    <li class="py-3">
                                                        <div class="flex items-center space-x-3">
                                                            <!-- File Icon based on mime type -->
                                                            <div class="flex-shrink-0">
                                                                <template
                                                                    x-if="material.mime_type && material.mime_type.startsWith('image/')">
                                                                    <svg class="h-5 w-5 text-blue-400"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </template>
                                                                <template
                                                                    x-if="material.mime_type && material.mime_type.startsWith('application/pdf')">
                                                                    <svg class="h-5 w-5 text-red-400"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V7.875L14.25 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z"
                                                                            clip-rule="evenodd" />
                                                                        <path
                                                                            d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
                                                                    </svg>
                                                                </template>
                                                                <template
                                                                    x-if="material.mime_type && !material.mime_type.startsWith('image/') && !material.mime_type.startsWith('application/pdf')">
                                                                    <svg class="h-5 w-5 text-gray-400"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zm6.905 9.97a.75.75 0 00-1.06 0l-3 3a.75.75 0 101.06 1.06l1.72-1.72V18a.75.75 0 001.5 0v-4.19l1.72 1.72a.75.75 0 101.06-1.06l-3-3z"
                                                                            clip-rule="evenodd" />
                                                                        <path
                                                                            d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
                                                                    </svg>
                                                                </template>
                                                            </div>

                                                            <!-- File Details -->
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center">
                                                                    <p class="text-sm font-medium text-gray-900 truncate"
                                                                        x-text="material.original_name"></p>
                                                                    <span class="ml-2 text-xs text-gray-500"
                                                                        x-text="'(' + (material.size ? (material.size / (1024 * 1024)).toFixed(2) + ' MB' : 'Size unknown') + ')'"></span>
                                                                </div>
                                                                <p class="text-xs text-gray-500 mt-0.5"
                                                                    x-text="material.mime_type"></p>
                                                            </div>

                                                            <!-- Actions -->
                                                            <div class="flex-shrink-0 flex space-x-2">
                                                                <button 
                                                                    @click="handleDownload(material.id)"
                                                                    :disabled="downloading"
                                                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-500 hover:bg-indigo-50 rounded-md transition-colors disabled:opacity-50"
                                                                >
                                                                    <template x-if="downloading">
                                                                        <svg class="animate-spin h-4 w-4 mr-1.5 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                        </svg>
                                                                    </template>
                                                                    <template x-if="!downloading">
                                                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                        </svg>
                                                                    </template>
                                                                    <span x-text="downloading ? 'Downloading...' : 'Download'"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </template>
                                </div>
                            </template>
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
        </template>

</div>
