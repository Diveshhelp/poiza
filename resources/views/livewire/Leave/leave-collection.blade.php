<div  class="mx-auto py-5 sm:px-6 lg:px-8">
    <!-- Leave Collection -->
    <div class="w-full mx-auto">
        <!-- Page Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-medium text-gray-900">{{ $moduleTitle }} List</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Manage all leave applications and approvals
                </p>
            </div>

            <div class="mt-4 flex md:ml-4 md:mt-0">
                <a href="{{ route('leave.index') }}"
                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Leave
                </a>
            </div>
        </div>

        <!-- Search Filters -->
        <div x-data="{ open: false }" class="mt-4 bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-3 border-b flex items-center justify-between cursor-pointer" @click="open = !open">
                <h1 class="text-lg font-medium text-gray-700">Filters</h1>
                <button class="text-gray-400 hover:text-gray-500">
                    <span x-show="!open">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                    <span x-show="open">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </span>
                </button>
            </div>

            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-4 py-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Employee Filter - Only visible to Super Admin and Admin -->
                    @php
                        $userRoles = explode(',', auth()->user()->user_role);
                        $isAdmin = in_array('1', $userRoles) || in_array('2', $userRoles);
                    @endphp

                    @if($isAdmin)
                        <div>
                            <label for="employee" class="block text-sm font-medium text-gray-700">Employee</label>
                            <select id="status" id="employee" wire:model="filterEmployee"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">All</option>
                                @foreach ($this->userList as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Start Date Filter -->
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" id="startDate" wire:model="filterStartDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        @error('filterStartDate')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- End Date Filter -->
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" id="endDate" wire:model="filterEndDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        @error('filterEndDate')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" wire:model="filterStatus"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All</option>
                            @foreach ($this->status_list as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reason Filter -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                        <input type="text" id="reason" wire:model="filterReason"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Search in reason">
                    </div>

                    <!-- Total Days Filter -->
                    <div>
                        <label for="totalDays" class="block text-sm font-medium text-gray-700">Total Days</label>
                        <input type="number" step="0.5" id="totalDays" wire:model="filterTotalDays"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Ex: 2">
                        @error('filterTotalDays')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-3">
                    <button wire:click="resetSearch" type="button"
                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        Clear
                    </button>

                    <button wire:click="searchLeaves" type="button"
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
<!-- Table View for Leave Data -->
<div class=" shadow-md rounded-lg border border-gray-200">
    <table class="w-full divide-y divide-gray-200">
        <!-- Table Header -->
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Employee
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date Range
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Duration
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Reason
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Files
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <!-- Table Body -->
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($leaves as $leave)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <!-- Employee Name -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center text-white">
                                <span>{{ strtoupper(substr($leave->user->name ?? 'UN', 0, 2)) }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $leave->user->name ?? 'Unknown' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Requested: {{ $leave->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    </td>
                    
                    <!-- Date Range -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                            @if ($leave->start_date != $leave->end_date)
                                <span class="text-gray-500">to</span>
                                <br class="md:hidden">
                                {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                            @endif
                        </div>
                    </td>
                    
                    <!-- Duration -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $leave->total_days }} {{ $leave->total_days == 1 ? 'day' : 'days' }}
                            @if ($leave->is_full_day == 'no')
                                <span class="text-xs text-indigo-600">({{ ucfirst(str_replace('_', ' ', $leave->leave_half)) }})</span>
                            @endif
                        </div>
                    </td>
                    
                    <!-- Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div x-data="{
                            open: false,
                            loading: false,
                            status: '{{ $leave->status }}',
                            approvedBy: '{{ $leave->approvedBy->name ?? null }}',
                            updateStatus(newStatus) {
                                this.loading = true;
                                this.status = newStatus;
                                this.open = false;
                                
                                if (newStatus !== 'approved') {
                                    this.approvedBy = null;
                                } else {
                                    this.approvedBy = '{{ Auth::user()->name }}';
                                }
                                
                                setTimeout(() => {
                                    this.loading = false;
                                }, 500);
                            }
                        }" class="relative"
                            @status-updated.window="if ($event.detail.uuid === '{{ $leave->uuid }}') { 
                                updateStatus($event.detail.status);
                            }">
                            <button 
                                @click="open = !open" 
                                :disabled="loading || status !== 'pending'"
                                type="button"
                                class="inline-flex items-center rounded-md px-2.5 py-1.5 text-sm font-medium ring-1 ring-inset transition-all duration-200"
                                :class="{
                                    'bg-yellow-50 text-yellow-700 ring-yellow-600/20 hover:bg-yellow-100': status === 'pending',
                                    'bg-green-50 text-green-700 ring-green-600/20': status === 'approved',
                                    'bg-red-50 text-red-700 ring-red-600/20': status === 'rejected',
                                    'bg-gray-50 text-gray-700 ring-gray-600/20': status === 'cancelled',
                                    'opacity-60 cursor-not-allowed': loading || status !== 'pending'
                                }">
                                <span x-show="!loading" 
                                    x-text="status.charAt(0).toUpperCase() + status.slice(1)"
                                    class="flex items-center gap-1">
                                </span>
                                <!-- Loading Spinner -->
                                <svg x-show="loading" 
                                    class="animate-spin h-4 w-4 text-current" 
                                    fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                            
                            <!-- Status Dropdown Menu -->
                            <div x-cloak x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <!-- Status Options -->
                                <div class="py-1">
                                    <!-- Pending -->
                                    <button wire:click="updateStatus('{{ $leave->uuid }}', 'pending')"
                                        @click="updateStatus('pending')"
                                        class="flex items-center w-full px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50"
                                        :class="{ 'bg-yellow-50': status === 'pending' }"
                                        :disabled="status !== 'pending'">
                                        <svg class="mr-3 h-4 w-4 text-yellow-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Pending
                                    </button>

                                    <!-- Approve -->
                                    <button wire:click="updateStatus('{{ $leave->uuid }}', 'approved')"
                                        @click="updateStatus('approved')"
                                        class="flex items-center w-full px-4 py-2 text-sm text-green-600 hover:bg-green-50"
                                        :class="{ 'bg-green-50': status === 'approved' }"
                                        :disabled="status !== 'pending'">
                                        <svg class="mr-3 h-4 w-4 text-green-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve
                                    </button>

                                    <!-- Reject -->
                                    <button wire:click="openRejectionModal('{{ $leave->uuid }}')"
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                        :class="{ 'bg-red-50': status === 'rejected' }"
                                        :disabled="status !== 'pending'">
                                        <svg class="mr-3 h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Reject
                                    </button>
                                    
                                    <!-- Cancel -->
                                    <button wire:click="updateStatus('{{ $leave->uuid }}', 'cancelled')"
                                        @click="updateStatus('cancelled')"
                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-600 hover:bg-gray-50"
                                        :class="{ 'bg-gray-50': status === 'cancelled' }"
                                        :disabled="status !== 'pending'">
                                        <svg class="mr-3 h-4 w-4 text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                        Cancel
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Status additional info -->
                            @if ($leave->status === 'approved' && $leave->approved_by)
                                <div class="mt-1 text-xs text-green-600">
                                    By: {{ $leave->approvedBy->name ?? 'Admin' }}
                                </div>
                            @endif
                            
                            @if ($leave->status === 'rejected' && $leave->rejected_by)
                                <div class="mt-1 text-xs text-red-600 group relative cursor-help">
                                    By: {{ $leave->rejectedBy->name ?? 'Admin' }}
                                    @if ($leave->rejection_reason)
                                        <div class="absolute left-0 mt-1 bg-gray-900 text-white text-xs rounded-md p-2 shadow-lg w-48 invisible group-hover:visible z-10 transition-opacity">
                                            {{ $leave->rejection_reason }}
                                            <div class="absolute -top-1 left-4 transform rotate-45 w-2 h-2 bg-gray-900"></div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </td>
                    
                    <!-- Reason -->
                    <td class="px-6 py-4">
                        <div x-data="{ expanded: false }" class="text-sm text-gray-900">
                            <div x-show="!expanded" class="line-clamp-1">
                                {{ $leave->reason ?? 'No reason provided' }}
                            </div>
                            <div x-show="expanded" class="text-sm text-gray-900">
                                {{ $leave->reason ?? 'No reason provided' }}
                            </div>
                            <button @click="expanded = !expanded" 
                                class="text-xs text-indigo-600 hover:text-indigo-800 transition-colors duration-200 mt-1">
                                <span x-text="expanded ? 'Show Less' : 'Show More'"></span>
                            </button>
                        </div>
                    </td>
                    
                    <!-- Files -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($leave->attachments->count() > 0)
                            <div x-data="{ showFileList: false }" class="relative">
                                <button @click="showFileList = !showFileList"
                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-full
                                    bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors duration-200">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    {{ $leave->attachments->count() }} {{ Str::plural('file', $leave->attachments->count()) }}
                                </button>
                                
                                <!-- Files Dropdown -->
                                <div x-cloak x-show="showFileList" @click.away="showFileList = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute left-0 z-10 mt-2 w-64 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    
                                    <div class="py-1 max-h-64 overflow-y-auto">
                                        @foreach($leave->attachments as $attachment)
                                            <div class="flex items-center justify-between px-4 py-2 hover:bg-gray-50">
                                                <div class="flex items-center space-x-3 truncate">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-xs text-gray-700 truncate max-w-[150px]">
                                                        {{ $attachment->original_file_name }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-1">
                                                    <a href="{{ Storage::url('leave_attachments/' . $attachment->leave_id . '/' . $attachment->file_path) }}" 
                                                        target="_blank"
                                                        class="p-1 text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ Storage::url('leave_attachments/' . $attachment->leave_id . '/' . $attachment->file_path) }}" 
                                                        download="{{ $attachment->original_file_name }}"
                                                        class="p-1 text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-xs text-gray-500">No files</span>
                        @endif
                    </td>
                    
                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center space-x-3 justify-end">
                            @if ($leave->status === 'pending')
                                <!-- Edit Button -->
                                <a href="{{ route('leave.edit', ['uuid' => $leave->uuid]) }}"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                
                                <!-- Delete Button -->
                                <button wire:click="deleteLeave('{{ $leave->uuid }}')"
                                    wire:confirm="Are you sure you want to delete this leave request?"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <!-- Empty State -->
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No leave requests</h3>
                            <p class="mt-1 text-sm text-gray-500">No leave requests found in the system.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    <!-- Pagination -->
    @if (isset($leaves) && $leaves->hasPages())
        <div class="mt-6">
            {{ $leaves->links() }}
        </div>
    @endif

<!-- Rejection Modal -->
<div x-cloak
            x-show="$wire.showRejectionModal" 
            x-transition.opacity.duration.400ms
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-50" @click="showModal = false"></div>


            <!-- Modal Container -->
            <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal Content -->
                <div 
                    x-show="$wire.showRejectionModal"
                    x-transition:enter="transition ease-out duration-300" 
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95" 
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                    x-transition:leave="transition ease-in duration-200" 
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                    x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <form wire:submit.prevent="submitRejection" class="relative">
                        <!-- Modal Header with Gradient -->
                        <div class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 px-6 py-4">
                            <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">
                                Reject Leave Request
                            </h3>
                        </div>

                        <!-- Modal Body -->
                        <div class="bg-white px-6 py-6">
                            <div class="sm:flex sm:items-start">
                                <!-- Warning Icon -->
                                <div class="mx-auto flex-shrink-0 sm:mx-0 sm:mr-6">
                                    <div class="h-12 w-12 flex items-center justify-center rounded-full bg-red-100">
                                        <svg class="h-6 w-6 text-red-600" 
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Form Content -->
                                <div class="mt-3 text-center sm:mt-0 sm:text-left flex-1">
                                    <div class="relative">
                                        <textarea 
                                            wire:model="rejectionReason"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm 
                                                focus:border-indigo-500 focus:ring-indigo-500 
                                                transition-all duration-300 ease-in-out
                                                text-gray-700 placeholder-gray-400
                                                resize-none h-32"
                                            placeholder="Please provide a detailed reason for rejecting this leave request..."
                                        ></textarea>
                                        @error('rejectionReason')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" 
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" 
                                                        clip-rule="evenodd"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                            <!-- Confirm Button -->
                            <button type="submit"
                                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                Confirm Rejection
                            </button>

                            <!-- Cancel Button -->
                            <button type="button" 
                                wire:click="closeRejectionModal"
                                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 mr-2 ml-2">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
