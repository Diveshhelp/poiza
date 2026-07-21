<div>
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-1">Bug Tracking System</h2>
            <p class="text-gray-500 mb-0">Manage bugs, enhancements, and justifications</p>
        </div>
        <button wire:click="openModal" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
            <i class="fas fa-plus mr-2"></i>Report New Issue
        </button>
    </div>

    <!-- Filters Section -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" wire:model.live.debounce.500ms="search" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search bugs...">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model.live="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Client Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client Status</label>
                    <select wire:model.live="filterClientStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Client Status</option>
                        @foreach($clientStatusOptions as $clientStatus)
                            <option value="{{ $clientStatus }}">{{ $clientStatus }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select wire:model.live="filterType" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        @foreach($typeOptions as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Priority Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select wire:model.live="filterPriority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Priority</option>
                        @foreach($priorityOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Clear Filters -->
                <div class="flex items-end">
                    <button wire:click="clearFilters" class="px-3 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-md transition-colors" title="Clear Filters">
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading.delay class="text-center py-6">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <span class="sr-only">Loading...</span>
    </div>

    <!-- Bugs Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="p-6">
            @if($bugs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Status</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bugs as $bug)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-3 py-2">
                                        <div class="text-sm font-semibold text-gray-900 leading-tight">{{ Str::limit($bug->title, 50) }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ Str::limit($bug->explanation, 80) }}</div>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        @php
                                            $typeColors = [
                                                'Bug' => 'red',
                                                'Enhancement' => 'blue', 
                                                'Justification' => 'green'
                                            ];
                                            $typeColor = $typeColors[$bug->type] ?? 'gray';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $typeColor }}-100 text-{{ $typeColor }}-800">
                                            {{ $bug->type }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        @php
                                            $priorityColors = [
                                                1 => 'gray',   // Low
                                                2 => 'yellow', // Medium
                                                3 => 'orange', // High
                                                4 => 'red'     // Critical
                                            ];
                                            $priorityColor = $priorityColors[$bug->priority] ?? 'gray';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $priorityColor }}-100 text-{{ $priorityColor }}-800">
                                            {{ $bug->priority_text }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            @php
                                                $statusColors = [
                                                    'Draft' => 'gray',
                                                    'Ready for work' => 'blue',
                                                    'In progress' => 'yellow',
                                                    'Attention required' => 'orange',
                                                    'Deployed' => 'purple',
                                                    'Done' => 'green'
                                                ];
                                                $statusColor = $statusColors[$bug->status] ?? 'gray';
                                            @endphp
                                            <button @click="open = !open" class="inline-flex justify-center items-center px-2 py-1 border border-{{ $statusColor }}-300 text-{{ $statusColor }}-700 bg-white hover:bg-{{ $statusColor }}-50 rounded text-xs font-medium transition-colors duration-150">
                                                {{ $bug->status }}
                                                <svg x-show="!open" class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                                <svg x-show="open" class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-transition @click.away="open = false" class="origin-top-right absolute right-0 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                                                <div class="py-1">
                                                    @foreach($statusOptions as $status)
                                                        <a href="#" wire:click.prevent="updateStatus('{{ $bug->uuid }}', '{{ $status }}')" @click="open = false" class="flex items-center px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                                            @if($status === $bug->status)
                                                                <svg class="mr-2 h-3 w-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                                </svg>
                                                            @else
                                                                <span class="mr-5"></span>
                                                            @endif
                                                            {{ $status }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            @php
                                                $clientStatusColors = [
                                                    'Created' => 'gray',
                                                    'In Review' => 'blue',
                                                    'In Development' => 'yellow',
                                                    'In Testing' => 'orange',
                                                    'Done' => 'green',
                                                    'Ready for check' => 'purple'
                                                ];
                                                $clientStatusColor = $clientStatusColors[$bug->client_status] ?? 'gray';
                                            @endphp
                                            <button @click="open = !open" class="inline-flex justify-center items-center px-2 py-1 border border-{{ $clientStatusColor }}-300 text-{{ $clientStatusColor }}-700 bg-white hover:bg-{{ $clientStatusColor }}-50 rounded text-xs font-medium transition-colors duration-150">
                                                {{ $bug->client_status }}
                                                <svg x-show="!open" class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                                <svg x-show="open" class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-transition @click.away="open = false" class="origin-top-right absolute right-0 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                                                <div class="py-1">
                                                    @foreach($clientStatusOptions as $clientStatus)
                                                        <a href="#" wire:click.prevent="updateClientStatus('{{ $bug->uuid }}', '{{ $clientStatus }}')" @click="open = false" class="flex items-center px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                                            @if($clientStatus === $bug->client_status)
                                                                <svg class="mr-2 h-3 w-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                                </svg>
                                                            @else
                                                                <span class="mr-5"></span>
                                                            @endif
                                                            {{ $clientStatus }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-6 w-6">
                                                <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-blue-700">{{ substr($bug->user->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <div class="text-xs font-medium text-gray-900">{{ $bug->user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $bug->created_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-xs font-medium">
                                        <div class="flex items-center space-x-1">
                                            <!-- View Details Button -->
                                            <button wire:click="openDetailModal('{{ $bug->uuid }}')" class="inline-flex items-center p-1.5 border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded transition-colors duration-150 group" title="View Details">
                                                <svg class="h-3 w-3 group-hover:scale-110 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            
                                            <!-- Edit Button -->
                                            <button wire:click="editBug('{{ $bug->uuid }}')" class="inline-flex items-center p-1.5 border border-indigo-300 text-indigo-700 bg-white hover:bg-indigo-50 rounded transition-colors duration-150 group" title="Edit">
                                                <svg class="h-3 w-3 group-hover:scale-110 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <button wire:click="deleteBug('{{ $bug->uuid }}')" class="inline-flex items-center p-1.5 border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded transition-colors duration-150 group" title="Delete" 
                                                wire:confirm="Are you sure you want to delete this bug?">
                                                <svg class="h-3 w-3 group-hover:scale-110 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-center mt-6">
                    {{ $bugs->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-bug text-4xl text-gray-400 mb-4"></i>
                    <h5 class="text-lg font-medium text-gray-500 mb-2">No bugs found</h5>
                    <p class="text-gray-400">No bugs match your current filters.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50" wire:ignore.self>
            <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full m-4 max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-900">{{ $isUpdate ? 'Edit Bug' : 'Report New Bug' }}</h5>
                    <button type="button" class="text-gray-400 hover:text-gray-600" wire:click="closeModal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <form wire:submit="saveBug">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                <input type="text" wire:model="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror" placeholder="Brief description of the issue">
                                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Explanation -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Explanation *</label>
                                <textarea wire:model="explanation" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('explanation') border-red-500 @enderror" placeholder="Detailed description of the issue..."></textarea>
                                @error('explanation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Type and Priority -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                                <select wire:model="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                                    @foreach($typeOptions as $typeOption)
                                        <option value="{{ $typeOption }}">{{ $typeOption }}</option>
                                    @endforeach
                                </select>
                                @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                                <select wire:model="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('priority') border-red-500 @enderror">
                                    @foreach($priorityOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('priority') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Status and Client Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                                    @foreach($statusOptions as $statusOption)
                                        <option value="{{ $statusOption }}">{{ $statusOption }}</option>
                                    @endforeach
                                </select>
                                @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Client Status *</label>
                                <select wire:model="client_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('client_status') border-red-500 @enderror">
                                    @foreach($clientStatusOptions as $clientStatusOption)
                                        <option value="{{ $clientStatusOption }}">{{ $clientStatusOption }}</option>
                                    @endforeach
                                </select>
                                @error('client_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Assigned To and Due Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                                <select wire:model="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('assigned_to') border-red-500 @enderror">
                                    <option value="">Select User</option>
                                    @foreach($teamUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_to') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                                <input type="date" wire:model="due_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('due_date') border-red-500 @enderror">
                                @error('due_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Images -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Images</label>
                                <input type="file" wire:model="images" multiple accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('images.*') border-red-500 @enderror">
                                @error('images.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                <p class="mt-1 text-xs text-gray-500">Max 5MB per image. Supported formats: JPG, PNG, GIF</p>
                                
                                <!-- Upload Progress -->
                                <div wire:loading wire:target="images" class="mt-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 100%"></div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Uploading...</p>
                                </div>
                            </div>

                            <!-- Existing Images (for edit mode) -->
                            @if($isUpdate && !empty($existingImages))
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Existing Images</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($existingImages as $image)
                                            <div class="relative bg-white border border-gray-200 rounded-lg">
                                                <img src="{{ asset('storage/' . $image['image_path']) }}" class="w-full h-32 object-cover rounded-t-lg">
                                                <div class="p-2">
                                                    <p class="text-xs text-gray-500 truncate">{{ $image['original_name'] }}</p>
                                                    <button type="button" wire:click="deleteImage('{{ $image['uuid'] }}')" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                                                        wire:confirm="Are you sure you want to delete this image?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Image Previews -->
                            @if($images)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Images Preview</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($images as $image)
                                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="flex justify-end space-x-3 px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-md font-medium" wire:click="closeModal">Cancel</button>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md" wire:click="saveBug" wire:loading.attr="disabled">
                        <span wire:loading wire:target="saveBug" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
                        {{ $isUpdate ? 'Update Bug' : 'Create Bug' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedBug)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50" wire:ignore.self>
            <div class="relative bg-white rounded-lg shadow-xl max-w-7xl w-full m-4 max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-900">Bug Details</h5>
                    <button type="button" class="text-gray-400 hover:text-gray-600" wire:click="closeDetailModal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Bug Details -->
                        <div class="lg:col-span-2">
                            <div class="bg-white border border-gray-200 rounded-lg">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h6 class="text-lg font-medium text-gray-900">{{ $selectedBug->title }}</h6>
                                </div>
                                <div class="p-6">
                                    <div class="mb-6">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Description:</h4>
                                        <p class="text-gray-700">{{ $selectedBug->explanation }}</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900">Type:</span>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $selectedBug->type_color }}-100 text-{{ $selectedBug->type_color }}-800">
                                                {{ $selectedBug->type }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-900">Priority:</span>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $selectedBug->priority_color }}-100 text-{{ $selectedBug->priority_color }}-800">
                                                {{ $selectedBug->priority_text }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900">Status:</span>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $selectedBug->status_color }}-100 text-{{ $selectedBug->status_color }}-800">
                                                {{ $selectedBug->status }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-900">Client Status:</span>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $selectedBug->client_status_color }}-100 text-{{ $selectedBug->client_status_color }}-800">
                                                {{ $selectedBug->client_status }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900">Reporter:</span>
                                            <span class="ml-2 text-gray-700">{{ $selectedBug->user->name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-900">Assigned To:</span>
                                            <span class="ml-2 text-gray-700">{{ $selectedBug->assignedUser->name ?? 'Unassigned' }}</span>
                                        </div>
                                    </div>

                                    @if($selectedBug->due_date)
                                        <div class="mb-6">
                                            <span class="text-sm font-medium text-gray-900">Due Date:</span>
                                            <span class="ml-2 text-gray-700">{{ $selectedBug->due_date->format('M d, Y') }}</span>
                                        </div>
                                    @endif

                                    <!-- Images -->
                                    @if($selectedBug->images->count() > 0)
                                        <div class="mb-6">
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Images:</h4>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                @foreach($selectedBug->images as $image)
                                                    <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank" class="block">
                                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-75 transition-opacity">
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div>
                            <div class="bg-white border border-gray-200 rounded-lg">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h6 class="text-lg font-medium text-gray-900">Comments ({{ $selectedBug->comments->count() }})</h6>
                                </div>
                                <div class="p-6 max-h-96 overflow-y-auto">
                                    @foreach($selectedBug->comments as $comment)
                                        <div class="mb-4 pb-4 border-b border-gray-100 last:border-b-0">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-medium text-gray-900">{{ $comment->createdBy->name }}</span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $comment->comment_type_color }}-100 text-{{ $comment->comment_type_color }}-800">{{ $comment->comment_type }}</span>
                                                    @if($comment->is_internal)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Internal</span>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-700 text-sm mb-2">{{ $comment->comment }}</p>
                                            @if($comment->created_by === auth()->id())
                                                <button wire:click="deleteComment('{{ $comment->uuid }}')" class="inline-flex items-center px-2 py-1 border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded text-xs"
                                                    wire:confirm="Are you sure you want to delete this comment?">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="px-6 py-4 border-t border-gray-200">
                                    <form wire:submit="addComment">
                                        <div class="mb-3">
                                            <textarea wire:model="newComment" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('newComment') border-red-500 @enderror" rows="3" placeholder="Add a comment..."></textarea>
                                            @error('newComment') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 mb-3">
                                            <select wire:model="commentType" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                @foreach($commentTypeOptions as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                            <div class="flex items-center">
                                                <input wire:model="isInternalComment" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" type="checkbox" id="internalCheck">
                                                <label class="ml-2 block text-sm text-gray-900" for="internalCheck">
                                                    Internal
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md" wire:loading.attr="disabled">
                                            <span wire:loading wire:target="addComment" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
                                            Add Comment
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify-success', (message) => {
                // Replace with your notification system
                // alert('Success: ' + message);
            });

            Livewire.on('notify-error', (message) => {
                // Replace with your notification system
                alert('Error: ' + message);
            });
        });
    </script>
</div>