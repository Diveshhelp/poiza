<div class="container mx-auto px-4" x-data="learningManager()" @learning-created.window="showCreateModal = false" @comment-saved.window="showCommentModal = false">
    <div class="bg-white rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h4 class="text-xl font-semibold text-gray-800">
                {{ $view_mode === 'admin' ? 'Learning Programs Management' : 'My Learning Programs' }}
            </h4>
            @if($view_mode === 'admin')
                <div class="flex space-x-3">
                    <button type="button" 
                            class="w-full inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0"
                            @click="showCreateModal = true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Add New Learning</span>
                    </button>
                    <button type="button" 
                            class="w-full inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0"
                            @click="$wire.loadSortableItems(); showSortModal = true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        <span>Sort Programs</span>
                    </button>
                </div>
            @endif
        </div>
        
        <div class="p-6">
            <!-- Search and Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="relative">
                    <input type="text" 
                           class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Search learning programs..." 
                           wire:model.live.debounce.300ms="serverSearch">
                  
                    @if($serverSearch)
                        <button class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600" 
                                wire:click="clearSearch">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
                @if($view_mode === 'admin')
                    <div>
                        <select class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                wire:model.live="filterByStatus">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                @endif
            </div>

            <!-- Learning Programs Table -->
            <div class="overflow-x-auto bg-white rounded-lg shadow">
            <div class="overflow-hidden shadow-xl rounded-lg">
            <div class="overflow-hidden shadow-xl rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Learning Program</th>
                @if($view_mode === 'admin')
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Progress Overview</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assigned Users</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Completion Details</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                @else
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Your Progress</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Completion Details</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Your Comment</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($learnings as $learning)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <!-- Learning Program Info -->
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <div class="text-sm font-semibold text-gray-900 mb-1">{{ $learning->learning_title }}</div>
                            <div class="text-sm text-gray-600 leading-5" title="{{ $learning->learning_description }}">{{ substr($learning->learning_description,0,200) ?: 'No description available' }}</div>
                            <div class="mt-2 flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $learning->status == '1' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    @if($learning->status == 1)
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Active
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Inactive
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500">ID: {{ $learning->id }}</span>
                            </div>
                        </div>
                    </td>
                    
                    @if($view_mode === 'admin')
                        <!-- Status Toggle -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                    class="sr-only peer"
                                    {{ $learning->status == '1' ? 'checked' : '' }}
                                    wire:click="toggleStatus({{ $learning->id }})">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500 hover:bg-gray-400 peer-checked:hover:bg-green-600"></div>
                            </label>
                        </td>

                            <!-- Progress Overview -->
                        <td class="px-6 py-4">
                            <div class="space-y-3">
                                <!-- Line 1: Progress Bar -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-gray-900">Progress</span>
                                        <span class="text-sm font-black text-gray-800 bg-gradient-to-r from-blue-100 to-purple-100 px-2 py-1 rounded-full shadow border">
                                            {{ $learning->completion_percentage }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3 shadow-inner border">
                                        <div class="h-3 rounded-full transition-all duration-500 ease-out progress-bar-gradient"
                                            style="width: {{ $learning->completion_percentage }}%"></div>
                                    </div>
                                </div>

                                <style>
                                .progress-bar-gradient {
                                    background: linear-gradient(90deg, 
                                        #3b82f6 0%,     /* blue-500 */
                                        #6366f1 25%,    /* indigo-500 */
                                        #8b5cf6 50%,    /* violet-500 */
                                        #a855f7 75%,    /* purple-500 */
                                        #ec4899 100%    /* pink-500 */
                                    );
                                    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
                                }

                                /* Alternative with Tailwind-safe classes */
                                .progress-bar-safe {
                                    background: linear-gradient(to right, #60a5fa, #a78bfa, #c084fc);
                                }

                                /* Animated version */
                                @keyframes progress-animation {
                                    0% { background-position: 0% 50%; }
                                    50% { background-position: 100% 50%; }
                                    100% { background-position: 0% 50%; }
                                }

                                .progress-bar-animated {
                                    background: linear-gradient(
                                        -45deg, 
                                        #3b82f6, 
                                        #6366f1, 
                                        #8b5cf6, 
                                        #a855f7
                                    );
                                    background-size: 400% 400%;
                                    animation: progress-animation 3s ease infinite;
                                }
                                </style>
                                
                                <!-- Line 2: Statistics in one line -->
                                <div class="flex items-center justify-between space-x-2">
                                    <div class="flex-1 text-center p-2 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg shadow-md border">
                                        <div class="text-lg font-black text-blue-800">{{ $learning->total_users }}</div>
                                        <div class="text-xs font-bold text-blue-700">Total</div>
                                    </div>
                                    <div class="flex-1 text-center p-2 bg-gradient-to-br from-green-100 to-emerald-200 rounded-lg shadow-md border">
                                        <div class="text-lg font-black text-green-800">{{ $learning->completed_users }}</div>
                                        <div class="text-xs font-bold text-green-700">Done</div>
                                    </div>
                                    <div class="flex-1 text-center p-2 bg-gradient-to-br from-amber-100 to-orange-200 rounded-lg shadow-md border">
                                        <div class="text-lg font-black text-amber-800">{{ $learning->incomplete_users }}</div>
                                        <div class="text-xs font-bold text-amber-700">Pending</div>
                                    </div>
                                </div>
                                
                                <!-- Line 3: Status Badge -->
                                <div class="flex justify-center">
                                    @if($learning->completion_percentage >= 100)
                                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-green-100 to-emerald-200 text-green-800 text-sm font-bold rounded-full shadow-md border border-green-300">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            All Complete!
                                        </span>
                                    @elseif($learning->completion_percentage >= 50)
                                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-blue-100 to-purple-200 text-blue-800 text-sm font-bold rounded-full shadow-md border border-blue-300">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            In Progress
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-amber-100 to-orange-200 text-amber-800 text-sm font-bold rounded-full shadow-md border border-amber-300">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Getting Started
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Assigned Users -->
                        <td class="px-6 py-4">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 shadow-sm">
                                <!-- Header -->
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-bold text-gray-900">Assigned Users</h4>
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        {{ $learning->users->count() }} {{ $learning->users->count() == 1 ? 'User' : 'Users' }}
                                    </span>
                                </div>

                                 <!-- Assign User Dropdown -->
                                 <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="w-full inline-flex items-center justify-center px-4 py-3 border-2 border-dashed border-blue-300 rounded-lg text-sm font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add User to Program
                                        <svg class="ml-2 w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="open" 
                                        @click.away="open = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute z-30 mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl max-h-64 overflow-hidden">
                                        
                                        <!-- Search Header -->
                                        <div class="p-3 border-b border-gray-200 bg-gray-50">
                                            <input type="text" 
                                                placeholder="Search users..." 
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                x-data="{ search: '' }"
                                                x-model="search"
                                                @input="filterUsers(search)">
                                        </div>
                                        
                                        <!-- Users List -->
                                        <div class="max-h-48 overflow-y-auto">
                                            @php $availableUsers = 0; @endphp
                                            @foreach($user_list as $userId => $userName)
                                                @if(!$learning->users->contains('id', $userId))
                                                    @php $availableUsers++; @endphp
                                                    <a href="#" 
                                                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition-colors border-b border-gray-100 last:border-b-0 user-option"
                                                    @click="open = false"
                                                    wire:click="assignUserToLearning({{ $learning->id }}, {{ $userId }})"
                                                    data-user-name="{{ strtolower($userName) }}">
                                                        
                                                        <!-- Avatar -->
                                                        <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                            <span class="text-white text-sm font-semibold">{{ strtoupper(substr($userName, 0, 1)) }}</span>
                                                        </div>
                                                        
                                                        <!-- User Info -->
                                                        <div class="flex-1 min-w-0">
                                                            <div class="font-medium text-gray-900">{{ $userName }}</div>
                                                            <div class="text-xs text-gray-500">Click to assign</div>
                                                        </div>
                                                        
                                                        <!-- Add Icon -->
                                                        <svg class="w-5 h-5 text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                            @endforeach
                                            
                                            @if($availableUsers == 0)
                                                <div class="px-4 py-6 text-center text-gray-500">
                                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    <p class="text-sm font-medium">All users are already assigned</p>
                                                    <p class="text-xs">No more users available to add</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($learning->users->count() > 0)
                                    <!-- Users List -->
                                    <div class="mt-2 space-y-2 max-h-40 overflow-y-auto mb-4">
                                        @foreach($learning->users as $user)
                                            @php
                                                $isCompleted = $user->pivot->completed_at || $user->pivot->status === 'completed';
                                            @endphp
                                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                                                <!-- User Info -->
                                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                    <!-- Avatar -->
                                                    <div class="relative">
                                                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                            <span class="text-white text-sm font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                        </div>
                                                        @if($isCompleted)
                                                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                                                <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Name and Status -->
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</span>
                                                            @if($isCompleted)
                                                                <span class="inline-flex items-center px-1.5 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                                    ✓ Complete
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-1.5 py-0.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">
                                                                    ⏳
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-gray-500 mt-0.5">
                                                            {{ $user->email ?? 'No email' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Remove Button -->
                                                <button type="button" 
                                                        class="flex-shrink-0 ml-2 p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 group"
                                                        wire:click="removeUserFromLearning({{ $learning->id }}, {{ $user->id }})"
                                                        title="Remove {{ $user->name }}">
                                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Empty State -->
                                    <div class="text-center py-8 mb-4">
                                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                        </div>
                                        <h5 class="text-sm font-medium text-gray-900 mb-1">No users assigned</h5>
                                        <p class="text-xs text-gray-500">Start by assigning users to this learning program</p>
                                    </div>
                                @endif
                                
                               
                            </div>
                        </td>

                        <script>
                        function filterUsers(search) {
                            const userOptions = document.querySelectorAll('.user-option');
                            userOptions.forEach(option => {
                                const userName = option.getAttribute('data-user-name');
                                if (userName.includes(search.toLowerCase())) {
                                    option.style.display = 'flex';
                                } else {
                                    option.style.display = 'none';
                                }
                            });
                        }
                        </script>

                       <!-- Completion Details -->
                    <td class="px-6 py-4">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                            <!-- Header -->
                            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                                <h4 class="text-sm font-bold text-gray-900">Completion Status</h4>
                                <span class="text-xs font-medium text-gray-600 bg-gray-200 px-2 py-1 rounded-full">
                                    {{ $learning->users->count() }} {{ $learning->users->count() == 1 ? 'User' : 'Users' }}
                                </span>
                            </div>

                            @if($learning->users->count() > 0)
                                <!-- Users List -->
                                <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                                    @foreach($learning->users as $user)
                                        @php
                                            $isCompleted = $user->pivot->complete_on || $user->pivot->status === '1';
                                        @endphp
                                        <li class="p-4 hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex items-center justify-between">
                                                <!-- Left Side: User Info -->
                                                <div class="flex-1 min-w-0">
                                                    <!-- User Name -->
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                            <span class="text-white text-xs font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                        </div>
                                                        <span class="text-sm font-semibold text-gray-900">{{ $user->name }}</span>
                                                        @if($isCompleted)
                                                            <span class="inline-flex items-center px-2 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                                ✓ Done
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-medium rounded-full">
                                                                ⏳
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Complete On -->
                                                    <div class="text-xs text-gray-600 mb-1">
                                                        <span class="font-medium text-gray-700">Complete On:</span> 
                                                        @if($isCompleted && $user->pivot->complete_on)
                                                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($user->pivot->complete_on)->format('M d, Y g:i A') }}</span>
                                                        @else
                                                            <span class="text-gray-400 italic">Not completed</span>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Completed By -->
                                                    <div class="text-xs text-gray-600">
                                                        <span class="font-medium text-gray-700">By:</span> 
                                                        @if($isCompleted && isset($learning->updater->name))
                                                            <span class="font-medium text-gray-900">{{ $learning->updater->name }}</span>
                                                        @else
                                                            <span class="text-gray-400 italic">Not available</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Right Side: Action Button -->
                                                <div class="ml-4 flex-shrink-0">
                                                    @if($isCompleted)
                                                        <button wire:click="toggleCompletion({{ $learning->id }}, {{ $user->id }})" 
                                                                class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-semibold rounded-lg border border-amber-300 transition-all duration-200 hover:shadow-sm">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                            </svg>
                                                            Undo
                                                        </button>
                                                    @else
                                                        <button wire:click="toggleCompletion({{ $learning->id }}, {{ $user->id }})" 
                                                                class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-800 text-xs font-semibold rounded-lg border border-green-300 transition-all duration-200 hover:shadow-sm">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Complete
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <!-- Empty State -->
                                <div class="p-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <h5 class="text-sm font-medium text-gray-900 mb-1">No users assigned</h5>
                                    <p class="text-xs text-gray-500">Assign users to track their completion status</p>
                                </div>
                            @endif
                        </div>
                    </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-2">
                                <div class="flex space-x-2">
                                    <button type="button" 
                                            class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-md transition-colors"
                                            wire:click="editLearning({{ $learning->id }})"
                                            title="Edit Learning Program">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" 
                                            class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-md transition-colors"
                                            @click="if(confirm('Are you sure you want to delete this learning program? This will remove all user progress.')) $wire.deleteLearning({{ $learning->id }})"
                                            title="Delete Learning Program">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </td>
                    @else
                        <!-- User view columns -->
                        @php
                            $userProgress = $learning->users->where('id', Auth::user()->id)->first();
                            $isCompleted = $userProgress && ($userProgress->pivot->complete_on || $userProgress->pivot->status == '1');
                            $progress = $userProgress ? 100  : 0;
                        @endphp
                        
                        <!-- User Progress -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-2">
                                @if($isCompleted)
                                    <div class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                        </svg>
                                        Completed
                                    </div>
                                @else
                                    <div class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        In Progress
                                    </div>
                                @endif
                                
                                <!-- Progress Bar -->
                                <div class="w-full">
                                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Completion Details -->
                        <td class="px-6 py-4">
                            @if($userProgress && $userProgress->pivot->complete_on)
                                <div class="text-sm text-gray-900 bg-green-50 p-3 rounded-lg">
                                    <div class="font-medium text-green-800">Completed By @if($isCompleted && isset($learning->updater->name))
                                        <span class="font-medium text-gray-900">{{ $learning->updater->name }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Not available</span>
                                    @endif</div>
                                    <div class="text-green-600">{{ \Carbon\Carbon::parse($userProgress->pivot->complete_on)->format('M d, Y g:i A') }}</div>
                                    
                                </div>
                            @else
                                <div class="text-sm text-gray-400 bg-gray-50 p-3 rounded-lg">
                                    <div>Not completed</div>
                                    @if($userProgress && $userProgress->pivot->created_at)
                                        <div class="text-xs">Assigned: {{ \Carbon\Carbon::parse($userProgress->pivot->created_at)->format('M d, Y') }}</div>
                                    @endif
                                </div>
                            @endif
                        </td>
                        
                        <!-- User Comment -->
                        <td class="px-6 py-4">
                            @if($userProgress && isset($userProgress->pivot->comment) && $userProgress->pivot->comment)
                                <div class="text-sm text-gray-900 bg-blue-50 p-3 rounded-lg max-w-xs">
                                    <div class="font-medium text-xs text-blue-600 mb-1">Your Comment:</div>
                                    <div class="break-words text-blue-800">{{ $userProgress->pivot->comment }}</div>
                                </div>
                            @else
                                <span class="text-sm text-gray-400 italic">No comment added</span>
                            @endif
                        </td>
                        
                        <!-- User Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button type="button" 
                                    class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-md text-sm text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
                                    @click="$wire.openCommentModal({{ $learning->id }}); showCommentModal = true">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                {{ $userProgress && isset($userProgress->pivot->comment) && $userProgress->pivot->comment ? 'Edit Comment' : 'Add Comment' }}
                            </button>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $view_mode === 'admin' ? '6' : '5' }}" class="px-6 py-16 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No learning programs found</h3>
                            <p class="text-gray-500">{{ $view_mode === 'admin' ? 'Get started by creating a new learning program.' : 'No learning programs have been assigned to you yet.' }}</p>
                            @if($view_mode === 'admin')
                                <button class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                                        wire:click="createNewLearning">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Create Learning Program
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $learnings->links() }}
            </div>
        </div>
    </div>

    <!-- Create Learning Modal -->
    <div x-show="showCreateModal" 
         x-transition.opacity
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showCreateModal = false"></div>
            
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Create New Learning Program</h3>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="saveDataObject">
                    <div class="space-y-4">
                        <div>
                            <label for="learning_title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" 
                                   id="learning_title"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('learning_title') border-red-500 @enderror" 
                                   wire:model="learning_title" 
                                   required>
                            @error('learning_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="learning_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="learning_description"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                      wire:model="learning_description"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                @click="showCreateModal = false"
                                class="px-4 py-2 w-full inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 w-full inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
                            <span wire:loading wire:target="saveDataObject" class="inline-block w-4 h-4 mr-2 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            Create Learning
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Learning Modal -->
    <div x-show="$wire.showEditModal" 
         x-transition.opacity
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="$wire.closeEditModal()"></div>
            
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Learning Program</h3>
                    <button @click="$wire.closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="updateDataObject">
                    <div class="space-y-4">
                        <div>
                            <label for="edit_learning_title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" 
                                   id="edit_learning_title"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('learning_title') border-red-500 @enderror" 
                                   wire:model="learning_title" 
                                   required>
                            @error('learning_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit_learning_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="edit_learning_description"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                      wire:model="learning_description"></textarea>
                        </div>
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <input type="number" 
                                   id="sort_order"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   wire:model="sort_order">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                @click="$wire.closeEditModal()"
                                class="px-4 py-2 w-full inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 w-full inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
                            <span wire:loading wire:target="updateDataObject" class="inline-block w-4 h-4 mr-2 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            Update Learning
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Comment Modal -->
    <div x-show="showCommentModal" 
         x-transition.opacity
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showCommentModal = false"></div>
            
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Comment</h3>
                    <button @click="showCommentModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="saveComment">
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Your Comment</label>
                        <textarea id="comment"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                  wire:model="comment"
                                  placeholder="Enter your comment about this learning program..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                @click="showCommentModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 flex items-center">
                            <span wire:loading wire:target="saveComment" class="inline-block w-4 h-4 mr-2 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            Save Comment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sort Modal -->
    <div x-show="showSortModal" 
         x-transition.opacity
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showSortModal = false"></div>
            
            <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Sort Learning Programs</h3>
                    <button @click="showSortModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <p class="text-sm text-gray-600 mb-4">Drag and drop to reorder the learning programs:</p>
                <div id="sortable-list" class="space-y-2">
                    @foreach($sortableItems as $item)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg border border-gray-200 cursor-move hover:bg-gray-100" 
                             data-id="{{ $item['id'] }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V6a2 2 0 012-2h12a2 2 0 012 2v2M4 8v8a2 2 0 002 2h12a2 2 0 002-2V8M4 8h16"></path>
                                </svg>
                                <span class="font-medium text-gray-900">{{ $item['learning_title'] }}</span>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-gray-200 text-gray-800 rounded">Order: {{ $item['sort_order'] }}</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            @click="showSortModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" 
                            @click="saveSortOrder()"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Save Order
                    </button>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function learningManager() {
        return {
            showCreateModal: false,
            showCommentModal: false,
            showSortModal: false,
            
            init() {
                this.initSortable();
                
                // Re-initialize when Livewire updates
                this.$wire.on('refreshComponent', () => {
                    this.initSortable();
                });
            },
            
            initSortable() {
                this.$nextTick(() => {
                    const sortableList = document.getElementById('sortable-list');
                    if (sortableList) {
                        new Sortable(sortableList, {
                            animation: 150,
                            ghostClass: 'opacity-40',
                            chosenClass: 'bg-blue-50',
                            dragClass: 'opacity-80'
                        });
                    }
                });
            },
            
            saveSortOrder() {
                const sortableList = document.getElementById('sortable-list');
                const items = sortableList.querySelectorAll('[data-id]');
                const orderedIds = Array.from(items).map(item => parseInt(item.dataset.id));
                
                this.$wire.call('updateSortOrder', orderedIds).then(() => {
                    this.showSortModal = false;
                });
            }
        }
    }
</script>
</div>