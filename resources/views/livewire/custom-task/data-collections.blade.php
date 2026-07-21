<div x-data="{ showModal: @entangle('showModal') }" x-cloak>
    <!-- Create/Edit Custom Task Modal -->
<!-- Create/Edit Custom Task Modal - Optimized -->
    <div x-show="showModal" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50"
        @keydown.escape.window="$wire.closeModal()">
        
        <!-- Black Background Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="$wire.closeModal()"></div>
        
        <!-- Modal Container -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Modal Content -->
            <div class="relative w-full max-w-5xl max-h-[95vh] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-indigo-400 to-purple-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-white">
                                    {{ $isUpdate ? 'Edit Custom Task' : 'Create New Custom Task' }}
                                </h1>
                                <p class="text-indigo-100 text-sm">
                                    {{ $isUpdate ? 'Modify task details and assignments' : 'Set up a new task with scheduling and user assignments' }}
                                </p>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="text-white hover:text-indigo-100 p-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto bg-gray-50">
                    <div class="p-6">
                        <!-- Modal Form -->
                        <form wire:submit="saveTask" class="space-y-6">
                            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                                <!-- Left Column - Task Details -->
                                <div class="xl:col-span-2 space-y-4">
                                    <!-- Task Basic Info -->
                                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                                        <div class="flex items-center mb-3">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <h3 class="font-semibold text-gray-900">Task Details</h3>
                                        </div>
                                        
                                        <!-- Task Title -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Task Title <span class="text-red-500">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                wire:model="title"
                                                placeholder="Enter a descriptive task title"
                                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            >
                                            @error('title')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Task Type and Repeat Day -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Task Type -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Task Type <span class="text-red-500">*</span>
                                                </label>
                                                <div class="space-y-2">
                                                    <label class="relative flex cursor-pointer rounded-lg border p-3 focus:outline-none" :class="$wire.task_type === 'onetime' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300 bg-white'">
                                                        <input type="radio" wire:model="task_type" value="onetime" class="sr-only">
                                                        <span class="flex flex-1 items-center">
                                                            <span class="text-sm font-medium text-gray-900">One Time</span>
                                                            <svg x-show="$wire.task_type === 'onetime'" class="ml-auto h-4 w-4 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </label>
                                                    
                                                    <label class="relative flex cursor-pointer rounded-lg border p-3 focus:outline-none" :class="$wire.task_type === 'repeat' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300 bg-white'">
                                                        <input type="radio" wire:model="task_type" value="repeat" class="sr-only">
                                                        <span class="flex flex-1 items-center">
                                                            <span class="text-sm font-medium text-gray-900">Repeat Monthly</span>
                                                            <svg x-show="$wire.task_type === 'repeat'" class="ml-auto h-4 w-4 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </label>
                                                </div>
                                                @error('task_type')
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Repeat On Day -->
                                            <div x-show="$wire.task_type === 'repeat'" x-transition>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Repeat On Day <span class="text-red-500">*</span>
                                                </label>
                                                <input 
                                                    type="number" 
                                                    wire:model="repeat_on_day"
                                                    placeholder="1-31"
                                                    min="1"
                                                    max="31"
                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                >
                                                <p class="mt-1 text-xs text-gray-500">Day of each month</p>
                                                @error('repeat_on_day')
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Timing Configuration -->
                                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                                        <div class="flex items-center mb-3">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <h3 class="font-semibold text-gray-900">Timing</h3>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Task Trigger Date -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Trigger Date
                                                </label>
                                                <input 
                                                    type="date" 
                                                    wire:model="task_trigger_date"
                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                >
                                                @error('task_trigger_date')
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Task Due Day -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Due in Days <span class="text-red-500">*</span>
                                                </label>
                                                <input 
                                                    type="number" 
                                                    wire:model="task_due_day"
                                                    placeholder="Number of days"
                                                    min="0"
                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                >
                                                @error('task_due_day')
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column - User Assignment -->
                                <div class="space-y-4">
                                    <!-- User Assignment -->
                                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                                        <div class="flex items-center mb-3">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                            <h3 class="font-semibold text-gray-900">Assign Users</h3>
                                        </div>
                                        
                                        <div x-data="{
                                            open: false,
                                            search: '',
                                            selectedUsers: @entangle('assigned_to'),
                                            users: @js($users),
                                            
                                            get filteredUsers() {
                                                if (!this.search) return this.users;
                                                return this.users.filter(user => 
                                                    user.name.toLowerCase().includes(this.search.toLowerCase())
                                                );
                                            },
                                            
                                            get selectedUserNames() {
                                                return this.users.filter(user => 
                                                    this.selectedUsers.includes(user.id)
                                                ).map(user => user.name);
                                            },
                                            
                                            toggleUser(userId) {
                                                if (this.selectedUsers.includes(userId)) {
                                                    this.selectedUsers = this.selectedUsers.filter(id => id !== userId);
                                                } else {
                                                    this.selectedUsers = [...this.selectedUsers, userId];
                                                }
                                            },
                                            
                                            removeUser(userId) {
                                                this.selectedUsers = this.selectedUsers.filter(id => id !== userId);
                                            },
                                            
                                            isSelected(userId) {
                                                return this.selectedUsers.includes(userId);
                                            }
                                        }" 
                                        class="relative">
                                            
                                            <!-- Trigger Button -->
                                            <button
                                                type="button"
                                                @click="open = !open"
                                                class="relative w-full bg-gray-50 border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left cursor-pointer hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                :class="{ 'border-indigo-500 ring-2 ring-indigo-500 bg-indigo-50': open }"
                                            >
                                                <span class="block truncate text-sm">
                                                    <span x-show="selectedUsers.length === 0" class="text-gray-500">
                                                        Select users...
                                                    </span>
                                                    <span x-show="selectedUsers.length === 1" class="text-gray-900" x-text="selectedUserNames[0]">
                                                    </span>
                                                    <span x-show="selectedUsers.length > 1" class="text-gray-900" x-text="`${selectedUsers.length} users selected`">
                                                    </span>
                                                </span>
                                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                    <svg class="h-4 w-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </button>
                                            
                                            <!-- Selected Users Tags -->
                                            <div x-show="selectedUsers.length > 0" class="mt-2 flex flex-wrap gap-1">
                                                <template x-for="user in users.filter(u => selectedUsers.includes(u.id))" :key="user.id">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-700">
                                                        <span x-text="user.name"></span>
                                                        <button
                                                            type="button"
                                                            @click="removeUser(user.id)"
                                                            class="ml-1 inline-flex items-center justify-center h-3 w-3 rounded-full text-indigo-400 hover:bg-indigo-200 hover:text-indigo-600"
                                                        >
                                                            <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                                                <path stroke-linecap="round" stroke-width="1.5" d="m1 1 6 6m0-6-6 6" />
                                                            </svg>
                                                        </button>
                                                    </span>
                                                </template>
                                            </div>
                                            
                                            <!-- Dropdown Panel -->
                                            <div
                                                x-show="open"
                                                @click.away="open = false"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                class="absolute z-30 mt-1 w-full bg-white shadow-lg max-h-64 rounded-lg border border-gray-200 overflow-hidden"
                                            >
                                                <!-- Search Input -->
                                                <div class="p-3 border-b border-gray-100">
                                                    <input
                                                        type="text"
                                                        x-model="search"
                                                        placeholder="Search users..."
                                                        class="w-full px-3 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                    >
                                                </div>
                                                
                                                <!-- User Options -->
                                                <div class="max-h-48 overflow-y-auto">
                                                    <template x-for="user in filteredUsers" :key="user.id">
                                                        <div
                                                            @click="toggleUser(user.id)"
                                                            class="cursor-pointer select-none relative px-3 py-2 hover:bg-gray-50 flex items-center"
                                                            :class="{ 'bg-indigo-50': isSelected(user.id) }"
                                                        >
                                                            <!-- Checkbox -->
                                                            <input
                                                                type="checkbox"
                                                                :checked="isSelected(user.id)"
                                                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded mr-3"
                                                                @click.stop="toggleUser(user.id)"
                                                            >
                                                            
                                                            <!-- Avatar -->
                                                            <div class="flex-shrink-0 h-8 w-8 mr-3">
                                                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                                                    <span class="text-xs font-medium text-white" x-text="user.name.charAt(0).toUpperCase()"></span>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- User Details -->
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-medium text-gray-900 truncate" x-text="user.name"></p>
                                                                <p class="text-xs text-gray-500 truncate" x-text="user.email || ''"></p>
                                                            </div>
                                                            
                                                            <!-- Selected Indicator -->
                                                            <svg x-show="isSelected(user.id)" class="h-4 w-4 text-indigo-600 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    </template>
                                                    
                                                    <!-- No Results -->
                                                    <div x-show="filteredUsers.length === 0" class="px-3 py-4 text-sm text-gray-500 text-center">
                                                        No users found
                                                    </div>
                                                </div>
                                                
                                                <!-- Footer Actions -->
                                                <div class="border-t border-gray-100 px-3 py-2 bg-gray-50 flex justify-between">
                                                    <button
                                                        type="button"
                                                        @click="selectedUsers = []"
                                                        class="text-xs text-gray-600 hover:text-gray-800"
                                                    >
                                                        Clear All
                                                    </button>
                                                    <button
                                                        type="button"
                                                        @click="open = false"
                                                        class="text-xs text-indigo-600 hover:text-indigo-800 font-medium"
                                                    >
                                                        Done
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @error('assigned_to')
                                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Quick Info -->
                                    <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div class="text-sm text-blue-800">
                                                <p class="font-medium mb-1">Quick Tips:</p>
                                                <ul class="text-xs space-y-1 text-blue-700">
                                                    <li>• One-time tasks execute once on trigger date</li>
                                                    <li>• Repeat tasks auto-create monthly</li>
                                                    <li>• Due days = completion timeline</li>
                                                    <li>• Multiple users can be assigned</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Fixed Footer -->
                <div class="border-t border-gray-200 bg-white px-6 py-3">
                    <div class="flex items-center justify-end space-x-3">
                        <button 
                            type="button" 
                            wire:click="closeModal"
                            class="mr-2 inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0
                                bg-blue-400 hover:bg-blue-500 text-white"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:click="saveTask"
                            class="mr-2 inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0
                                bg-blue-400 hover:bg-blue-500 text-white"
                        >
                            <span wire:loading.remove class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $isUpdate ? 'Update Task' : 'Create Task' }}
                            </span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
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
    </div>
    <!-- Main Container -->
    <div class="mx-auto py-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Custom Task Management</h1>
                <p class="mt-1 text-sm text-gray-600">Create and manage custom tasks for better workflow automation.</p>
            </div>
            @if(in_array($user_role,[1,2]))
            <!-- Create Button -->
            <button 
                wire:click="openModal"
                class="mr-2 inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0
                            bg-blue-400 hover:bg-blue-500 text-white"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Custom Task
            </button>
            @endif
        </div>

        <!-- Search and Filter Bar -->
        <div class="mb-4 bg-white rounded-lg border border-gray-200 p-4">
            <!-- Main Search Row -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search by task title or user..."
                            class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                        @if($search)
                            <button 
                                wire:click="$set('search', '')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <svg class="h-4 w-4 text-gray-400 hover:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Quick Type Filter -->
                <div>
                    <select 
                        wire:model.live="typeFilter"
                        class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                        <option value="">All Types</option>
                        <option value="active">Active Tasks</option>
                        <option value="inactive">Inactive Tasks</option>
                        <option value="completed">Completed Tasks</option>
                        <option value="transfer">Transfer Tasks</option>
                    </select>
                </div>

                <!-- Advanced Filters Toggle -->
                <div class="flex items-center justify-end space-x-2">
                    <button 
                        @click="showAdvancedFilters = !showAdvancedFilters"
                        x-data="{ showAdvancedFilters: @entangle('showAdvancedFilters') }"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :class="{ 'bg-indigo-50 border-indigo-300 text-indigo-700': showAdvancedFilters }"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                        Filters
                        @if($this->hasActiveFilters())
                            <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $this->activeFiltersCount() }}
                            </span>
                        @endif
                    </button>

                    @if($this->hasActiveFilters())
                        <button 
                            wire:click="clearAllFilters"
                            class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                            Clear All
                        </button>
                    @endif
                </div>
            </div>

            <!-- Advanced Filters Panel -->
            <div 
                x-data="{ showAdvancedFilters: @entangle('showAdvancedFilters') }"
                x-show="showAdvancedFilters"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="border-t border-gray-200 pt-4"
                x-cloak
            >
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    @if(in_array($user_role,[1,2]))
                    <!-- Assigned To Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                        <select 
                            wire:model.live="assignedToFilter"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="">All Users</option>
                            <option value="unassigned">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Created By Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                        <select 
                            wire:model.live="createdByFilter"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                    <!-- From Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input 
                            type="date"
                            wire:model.live="fromDate"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                    </div>

                    <!-- To Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input 
                            type="date"
                            wire:model.live="toDate"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                    </div>
                </div>

                <!-- Quick Date Filters -->
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-sm font-medium text-gray-700">Quick filters:</span>
                    <button 
                        wire:click="setDateFilter('today')"
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors {{ $dateFilter === 'today' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        Today
                    </button>
                    <button 
                        wire:click="setDateFilter('yesterday')"
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors {{ $dateFilter === 'yesterday' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        Yesterday
                    </button>
                    <button 
                        wire:click="setDateFilter('this_week')"
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors {{ $dateFilter === 'this_week' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        This Week
                    </button>
                    <button 
                        wire:click="setDateFilter('last_week')"
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors {{ $dateFilter === 'last_week' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        Last Week
                    </button>
                    <button 
                        wire:click="setDateFilter('this_month')"
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors {{ $dateFilter === 'this_month' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        This Month
                    </button>
                    <button 
                        wire:click="setDateFilter('last_month')"
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors {{ $dateFilter === 'last_month' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        Last Month
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Task Tabs and Table Container -->
        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
            <!-- Task Status Tabs -->
            <div class="border-b border-gray-200">
                <nav class="lg:flex -mb-px space-x-2 px-4 overflow-x-auto" aria-label="Tabs">
                    <button 
                        wire:click="setStatusFilter('all')"
                        class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        All
                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'all' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $allTasksCount }}
                        </span>
                    </button>
                    
                    <button 
                        wire:click="setStatusFilter('mytask')"
                        class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'mytask' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        My Task
                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'mytask' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $myTasksCount }}
                        </span>
                    </button>
                    
                    <button 
                        wire:click="setStatusFilter('active')"
                        class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'active' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Active
                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'active' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $activeTasksCount }}
                        </span>
                    </button>
                    
                    <button 
                        wire:click="setStatusFilter('inactive')"
                        class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'inactive' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Inactive
                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'inactive' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $inactiveTasksCount }}
                        </span>
                    </button>
                    <button 
                        wire:click="setStatusFilter('transfer')"
                        class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'transfer' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Transferd
                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'transfer' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $transferTasksCount }}
                        </span>
                    </button>
                    <button 
                        wire:click="setStatusFilter('completed')"
                        class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'completed' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Completed
                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'completed' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $completedTasksCount }}
                        </span>
                    </button>
                </nav>
            </div>

            <!-- Table Header with Current Filter Info -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $this->getFilterTitle() }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $this->getFilterDescription() }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $tasks->total() }} tasks
                        </span>
                        @if($statusFilter !== 'all')
                            <button 
                                wire:click="setStatusFilter('all')"
                                class="inline-flex items-center px-2 py-1 border border-gray-300 rounded text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Clear Filter
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Table Wrapper -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Task Title
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trigger Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Due Days
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Assigned Users
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created By
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Task Title -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $task->task->title }}</div>
                                </td>
                                
                                <!-- Type -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->task->task_type === 'repeat' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($task->task->task_type) }}
                                        @if($task->task->task_type === 'repeat')
                                            (Day {{ $task->task->repeat_on_day }})
                                        @endif
                                    </span>
                                </td>
                                
                                <!-- Trigger Date -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($task->task->task_trigger_date)
                                        {{ $task->task->task_trigger_date->format('M d, Y') }}
                                    @else
                                        <span class="text-gray-400 italic">Not set</span>
                                    @endif
                                </td>
                                
                                <!-- Due Days -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $task->task->task_due_day }} day(s)
                                </td>
                                
                             <!-- Assigned Users -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                @if($task->assignedUser)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-gray-700">
                                                        {{ substr($task->assignedUser->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $task->assignedUser->name }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">N/A</span>
                                    @endif
                                </td>
                                <!-- Created By -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($task->creator)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-gray-700">
                                                        {{ substr($task->creator->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $task->creator->name }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">N/A</span>
                                    @endif
                                </td>
                                
                                <!-- Created Date -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex flex-col">
                                        <span class="text-gray-900">{{ $task->task->created_at->format('M d, Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ $task->task->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                
                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                @if(in_array($user_role,[1,2]))
                                    <button 
                                        wire:click="toggleStatus({{ $task->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 hover:opacity-80 {{ $task->status == '1' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}"
                                        title="Click to toggle status"
                                    >
                                        {{ $task->status == '1' ? 'Active' : 'Inactive' }}
                                    </button>
                                @else
                                    <span 
                                        class="inline-flex disabled items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 hover:opacity-80 {{ $task->status == '1' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}"
                                        title="Disabled"
                                    >
                                        {{ $task->status == '1' ? 'Active' : 'Inactive' }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if(isset($task->task_done_on) && $task->task_done_on!=NULL)
                                        {{-- Show completion info when task is completed --}}
                                        <div class="flex items-center justify-end">
                                            <div class="flex flex-col items-end">
                                                {{-- Completed Badge --}}
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <path fill="white" d="m9 12 2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                        Completed
                                                    </span>
                                                </div>
                                                {{-- Date and Time --}}
                                                <div class="text-xs text-gray-500 text-right">
                                                    <div class="flex items-center space-x-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 6v6a2 2 0 002 2h4a2 2 0 002-2v-6m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v0M8 7v6a2 2 0 002 2h4a2 2 0 002-2V7"></path>
                                                        </svg>
                                                        <span>{{ $task->task_done_on??'' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-end space-x-2">
                                            {{-- Edit Button --}}
                                            @if($task->assign_to == Auth::user()->id)
                                                <div class="flex items-center space-x-2">
                                                {{-- Transfer Button --}}
                                                    <button 
                                                        wire:click="openActionModal({{ $task->id }}, 'transfer')"
                                                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                        </svg>
                                                        Transfer
                                                    </button>
                                                    
                                                    {{-- Done Button --}}
                                                    <button 
                                                        wire:click="openActionModal({{ $task->id }}, 'done')"
                                                        class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Done
                                                    </button>
                                                </div>
                                            @endif
                                            <!-- Actions -->
                                            @if(in_array($user_role,[1,2]))
                                                <button
                                                    wire:click="editTask({{ $task->id }})"
                                                    class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                                                    title="Edit task"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>

                                                <button
                                                    wire:click="deleteTask({{ $task->id }})"
                                                    wire:confirm="Are you sure you want to delete this task?"
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                    title="Delete task"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0-1.125.504-1.125 1.125V18a9 9 0 00-9-9z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No custom tasks found</h3>
                                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first custom task.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tasks->hasPages())
                <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>


    <div 
        x-data="{ show: @entangle('showActionModal') }"
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
        @keydown.escape.window="$wire.closeActionModal()">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        
        {{-- Modal Content --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div 
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-md transform overflow-hidden rounded-lg bg-white px-6 py-6 shadow-xl transition-all">
                
                {{-- Modal Header --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        @if($actionType === 'done')
                            <span class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                Mark Task as Done
                            </span>
                        @elseif($actionType === 'transfer')
                            <span class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                                Transfer Task
                            </span>
                        @endif
                    </h3>
                    
                    <button 
                        wire:click="closeActionModal"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 rounded-md p-1 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="mb-6 space-y-4">
                    <div class="bg-gray-50 p-3 rounded-md">
                        <p class="text-sm text-gray-600">
                            @if($actionType === 'done')
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    You are about to mark this task as complete. Please add a comment about the work done.
                                </span>
                            @elseif($actionType === 'transfer')
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    You are about to transfer this task to another user. Please select the recipient and add a comment.
                                </span>
                            @endif
                        </p>
                    </div>

                    {{-- Transfer To User Field (only for transfer action) --}}
                    @if($actionType === 'transfer')
                        <div>
                            <label for="transferToUserId" class="block text-sm font-medium text-gray-700 mb-2">
                                Transfer to User <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="transferToUserId"
                                wire:model="transferToUserId"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('transferToUserId') border-red-300 @enderror">
                                <option value="">Select a user...</option>
                                @foreach($users as $user)
                                    @if($user->id !== $user_id) {{-- Don't show current user --}}
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            
                            @error('transferToUserId')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    @endif
                    
                    {{-- Comment Field --}}
                    <div>
                        <label for="actionComment" class="block text-sm font-medium text-gray-700 mb-2">
                            Comment <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="actionComment"
                            wire:model="actionComment"
                            rows="4"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('actionComment') border-red-300 @enderror"
                            placeholder="@if($actionType === 'done')Describe what was accomplished...@else Add transfer notes and instructions...@endif"></textarea>
                        
                        @error('actionComment')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        
                        {{-- Character Counter --}}
                        <div class="mt-1 text-right">
                            <span class="text-xs text-gray-500">
                                {{ strlen($actionComment) }}/500 characters
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button 
                        wire:click="closeActionModal"
                        type="button"
                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        Cancel
                    </button>
                    
                    <button 
                        wire:click="submitAction"
                        wire:loading.attr="disabled"
                        type="button"
                        class="inline-flex justify-center rounded-md border border-transparent px-4 py-2 text-sm font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all
                        @if($actionType === 'done') bg-green-600 hover:bg-green-700 focus:ring-green-500 @elseif($actionType === 'transfer') bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 @endif">
                        
                        <span wire:loading.remove wire:target="submitAction">
                            @if($actionType === 'done')
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Mark as Done
                            @elseif($actionType === 'transfer')
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                Transfer Task
                            @endif
                        </span>
                        
                        <span wire:loading wire:target="submitAction" class="flex items-center">
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

    <div x-data="{ 
        show: false, 
        message: '', 
        type: 'success',
        showNotification(event) {
            this.message = event.detail[0] || event.detail;
            this.type = event.type.includes('success') ? 'success' : 'error';
            this.show = true;
            setTimeout(() => this.show = false, 4000);
        }
    }" 
    @notify-success.window="showNotification($event)"
    @notify-error.window="showNotification($event)">
        <div x-show="show" 
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed top-4 right-4 z-50 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto overflow-hidden"
            :class="type === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg x-show="type === 'success'" class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <svg x-show="type === 'error'" class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium" :class="type === 'success' ? 'text-green-800' : 'text-red-800'" x-text="message"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" 
                                class="inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
                                :class="type === 'success' ? 'text-green-500 hover:text-green-600 focus:ring-green-500' : 'text-red-500 hover:text-red-600 focus:ring-red-500'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    

        <!-- Mobile-Optimized Card View -->
        <div class="block md:hidden space-y-4 mt-6">
            @foreach($tasks as $task)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-gray-900">{{ $task->title }}</div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->status == '1' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $task->status == '1' ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <!-- Content -->
                    <div class="space-y-2 text-sm">
                        <div><span class="font-medium text-gray-700">Type:</span> {{ ucfirst($task->task_type) }}</div>
                        <div><span class="font-medium text-gray-700">Due Days:</span> {{ $task->task_due_day }}</div>
                        <div><span class="font-medium text-gray-700">Trigger Date:</span> {{ $task->task_trigger_date ? $task->task_trigger_date->format('M d, Y') : 'Not set' }}</div>
                        <div><span class="font-medium text-gray-700">Created By:</span> {{ $task->creator?->name ?? 'N/A' }}</div>
                        <div><span class="font-medium text-gray-700">Assigned Users:</span> {{ $task->count() }} user(s)</div>
                        <div><span class="font-medium text-gray-700">Created:</span> {{ $task->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex justify-end space-x-2 mt-4 pt-3 border-t border-gray-200">
                        <button wire:click="editTask({{ $task->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            Edit
                        </button>
                        <button wire:click="deleteTask({{ $task->id }})" wire:confirm="Are you sure?" class="text-red-600 hover:text-red-900 text-sm font-medium">
                            Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>