<div class="min-h-screen py-1" x-data="{ 
    showRoleModal: false,
    importArea:false,
    selectedUser: null,
    roles: @entangle('roles'),
    selectedRoles: []
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
                    <!-- User Count Badge -->
                    <div class="ml-3 flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $totalUsers ?? 0 }} users
                        </span>
                        
                        <!-- Quota Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($pendingUsers > 0) ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                            @if($pendingUsers > 0)
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                                </svg>
                                {{ $pendingUsers ?? 0 }} pending
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Quota reached
                            @endif
                        </span>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Manage and view all registered users in the system.
                    @if($quota && $totalUsers < $quota)
                        <span class="text-blue-600 dark:text-blue-400 font-medium">
                            {{ $quota - $totalUsers }} more users available in your plan.
                        </span>
                    @endif
                </p>
            </div>
            <!-- Add User Button (Optional) -->
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <!-- Quota Visualization (small on mobile, visible on sm screens) -->
                <div class="hidden sm:flex items-center bg-gray-100 dark:bg-gray-800 rounded-lg p-2 pr-3">
                    <div class="flex items-center w-32">
                        <div class="relative w-full h-2 bg-gray-300 dark:bg-gray-600 rounded-full overflow-hidden">
                            <div class="absolute top-0 left-0 h-full rounded-full bg-primary dark:bg-secondary" 
                                 style="width: {{ $quota ? min(100, ($totalUsers / $quota) * 100) : 100 }}%"></div>
                        </div>
                        <span class="ml-2 text-xs font-medium text-gray-700 dark:text-gray-300">
                            {{ $totalUsers ?? 0 }}/{{ $quota ?? '∞' }}
                        </span>
                    </div>
                </div>
                @if($pendingUsers > 0)
                <button @click="importArea = !importArea"
                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Import User
                </button>
                @endif
            </div>
        </div>
        
        <!-- Import Area (existing code) -->
        <div x-show="importArea" x-cloak>
            <!-- ... existing import area code ... -->
        </div>

        <!-- Search Box with Enhanced Design -->
        <div class="mt-6">
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="search" wire:model.live="search"
                    class="form-input block w-full pl-10 sm:text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                    placeholder="Search users by name or email...">
            </div>
        </div>

        <!-- Status Filter Section -->
        <div class="mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Filter Title -->
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Filter by Status</h3>
                </div>

                <!-- Filter Buttons -->
                <div class="flex items-center space-x-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                  <!-- All Filter -->
                    <button wire:click="setStatusFilter('all')"
                        class="relative flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200
                            {{ $statusFilter === 'all'
                                ? 'bg-blue-500 text-white shadow-md'
                                : 'text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white' }}">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full {{ $statusFilter === 'all' ? 'bg-white' : 'bg-blue-500' }}"></div>
                            <span>All Users</span>
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full
                                {{ $statusFilter === 'all'
                                    ? 'bg-white/20 text-white'
                                    : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                {{ $filterCounts['all'] ?? $users->count() }}
                            </span>
                        </div>
                    </button>

                    <!-- Pending Filter -->
                    <button wire:click="setStatusFilter('0')"
                        class="relative flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200
                            {{ $statusFilter === '0'
                                ? 'bg-yellow-500 text-white shadow-md'
                                : 'text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white' }}">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full {{ $statusFilter === '0' ? 'bg-white' : 'bg-yellow-500' }} animate-pulse"></div>
                            <span>Pending</span>
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full
                                {{ $statusFilter === '0'
                                    ? 'bg-white/20 text-white'
                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                {{ $filterCounts['pending'] ?? 0 }}
                            </span>
                        </div>
                    </button>

                    <!-- Active Filter -->
                    <button wire:click="setStatusFilter('1')"
                        class="relative flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200
                            {{ $statusFilter === '1'
                                ? 'bg-green-500 text-white shadow-md'
                                : 'text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white' }}">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full {{ $statusFilter === '1' ? 'bg-white' : 'bg-green-500' }} animate-pulse"></div>
                            <span>Active</span>
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full
                                {{ $statusFilter === '1'
                                    ? 'bg-white/20 text-white'
                                    : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                {{ $filterCounts['active'] ?? 0 }}
                            </span>
                        </div>
                    </button>

                    <!-- Blocked Filter -->
                    <button wire:click="setStatusFilter('2')"
                        class="relative flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200
                            {{ $statusFilter === '2'
                                ? 'bg-red-500 text-white shadow-md'
                                : 'text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white' }}">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full {{ $statusFilter === '2' ? 'bg-white' : 'bg-red-500' }}"></div>
                            <span>Blocked</span>
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full
                                {{ $statusFilter === '2'
                                    ? 'bg-white/20 text-white'
                                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $filterCounts['blocked'] ?? 0 }}
                            </span>
                        </div>
                    </button>

                    <!-- Rejected Filter -->
                    <button wire:click="setStatusFilter('3')"
                        class="relative flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200
                            {{ $statusFilter === '3'
                                ? 'bg-gray-500 text-white shadow-md'
                                : 'text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white' }}">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full {{ $statusFilter === '3' ? 'bg-white' : 'bg-gray-500' }}"></div>
                            <span>Rejected</span>
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full
                                {{ $statusFilter === '3'
                                    ? 'bg-white/20 text-white'
                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                {{ $filterCounts['rejected'] ?? 0 }}
                            </span>
                        </div>
                    </button>

                </div>

                <!-- Quick Stats (Optional) -->
                <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center space-x-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span>{{ $filterCounts['active'] }} Active</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        <span>{{ $filterCounts['pending'] }} Inactive</span>
                    </div>
                </div>
            </div>

            <!-- Search Results Info -->
            @if(!empty($search))
                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">{{ $users->total() }}</span> users found matching 
                        <span class="font-medium text-blue-600 dark:text-blue-400">"{{ $search }}"</span>
                        @if($statusFilter !== 'all')
                            in <span class="font-medium">{{ $statusFilter === '1' ? 'Active' : 'Inactive' }}</span> users
                        @endif
                        
                        @if($users->total() === 0)
                            <button wire:click="$set('search', '')" 
                                class="ml-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 underline">
                                Clear search
                            </button>
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Loading Indicator -->
        <div wire:loading.delay class="mt-4">
            <div class="flex items-center justify-center py-8">
                <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium">Loading users...</span>
                </div>
            </div>
        </div>

        <!-- No Results Message -->
        @if($users->isEmpty() && !$this->loading)
            <div class="mt-8 text-center">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No users found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if(!empty($search))
                            No users match your search criteria.
                        @elseif($statusFilter === '1')
                            No active users found.
                        @elseif($statusFilter === '2')
                            No inactive users found.
                        @else
                            Get started by creating your first user.
                        @endif
                    </p>
                    @if(!empty($search))
                        <div class="mt-3">
                            <button wire:click="$set('search', '')" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Clear search
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Compact User Cards Grid -->
        @if(!$users->isEmpty())
        <div class="mt-4" wire:loading.remove>
            <ul role="list" class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                @foreach ($users as $user)
                    <li class="col-span-1 bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border-2 overflow-hidden
                        {{ $user->status === '1' 
                            ? 'border-green-200 hover:border-green-300' 
                            : 'border-red-200 hover:border-red-300 opacity-75 hover:opacity-90' }}">
                        
                        <!-- Status Bar at Top -->
                        <div class="h-1 w-full {{ $user->status === '1' ? 'bg-gradient-to-r from-green-400 to-green-500' : 'bg-gradient-to-r from-red-400 to-red-500' }}"></div>
                        
                        <div class="p-4">
                            <!-- User Header -->
                            <div class="flex items-center gap-3 mb-4">
                                <!-- User Avatar with Status -->
                                <div class="relative">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-md
                                        {{ $user->status !== '1' ? 'grayscale' : '' }}">
                                        <span class="text-white font-bold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <!-- Status Indicator -->
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-3 border-white shadow-sm flex items-center justify-center
                                        {{ $user->status === '1' ? 'bg-green-500' : 'bg-red-500' }}">
                                        @if($user->status === '1')
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                               
                            <div class="min-w-0 flex-1">
                                <!-- User Name -->
                                <h3 class="text-sm font-bold truncate mb-1 
                                    {{ $user->status == '1' ? 'text-gray-900' : 'text-gray-500' }}">
                                    {{ $user->name }}
                                </h3>
                            
                                <!-- Status Badge -->
                                @php
                                    $statusStyles = [
                                        '0' => 'bg-yellow-100 text-yellow-800 border-yellow-300',  // Pending
                                        '1' => 'bg-green-100 text-green-800 border-green-300',     // Active  
                                        '2' => 'bg-red-100 text-red-800 border-red-300',          // Blocked
                                        '3' => 'bg-gray-100 text-gray-800 border-gray-300'        // Rejected
                                    ];
                                    
                                    $statusTexts = [
                                        '0' => 'PENDING',
                                        '1' => 'ACTIVE', 
                                        '2' => 'BLOCKED',
                                        '3' => 'REJECTED'
                                    ];
                                    
                                    $statusDots = [
                                        '0' => 'bg-yellow-500 animate-pulse',
                                        '1' => 'bg-green-500 animate-pulse',
                                        '2' => 'bg-red-500', 
                                        '3' => 'bg-gray-500'
                                    ];
                                    
                                    $currentStatus = $user->status;
                                    $badgeStyle = $statusStyles[$currentStatus] ?? $statusStyles['0'];
                                    $badgeText = $statusTexts[$currentStatus] ?? 'UNKNOWN';
                                    $dotStyle = $statusDots[$currentStatus] ?? $statusDots['0'];
                                @endphp

                                <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-semibold border {{ $badgeStyle }}">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $dotStyle }}"></div>
                                    {{ $badgeText }}
                                </div>
                            </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <div class="bg-gray-50 rounded-lg px-3 py-2 border border-gray-200" title="{{ $user->email }}">
                                    <p class="text-xs text-gray-600 truncate font-medium">{{ $user->email }}</p>
                                </div>
                            </div>

                            <!-- Status Toggle Button -->
                                                            
                                <div class="mb-4">
                                    <div class="flex items-center space-x-3">
                                        <!-- Current Status Display -->
                                        <div class="text-sm text-gray-600">
                                            Status: <span class="font-medium text-gray-900">{{ $this->getStatusLabel($user->status) }}</span>
                                        </div>

                                        <!-- Status Dropdown -->
                                        <div class="relative">
                                            <select 
                                                wire:model.live="selectedStatus"
                                                wire:change="updateUserStatus({{ $user->id }})"
                                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                wire:loading.attr="disabled"
                                            >
                                                <option value="">Status...</option>
                                                @foreach($this->getStatusOptions() as $status => $label)
                                                    @if($status != $user->status)
                                                        <option value="{{ $status }}">{{ $label }}</option>
                                                    @endif
                                                @endforeach
                                            </select>

                                            <!-- Loading Indicator -->
                                            <div wire:loading wire:target="updateUserStatus" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <!-- Roles Pills -->
                            @if(!empty($user->user_role))
                            <div class="mb-4 flex flex-wrap gap-1.5">
                                @foreach(explode(',', $user->user_role ?? '') as $roleId)
                                    @php
                                        $role = collect($roles)->firstWhere('id', (int) $roleId);
                                    @endphp
                                    @if($role)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border
                                            @if($role['name'] === 'Super Admin') bg-purple-100 text-purple-800 border-purple-300
                                            @elseif($role['name'] === 'Admin') bg-blue-100 text-blue-800 border-blue-300
                                            @else bg-emerald-100 text-emerald-800 border-emerald-300
                                            @endif {{ $user->status !== '1' ? 'opacity-60' : '' }}">
                                            {{ $role['name'] }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-1.5">
                                <!-- Edit Button -->
                                <a href="{{ route('user.edit', $user->id) }}"
                                    x-data="{ loading: false }"
                                    @click="loading = true"
                                    class="flex-1 inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow group"
                                    :class="{ 'opacity-75 cursor-not-allowed pointer-events-none': loading }">
                                    
                                    <!-- Edit Icon with Loading Spinner -->
                                    <svg x-show="loading" class="animate-spin h-3 w-3 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg x-show="!loading" class="h-3 w-3 mr-1 text-gray-500 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="hidden sm:inline">Edit</span>
                                    <span class="sm:hidden">E</span>
                                </a>

                                <!-- Roles Button -->
                                <button @click="showRoleModal = true; selectedUser = {{ $user->id }}; selectedRoles = {{ json_encode(explode(',', $user->user_role ?? '')) }}"
                                    wire:loading.attr="disabled"
                                    wire:target="updateUserRoles"
                                    class="flex-1 inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium rounded-md text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow group disabled:opacity-75 disabled:cursor-not-allowed">
                                    
                                    <!-- Roles Icon with Loading Spinner -->
                                    <svg wire:loading wire:target="updateUserRoles" class="animate-spin h-3 w-3 mr-1 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg wire:loading.remove wire:target="updateUserRoles" class="h-3 w-3 mr-1 text-blue-500 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                    <span class="hidden sm:inline">Roles</span>
                                    <span class="sm:hidden">R</span>
                                </button>

                                <!-- Delete Button -->
                                <button wire:click="confirmUserDeletion({{ $user->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="confirmUserDeletion({{ $user->id }})"
                                    class="flex-1 inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium rounded-md text-red-700 bg-red-50 border border-red-200 hover:bg-red-100 hover:border-red-300 transition-all duration-200 shadow-sm hover:shadow group disabled:opacity-75 disabled:cursor-not-allowed">
                                    
                                    <!-- Delete Icon with Loading Spinner -->
                                    <svg wire:loading wire:target="confirmUserDeletion({{ $user->id }})" class="animate-spin h-3 w-3 mr-1 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg wire:loading.remove wire:target="confirmUserDeletion({{ $user->id }})" class="h-3 w-3 mr-1 text-red-500 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="">&nbsp;</span>
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Delete User Modal -->
        <div class="fixed inset-0 z-50" x-data="{}" x-show="$wire.confirmingUserDeletion" x-cloak>
            <!-- Background Overlay -->
            <div class="fixed inset-0 bg-opacity-50 transition-opacity"></div>

            <!-- Modal Container (centered) -->
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <!-- Modal Content -->
                <div class="bg-white rounded-lg shadow-xl max-w-md mx-auto overflow-hidden w-full">
                    <!-- Header -->
                    <div class="bg-white px-6 py-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Delete User</h3>
                    </div>
                    <!-- Content -->
                    <div class="p-6">
                        <p class="text-gray-700">Are you sure you want to delete this user? This action cannot be undone.</p>
                    </div>
                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button type="button" wire:click="$set('confirmingUserDeletion', false)"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="button" wire:click="deleteUser" wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <span wire:loading.remove wire:target="deleteUser">Delete User</span>
                            <span wire:loading wire:target="deleteUser" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Pagination -->
        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </div>
    <!-- Role Edit Modal -->
    <div x-show="showRoleModal" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm transition-all duration-300"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Modal panel -->
            <div x-show="showRoleModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-2xl transform transition-all overflow-hidden border border-gray-200 dark:border-gray-700 ring-1 ring-black/5 dark:ring-white/10">

                <!-- Glass effect header -->
                <div
                    class="px-6 pt-5 pb-4 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 dark:from-indigo-600/20 dark:to-purple-600/20 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">
                            Edit User Roles
                        </h3>
                        <button type="button" @click="showRoleModal = false"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-full p-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal body with smooth shadow -->
                <!-- Ultra-modern role card list -->
                <div class="p-6 max-h-[60vh] overflow-y-auto">
                    <div class="grid gap-3">
                        <template x-for="role in roles" :key="role . id">
                            <div class="role-card-container">
                                <!-- Fully styled card with hover and active states -->
                                <label :for="'role-' + role . id"
                                    class="group relative flex items-center w-full p-4 rounded-xl cursor-pointer bg-white dark:bg-gray-800/80 border-2 border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transform transition-all duration-200 hover:-translate-y-0.5 hover:border-indigo-300 dark:hover:border-indigo-600 peer-checked:border-indigo-600 dark:peer-checked:border-indigo-500">

                                    <!-- Left side: custom interactive checkbox -->
                                    <div class="relative flex shrink-0 mr-4">
                                        <input type="checkbox" :id="'role-' + role . id" :value="role . id"
                                            x-model="selectedRoles" class="peer absolute w-0 h-0 opacity-0" />

                                        <!-- Animated checkbox container -->
                                        <div
                                            class="w-6 h-6 rounded-md border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden transition-all duration-200 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 dark:peer-checked:border-indigo-500 peer-focus:ring-2 peer-focus:ring-indigo-500/30 peer-focus:ring-offset-2 peer-focus:ring-offset-white dark:peer-focus:ring-offset-gray-900">
                                            <!-- Checkmark with animation -->
                                            <svg class="w-4 h-4 text-white transform scale-0 peer-checked:scale-100 transition-transform duration-200"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="flex-1">
                                        <!-- Dynamic role name with hover effect -->
                                        <p class="text-base font-medium text-gray-800 dark:text-gray-200 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition-colors"
                                            x-text="role.name"></p>

                                        <!-- Role-specific descriptions -->
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 transition-colors">
                                            <span x-show="role.name === 'Super Admin'">Full system access with all
                                                privileges</span>
                                            <span x-show="role.name === 'Admin'">System management with limited
                                                configuration access</span>
                                            <span x-show="role.name === 'Department Head'">Manage team members and
                                                department workflows</span>
                                            <span x-show="role.name === 'Department Work'">Access to department-specific
                                                features</span>
                                            <span x-show="role.name === 'Employee'">Basic access to assigned tasks and
                                                reports</span>
                                            <span
                                                x-show="!['Super Admin', 'Admin', 'Department Head', 'Department Work', 'Employee'].includes(role.name)">Access
                                                to specific system features</span>
                                        </p>
                                    </div>

                                    <!-- Selected state indicator -->
                                    <div
                                        class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-indigo-500 rounded-full transform scale-0 opacity-0 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-200">
                                    </div>
                                </label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Eye-catching gradient footer -->
                <div
                    class="p-5 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 border-t border-gray-200 dark:border-gray-800 flex flex-row-reverse gap-3">
                    <button type="button"
                        class="inline-flex justify-center items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:-translate-y-0.5"
                        @click="$wire.updateUserRoles(selectedUser, selectedRoles); showRoleModal = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                    <button type="button"
                        class="inline-flex justify-center items-center px-5 py-2.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 transform hover:-translate-y-0.5"
                        @click="showRoleModal = false">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>