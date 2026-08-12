<div class="min-h-screen py-1" x-data="{ 
    showRoleModal: false,
    showCreateModal: false,
    importArea: false,
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
                    
                    <div class="ml-3 flex items-center space-x-2">
                        <!-- User Count Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $totalUsers ?? 0 }} users
                        </span>
                    </div>
                </div>
                
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Manage and view all registered users in the system.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <button @click="showCreateModal = true"
                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add New User
                </button>
            </div>
        </div>
        
        <!-- Import Area -->
        <div x-show="importArea" x-cloak class="mt-4">
            <!-- Add your import form content here -->
        </div>

        <!-- Search Box -->
        <div class="mt-6">
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="search" wire:model.live="search"
                    class="form-input block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                    placeholder="Search users by name or email...">
            </div>
        </div>

        <!-- Status Filter Section -->
        <div class="mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Filter by Status</h3>
                </div>

                <div class="flex flex-wrap items-center gap-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    @foreach([
                        ['id' => 'all', 'label' => 'All Users', 'count' => $filterCounts['all'] ?? 0],
                        ['id' => '0', 'label' => 'Pending', 'count' => $filterCounts['pending'] ?? 0],
                        ['id' => '1', 'label' => 'Active', 'count' => $filterCounts['active'] ?? 0],
                        ['id' => '2', 'label' => 'Blocked', 'count' => $filterCounts['blocked'] ?? 0],
                        ['id' => '3', 'label' => 'Rejected', 'count' => $filterCounts['rejected'] ?? 0],
                    ] as $filter)
                        <button wire:click="setStatusFilter('{{ $filter['id'] }}')"
                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all duration-200 {{ $statusFilter === $filter['id'] ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' }}">
                            {{ $filter['label'] }} ({{ $filter['count'] }})
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading.delay class="mt-4 w-full">
            <div class="flex items-center justify-center py-8">
                <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                    <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium">Loading users...</span>
                </div>
            </div>
        </div>

        <!-- No Results Message -->
        @if($users->isEmpty() && !$this->loading)
            <div class="mt-8 text-center py-12 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No users found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if(!empty($search)) No users match your search criteria. @else Get started by creating your first user. @endif
                </p>
                @if(!empty($search))
                    <button wire:click="$set('search', '')" class="mt-3 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Clear search
                    </button>
                @endif
            </div>
        @endif

        <!-- Compact User Cards Grid -->
        @if(!$users->isEmpty())
        <div class="mt-4" wire:loading.remove>
            <ul role="list" class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                @foreach ($users as $user)
                    @php
                        $isActive = $user->status === '1';
                        $statusStyles = [
                            '0' => 'bg-yellow-100 text-yellow-800 border-yellow-300', 
                            '1' => 'bg-green-100 text-green-800 border-green-300',  
                            '2' => 'bg-red-100 text-red-800 border-red-300',          
                            '3' => 'bg-gray-100 text-gray-800 border-gray-300'        
                        ];
                        $statusTexts = ['0' => 'PENDING', '1' => 'ACTIVE', '2' => 'BLOCKED', '3' => 'REJECTED'];
                    @endphp

                    <li class="col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border-2 overflow-hidden {{ $isActive ? 'border-green-200' : 'border-red-200 opacity-75' }}">
                        <div class="h-1 w-full {{ $isActive ? 'bg-green-500' : 'bg-red-500' }}"></div>
                        
                        <div class="p-4">
                            <!-- User Header & Avatar -->
                            <div class="flex items-center gap-3 mb-3">
                                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm shadow">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-bold truncate text-gray-900 dark:text-white">{{ $user->name }}</h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $statusStyles[$user->status] ?? '' }}">
                                        {{ $statusTexts[$user->status] ?? 'UNKNOWN' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Email Box -->
                            <div class="mb-3">
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg px-2.5 py-1.5 border border-gray-200 dark:border-gray-700" title="{{ $user->email }}">
                                    <p class="text-[11px] text-gray-600 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>

                            <!-- Role Badges -->
                            @if(!empty($user->user_role))
                            <div class="mb-3 flex flex-wrap gap-1">
                                @foreach(explode(',', $user->user_role) as $roleId)
                                    @php $role = collect($roles)->firstWhere('id', (int) $roleId); @endphp
                                    @if($role)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            {{ $role['name'] }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-1 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('user.edit', $user->id) }}" class="flex-1 text-center py-1 text-xs font-medium rounded bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100">
                                    Edit
                                </a>
                                <button @click="showRoleModal = true; selectedUser = {{ $user->id }}; selectedRoles = {{ json_encode(explode(',', $user->user_role ?? '')) }}" 
                                    class="flex-1 text-center py-1 text-xs font-medium rounded bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 hover:bg-blue-100">
                                    Roles
                                </button>
                                <button wire:click="confirmUserDeletion({{ $user->id }})" class="p-1 text-red-600 hover:bg-red-50 rounded">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Pagination -->
        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="fixed inset-0 z-50" x-show="$wire.confirmingUserDeletion" x-cloak>
        <div class="fixed inset-0 bg-black/50 transition-opacity"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl max-w-md mx-auto overflow-hidden w-full border dark:border-gray-800">
                <div class="px-6 py-4 border-b dark:border-gray-800">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Delete User</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 dark:text-gray-300">Are you sure you want to delete this user? This action cannot be undone.</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 flex justify-end space-x-3">
                    <button type="button" wire:click="$set('confirmingUserDeletion', false)"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteUser" wire:loading.attr="disabled"
                        class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add New User</h3>
                <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Name</label>
                    <input type="text" wire:model="name" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="John Doe">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" wire:model="email" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="john@example.com">
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Mobile</label>
                    <input type="text" wire:model="mobile" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="1234567890">
                    @error('mobile') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Security Code</label>
                    <input type="text" wire:model="security_code" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="111111">
                    @error('security_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <input type="password" wire:model="password" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="••••••••">
                    @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select wire:model="status" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="1">Active</option>
                        <option value="0">Pending</option>
                        <option value="2">Blocked</option>
                        <option value="3">Rejected</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2">
                <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-sm border dark:border-gray-600 rounded-lg dark:text-gray-300">Cancel</button>
                <button type="button" wire:click="createUser" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save User</button>
            </div>
        </div>
    </div>
    <!-- Role Edit Modal -->
    <div x-show="showRoleModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit User Roles</h3>
                <button @click="showRoleModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <div class="p-6 max-h-[60vh] overflow-y-auto space-y-3">
                <template x-for="role in roles" :key="role.id">
                    <label :for="'role-' + role.id" class="flex items-center p-3 rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-indigo-500">
                        <input type="checkbox" :id="'role-' + role.id" :value="role.id" x-model="selectedRoles" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 mr-3" />
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="role.name"></p>
                        </div>
                    </label>
                </template>
            </div>

            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2">
                <button type="button" @click="showRoleModal = false" class="px-4 py-2 text-sm border dark:border-gray-600 rounded-lg dark:text-gray-300">Cancel</button>
                <button type="button" @click="$wire.updateUserRoles(selectedUser, selectedRoles); showRoleModal = false" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save Changes</button>
            </div>
        </div>
    </div>
</div>