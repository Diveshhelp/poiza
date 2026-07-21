<div class="ml-2" wire:poll.{{ $refreshInterval }}ms="refreshData">
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold text-gray-900">Login Analytics Dashboard</h1>
            <div class="flex space-x-4">
                <select wire:model="selectedPeriod" class="rounded-md border-gray-300 shadow-sm">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="365">Last year</option>
                </select>
                <button wire:click="refreshData" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    <span wire:loading.remove wire:target="refreshData">Refresh</span>
                    <span wire:loading wire:target="refreshData">Refreshing...</span>
                </button>
            </div>
        </div>

        <!-- Overview Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($overviewStats['total_users']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($overviewStats['active_users']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Very Active</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($overviewStats['very_active_users']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Logins</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($overviewStats['total_logins']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Engagement</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $overviewStats['engagement_rate'] }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg/User</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $overviewStats['avg_logins_per_user'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Daily Login Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Daily Login Trend</h3>
            <div style="height: 300px;">
                <canvas id="dailyLoginChart" wire:ignore></canvas>
            </div>
        </div>

        <!-- Hourly Distribution Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Hourly Distribution</h3>
            <div style="height: 300px;">
                <canvas id="hourlyChart" wire:ignore></canvas>
            </div>
        </div>
    </div>

    <!-- Most Active Users Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Most Active Users</h3>
                <div class="max-w-xs">
                    <input wire:model.debounce.300ms="searchUser" 
                           type="text" 
                           placeholder="Search users..." 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
        <tr>
            <th wire:click="sortBy('name')"
                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors duration-150">
                <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>User</span>
                    @if($sortBy === 'name')
                        <span class="text-gray-400 text-sm">
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        </span>
                    @endif
                </div>
            </th>
            <th wire:click="sortBy('email')"
                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors duration-150">
                <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                    <span class="hidden sm:inline">Email</span>
                    <span class="sm:hidden">Contact</span>
                    @if($sortBy === 'email')
                        <span class="text-gray-400 text-sm">
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        </span>
                    @endif
                </div>
            </th>
            <th wire:click="sortBy('login_logs_count')"
                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors duration-150">
                <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <span>Activity</span>
                    @if($sortBy === 'login_logs_count')
                        <span class="text-gray-400 text-sm">
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        </span>
                    @endif
                </div>
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="hidden md:inline">Last Activity</span>
                    <span class="md:hidden">Recent</span>
                </div>
            </th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        @forelse($mostActiveUsers as $index => $user)
        @php
            // Calculate activity level
            $loginCount = $user->login_logs_count ?? 0;
            $activityLevel = 'low';
            $activityColor = 'gray';
            $rankColor = 'gray';
            
            if ($loginCount >= 50) {
                $activityLevel = 'very-high';
                $activityColor = 'red';
            } elseif ($loginCount >= 25) {
                $activityLevel = 'high';
                $activityColor = 'orange';
            } elseif ($loginCount >= 10) {
                $activityLevel = 'medium';
                $activityColor = 'yellow';
            } elseif ($loginCount >= 5) {
                $activityLevel = 'moderate';
                $activityColor = 'green';
            }
            
            // Rank colors
            if ($index === 0) $rankColor = 'yellow'; // Gold
            elseif ($index === 1) $rankColor = 'gray'; // Silver
            elseif ($index === 2) $rankColor = 'orange'; // Bronze
            
            // Generate consistent avatar color based on user ID
            $avatarColors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-yellow-500', 'bg-teal-500'];
            $avatarColor = $avatarColors[$user->id % count($avatarColors)];
            
            // Get last login (you might need to add this to your query)
            $lastLogin = $user->loginLogs()->latest('logged_in_at')->first();
        @endphp
        
        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 group">
            {{-- User Column --}}
            <td class="px-4 py-3">
                <div class="flex items-center space-x-3">
                    {{-- Rank Badge --}}
                    @if($index < 3)
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 rounded-full bg-{{ $rankColor }}-100 border-2 border-{{ $rankColor }}-300 flex items-center justify-center">
                                <span class="text-xs font-bold text-{{ $rankColor }}-700">{{ $index + 1 }}</span>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 relative">
                        <div class="h-10 w-10 rounded-full {{ $avatarColor }} flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow duration-200">
                            <span class="text-sm font-semibold text-white">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>
                        {{-- Online Status Indicator (you can add logic for this) --}}
                        @if($lastLogin && $lastLogin->logged_in_at->gt(now()->subMinutes(15)))
                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></div>
                        @endif
                    </div>
                    
                    {{-- User Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2">
                            <div class="text-sm font-semibold text-gray-900 truncate">
                                {{ $user->name }}
                            </div>
                            {{-- User Role Badge (if you have roles) --}}
                            @if(method_exists($user, 'getRoleNames') && $user->getRoleNames()->isNotEmpty())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $user->getRoleNames()->first() }}
                                </span>
                            @endif
                        </div>
                        {{-- User ID --}}
                        <div class="text-xs text-gray-500">
                            ID: {{ $user->id }}
                        </div>
                    </div>
                </div>
            </td>

            {{-- Email Column --}}
            <td class="px-4 py-3">
                <div class="space-y-1">
                    <div class="text-sm text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                        {{ $user->email }}
                    </div>
                    {{-- Email verification status --}}
                    @if($user->email_verified_at)
                        <div class="flex items-center space-x-1">
                            <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs text-green-600">Verified</span>
                        </div>
                    @else
                        <div class="flex items-center space-x-1">
                            <svg class="w-3 h-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <span class="text-xs text-yellow-600">Unverified</span>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Activity Column --}}
            <td class="px-4 py-3">
                <div class="space-y-2">
                    {{-- Login Count Badge --}}
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                            @if($activityColor === 'red') bg-red-100 text-red-800
                            @elseif($activityColor === 'orange') bg-orange-100 text-orange-800
                            @elseif($activityColor === 'yellow') bg-yellow-100 text-yellow-800
                            @elseif($activityColor === 'green') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            {{ number_format($loginCount) }}
                        </span>
                    </div>
                    
                    {{-- Activity Level Indicator --}}
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $maxLogins = $mostActiveUsers->max('login_logs_count') ?? 1;
                            $percentage = ($loginCount / $maxLogins) * 100;
                        @endphp
                        <div class="h-2 rounded-full transition-all duration-300
                            @if($activityColor === 'red') bg-red-500
                            @elseif($activityColor === 'orange') bg-orange-500
                            @elseif($activityColor === 'yellow') bg-yellow-500
                            @elseif($activityColor === 'green') bg-green-500
                            @else bg-gray-500 @endif"
                            style="width: {{ $percentage }}%">
                        </div>
                    </div>
                    
                    {{-- Activity Label --}}
                    <div class="text-xs text-gray-500 capitalize">
                        {{ str_replace('-', ' ', $activityLevel) }} activity
                    </div>
                </div>
            </td>

            {{-- Last Activity Column --}}
            <td class="px-4 py-3">
                <div class="space-y-1">
                    @if($lastLogin)
                        <div class="text-sm text-gray-900">
                            {{ $lastLogin->logged_in_at->format('M j, Y') }}
                        </div>
                        <div class="text-xs text-gray-500 flex items-center space-x-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $lastLogin->logged_in_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-xs text-gray-400 flex items-center space-x-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                            </svg>
                            <span class="font-mono">{{ $lastLogin->ip_address }}</span>
                        </div>
                    @else
                        <div class="text-sm text-gray-400">
                            No recent activity
                        </div>
                    @endif
                </div>
            </td>

            {{-- Actions Column --}}
            <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end space-x-2">
                    {{-- View Details Button --}}
                    <a href="{{ route('admin.user-login-details', $user) }}"
                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="hidden sm:inline">View</span>
                    </a>
                    
                    {{-- Quick Actions Dropdown --}}
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                       
                        <a href="javascript:void(0)" wire:confirm="Are you sure you want to terminate session of {{ $user->name }}?" wire:click="resetSession('{{  $user->id  }}')" class="flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636"></path>
                                    </svg>
                                    Reset Sessions
                                </a>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-4 py-12 text-center">
                <div class="flex flex-col items-center space-y-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="text-center">
                        <h3 class="text-sm font-medium text-gray-900 mb-1">No Active Users Found</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            No users have logged in during the selected time period.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-3">
                            <button wire:click="$set('selectedPeriod', '90')" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Try Last 90 Days
                            </button>
                            <button wire:click="refreshData" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh Data
                            </button>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Add this CSS for additional styling --}}
<style>
    @keyframes pulse-gentle {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    
    .animate-pulse-gentle {
        animation: pulse-gentle 2s ease-in-out infinite;
    }
    
    .rank-1 { @apply border-yellow-400 bg-yellow-50; }
    .rank-2 { @apply border-gray-400 bg-gray-50; }
    .rank-3 { @apply border-orange-400 bg-orange-50; }
</style>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $mostActiveUsers->links() }}
        </div>
    </div>
    
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let dailyLoginChart = null;
    let hourlyDistributionChart = null;

    // Chart data from Livewire
    const chartData = {
        dailyLogins: @json($dailyLogins ?? collect()),
        hourlyDistribution: @json($hourlyDistribution ?? collect())
    };

    console.log('Chart data:', chartData);

    function initCharts() {
        console.log('Initializing charts...');
        
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return;
        }

        // Destroy existing charts
        if (dailyLoginChart) {
            dailyLoginChart.destroy();
            dailyLoginChart = null;
        }
        
        if (hourlyDistributionChart) {
            hourlyDistributionChart.destroy();
            hourlyDistributionChart = null;
        }

        // Daily Login Chart
        const dailyCtx = document.getElementById('dailyLoginChart');
        if (dailyCtx) {
            console.log('Creating daily chart');
            
            const dailyLabels = chartData.dailyLogins.map(item => item.date) || [];
            const dailyLoginData = chartData.dailyLogins.map(item => item.login_count) || [];
            const dailyUserData = chartData.dailyLogins.map(item => item.unique_users) || [];
            
            dailyLoginChart = new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: 'Total Logins',
                        data: dailyLoginData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        fill: true
                    }, {
                        label: 'Unique Users',
                        data: dailyUserData,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            console.log('Daily chart created successfully');
        } else {
            console.error('Daily chart canvas not found');
        }

        // Hourly Distribution Chart
        const hourlyCtx = document.getElementById('hourlyChart');
        if (hourlyCtx) {
            console.log('Creating hourly chart');
            
            const hourlyLabels = chartData.hourlyDistribution.map(item => item.hour + ':00') || [];
            const hourlyData = chartData.hourlyDistribution.map(item => item.login_count) || [];
            
            hourlyDistributionChart = new Chart(hourlyCtx, {
                type: 'bar',
                data: {
                    labels: hourlyLabels,
                    datasets: [{
                        label: 'Login Count',
                        data: hourlyData,
                        backgroundColor: 'rgba(147, 51, 234, 0.6)',
                        borderColor: 'rgba(147, 51, 234, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            console.log('Hourly chart created successfully');
        } else {
            console.error('Hourly chart canvas not found');
        }
    }

    // Multiple initialization attempts
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initCharts, 100);
    });

    document.addEventListener('livewire:load', function () {
        setTimeout(initCharts, 200);
    });

    // Listen for Livewire updates and refresh charts
    document.addEventListener('livewire:update', function () {
        // Update chart data from the new response
        const newDailyData = @json($dailyLogins ?? collect());
        const newHourlyData = @json($hourlyDistribution ?? collect());
        
        if (dailyLoginChart && newDailyData) {
            dailyLoginChart.data.labels = newDailyData.map(item => item.date);
            dailyLoginChart.data.datasets[0].data = newDailyData.map(item => item.login_count);
            dailyLoginChart.data.datasets[1].data = newDailyData.map(item => item.unique_users);
            dailyLoginChart.update('none');
        }
        
        if (hourlyDistributionChart && newHourlyData) {
            hourlyDistributionChart.data.labels = newHourlyData.map(item => item.hour + ':00');
            hourlyDistributionChart.data.datasets[0].data = newHourlyData.map(item => item.login_count);
            hourlyDistributionChart.update('none');
        }
    });

    // Fallback initialization
    window.addEventListener('load', function() {
        setTimeout(function() {
            if (!dailyLoginChart || !hourlyDistributionChart) {
                console.log('Fallback chart initialization');
                initCharts();
            }
        }, 1000);
    });

    // Debug function
    window.debugCharts = function() {
        console.log('Daily chart:', dailyLoginChart);
        console.log('Hourly chart:', hourlyDistributionChart);
        console.log('Chart data:', chartData);
        initCharts();
    };
</script>
</div>
