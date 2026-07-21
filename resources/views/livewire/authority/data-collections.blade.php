<div x-data="natureOfWorkManager()" x-init="init()">
    <!-- Notification System -->
    <div x-data="notificationSystem()" 
         x-show="show"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-0"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @notify.window="show = true; autoClose()"
         x-init="$watch('show', value => { if (value) autoClose() })"
         class="fixed inset-x-0 top-4 z-50 flex items-center justify-center px-4 sm:px-0">
    </div>

    <!-- Main Container -->
    <div class="mx-auto py-4 sm:px-6 lg:px-8" wire:key="type-of-work-manager-module-{{time()}}">
        
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{$moduleTitle}} Management</h1>
                <p class="mt-1 text-sm text-gray-600">Create and manage Approval Authority for better task categorization.</p>
            </div>
            
            <!-- Search Mode Toggle -->
            <div class="flex items-center space-x-3">
                <label class="flex items-center">
                    <input type="checkbox" 
                           wire:model.live="enableServerSearch" 
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Server Search</span>
                </label>
                <span class="text-xs text-gray-500" x-text="enableServerSearch ? 'Database search for large datasets' : 'Instant client-side filtering'"></span>
            </div>
        </div>

        <!-- Two Equal Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-10 gap-6">
            
            <!-- LEFT SECTION - Create/Edit Form -->
            <div class="bg-white shadow rounded-lg border border-gray-200 lg:col-span-3">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $isUpdate ? 'Edit Approval Authority' : 'Create Approval Authority' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $isUpdate ? 'Update the selected Approval Authority details.' : 'Add a new Approval Authority to your collection.' }}
                    </p>
                </div>

                <div class="p-6">
                    <form wire:submit="{{ $isUpdate ? 'updateDataObject' : 'saveDataObject' }}">
                        <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="title">
                                Approval Authority
                            </label>
                            <select
                                wire:model="user_id"
                                required="required"
                                class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            >
                                <option value="">Select Approval Authority</option>
                                @foreach($allUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->title ?? $user->email }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                            <div class="flex gap-3">
                                <button 
                                    type="submit"
                                    class="flex-1 inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0"
                                    wire:loading.attr="disabled"
                                    wire:target="{{ $isUpdate ? 'updateDataObject' : 'saveDataObject' }}"
                                >
                                    <span wire:loading.remove wire:target="{{ $isUpdate ? 'updateDataObject' : 'saveDataObject' }}">
                                        {{ $isUpdate ? 'Update Approval Authority' : 'Create Approval Authority' }}
                                    </span>
                                    <span wire:loading wire:target="{{ $isUpdate ? 'updateDataObject' : 'saveDataObject' }}" class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                </button>

                                @if($isUpdate)
                                    <button 
                                        type="button"
                                        wire:click="resetForm"
                                        class="inline-flex justify-center items-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                                    >
                                        Cancel
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT SECTION - Approval Authority Listing with Client-Side Filtering -->
            <div class="bg-white shadow rounded-lg border border-gray-200 lg:col-span-7">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Approval Authority List</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                <span x-show="!enableServerSearch">Instant search (client-side filtering)</span>
                                <span x-show="enableServerSearch">Database search (server-side filtering)</span>
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            <span x-text="enableServerSearch ? '{{ $data_list->total() }}' : filteredRecords.length"></span>
                            <span x-text="enableServerSearch ? ' total' : '&nbsp; filtered'"></span>
                            &nbsp;/&nbsp; <span x-text="allRecords.length"></span> &nbsp;total
                        </span>
                    </div>

                    <!-- Enhanced Search Bar -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        
                        <!-- Client-side search input -->
                        <input x-show="!enableServerSearch"
                               x-ref="clientSearchInput"
                               x-model="searchTerm"
                               @input="filterRecords()"
                               type="text"
                               placeholder="Instant search by title or creator... (Ctrl+K)"
                               @focus="searchFocused = true"
                               @blur="searchFocused = false"
                               @keydown.escape="$refs.clientSearchInput.blur(); searchTerm = ''"
                               class="block w-full pl-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all duration-200"
                               :class="{ 'ring-2 ring-indigo-500 border-indigo-500': searchFocused, 'pr-10': searchTerm, 'pr-20': !searchTerm }">

                        <!-- Server-side search input -->
                        <input x-show="enableServerSearch"
                               x-ref="serverSearchInput"
                               wire:model.live.debounce.300ms="serverSearch"
                               type="text"
                               placeholder="Database search by title or creator..."
                               @focus="searchFocused = true"
                               @blur="searchFocused = false"
                               class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all duration-200"
                               :class="{ 'ring-2 ring-indigo-500 border-indigo-500': searchFocused }">
                        
                        <!-- Clear button for client search -->
                        <div x-show="!enableServerSearch && searchTerm" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button @click="clearClientSearch()" 
                                    class="h-5 w-5 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Clear button for server search -->
                        <div x-show="enableServerSearch && @js($serverSearch)" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button wire:click="clearSearch"
                                    class="h-5 w-5 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Keyboard shortcuts hint -->
                        <div x-show="!enableServerSearch && !searchTerm" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <div class="hidden sm:flex items-center space-x-1 text-xs text-gray-400">
                                <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs font-mono">Ctrl+K</kbd>
                            </div>
                        </div>

                        <!-- Server search loading indicator -->
                        <div x-show="enableServerSearch" wire:loading wire:target="serverSearch" class="absolute inset-y-0 right-0 pr-8 flex items-center pointer-events-none">
                            <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Search Results Info -->
                    <div x-show="searchTerm || @js($serverSearch)" class="mt-3 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <template x-if="!enableServerSearch">
                                <span>
                                    <span class="font-medium text-indigo-600" x-text="filteredRecords.length"></span> 
                                    result(s) for "<span class="font-medium" x-text="searchTerm"></span>"
                                </span>
                            </template>
                            <template x-if="enableServerSearch">
                                <span>
                                    @if($serverSearch)
                                        @if($data_list->total() > 0)
                                            <span class="font-medium text-indigo-600">{{ $data_list->total() }}</span> result(s) for "<span class="font-medium">{{ $serverSearch }}</span>"
                                        @else
                                            No results found for "<span class="font-medium">{{ $serverSearch }}</span>"
                                        @endif
                                    @endif
                                </span>
                            </template>
                        </div>
                        <button @click="clearAllSearch()" class="text-xs text-indigo-600 hover:text-indigo-500 font-medium">
                            Clear search
                        </button>
                    </div>
                </div>

                <div class="p-0">
                    <!-- Client-side filtered results -->
                    <template x-if="!enableServerSearch">
                        <div>
                            <template x-if="filteredRecords.length === 0">
                                <div class="text-center py-12 px-6">
                                    <template x-if="searchTerm">
                                        <div>
                                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No Results Found</h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                Try different keywords or 
                                                <button @click="clearClientSearch()" class="text-indigo-600 hover:text-indigo-500 font-medium">clear the search</button>
                                            </p>
                                        </div>
                                    </template>
                                    <template x-if="!searchTerm">
                                        <div>
                                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0-1.125.504-1.125 1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No Approval Authority Found</h3>
                                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first Approval Authority using the form on the left.</p>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="filteredRecords.length > 0">
                                <div class="max-h-96 overflow-y-auto">
                                    <div class="divide-y divide-gray-200">
                                        <template x-for="record in paginatedRecords" :key="record.uuid">
                                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-x-3 mb-2">
                                                            <h4 class="text-sm font-semibold text-gray-900 truncate" x-html="highlightSearch(record.title)"></h4>
                                                            
                                                            <!-- Status Badge -->
                                                            <button @click="toggleRecordStatus(record.uuid)"
                                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-200 hover:opacity-80"
                                                                    :class="record.status == 1 ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'"
                                                                    title="Click to toggle status">
                                                                <span x-text="record.status == 1 ? 'Active' : 'Inactive'"></span>
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Meta Information -->
                                                        <div class="flex items-center gap-x-2 text-xs text-gray-500">
                                                            <time x-text="formatDate(record.created_at)"></time>
                                                            <span>•</span>
                                                            <span class="truncate">
                                                                by <span x-html="highlightSearch(record.created_user?.name || 'System')"></span>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Action Menu -->
                                                    <div class="flex items-center ml-4">
                                                        <div x-data="{ open: false }" class="relative">
                                                            <button @click="open = !open" 
                                                                    type="button" 
                                                                    class="flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                                                                </svg>
                                                            </button>

                                                            <div x-show="open"
                                                                 @click.away="open = false"
                                                                 x-transition:enter="transition ease-out duration-100"
                                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                                 x-transition:leave="transition ease-in duration-75"
                                                                 x-transition:leave-start="transform opacity-100 scale-100"
                                                                 x-transition:leave-end="transform opacity-0 scale-95"
                                                                 class="absolute right-0 z-10 mt-2 w-32 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                                                                 x-cloak>
                                                                <div class="py-1">
                                                                    <button @click="editRecord(record.uuid); open = false"
                                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                        Edit
                                                                    </button>
                                                                    <button @click="if(confirm('Are you sure you want to delete this Approval Authority?')) deleteRecord(record.uuid); open = false"
                                                                            class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                                        Delete
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Client-side Pagination -->
                            <div x-show="totalPages > 1" class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Showing <span x-text="((currentPage - 1) * perPage) + 1"></span> to 
                                        <span x-text="Math.min(currentPage * perPage, filteredRecords.length)"></span> of 
                                        <span x-text="filteredRecords.length"></span> results
                                    </div>
                                    <div class="flex space-x-1">
                                        <button @click="previousPage()" 
                                                :disabled="currentPage === 1"
                                                class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">
                                            Previous
                                        </button>
                                        <template x-for="page in getPageNumbers()" :key="page">
                                            <button @click="goToPage(page)"
                                                    :class="page === currentPage ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                                                    class="px-3 py-1 text-sm border border-gray-300 rounded-md">
                                                <span x-text="page"></span>
                                            </button>
                                        </template>
                                        <button @click="nextPage()" 
                                                :disabled="currentPage === totalPages"
                                                class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Server-side results (existing Livewire pagination) -->
                    <template x-if="enableServerSearch">
                        <div>
                            @if($data_list->isEmpty())
                                <div class="text-center py-12 px-6">
                                    @if($serverSearch)
                                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No Results Found</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Try different keywords or 
                                            <button wire:click="clearSearch" class="text-indigo-600 hover:text-indigo-500 font-medium">clear the search</button>
                                        </p>
                                    @else
                                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0-1.125.504-1.125 1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No Approval Authority Found</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating your first Approval Authority using the form on the left.</p>
                                    @endif
                                </div>
                            @else
                                <div class="max-h-96 overflow-y-auto">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($data_list as $typeOfWork)
                                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-x-3 mb-2">
                                                            <h4 class="text-sm font-semibold text-gray-900 truncate">
                                                                @if($serverSearch)
                                                                    {!! preg_replace('/(' . preg_quote($serverSearch, '/') . ')/i', '<mark class="bg-yellow-200">$1</mark>', e($typeOfWork['authorityUser']['name'])) !!}
                                                                @else
                                                                    {{ $typeOfWork['authorityUser']['name'] }} - [{{ $typeOfWork['authorityUser']['email'] }}]
                                                                @endif
                                                            </h4>
                                                            
                                                            <button wire:click="toggleStatus('{{ $typeOfWork['uuid'] }}')"
                                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-200 hover:opacity-80 {{ $typeOfWork['status'] == 1 ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}"
                                                                    title="Click to toggle status">
                                                                {{ $typeOfWork['status'] == 1 ? 'Active' : 'Inactive' }}
                                                            </button>
                                                        </div>
                                                        

                                                        <div class="flex items-center gap-x-2 text-xs text-gray-500">
                                                            <time datetime="{{ $typeOfWork['created_at'] }}">
                                                                {{ $typeOfWork['created_at']->diffForHumans() }}
                                                            </time>
                                                            <span>•</span>
                                                            <span class="truncate">
                                                                by 
                                                                @if($serverSearch && $typeOfWork['createdUser'])
                                                                    {!! preg_replace('/(' . preg_quote($serverSearch, '/') . ')/i', '<mark class="bg-yellow-200">$1</mark>', e($typeOfWork['createdUser']['name'])) !!}
                                                                @else
                                                                    {{ $typeOfWork['createdUser']['name'] ?? 'System' }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center ml-4">
                                                        <div x-data="{ open: false }" class="relative">
                                                          
                                                            </button>

                                                            <button wire:confirm="Are you sure you want to delete this Approval Authority?" 
                                                                            wire:click="deleteAuthority('{{ $typeOfWork['uuid'] }}')" 
                                                                            @click="open = false"
                                                                            class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                                        Delete
                                                                    </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if($data_list->hasPages())
                                    <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                                        {{ $data_list->links() }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function notificationSystem() {
    return {
        show: @entangle('notification.show'),
        timer: null,
        progress: 100,
        progressInterval: null,
        autoClose() {
            if (this.timer) clearTimeout(this.timer);
            if (this.progressInterval) clearInterval(this.progressInterval);
            
            this.progress = 100;
            
            const startTime = Date.now();
            const duration = 3000;
            this.progressInterval = setInterval(() => {
                const elapsed = Date.now() - startTime;
                this.progress = Math.max(0, Math.round((1 - elapsed/duration) * 100));
                if (this.progress <= 0) clearInterval(this.progressInterval);
            }, 20);

            this.timer = setTimeout(() => {
                this.show = false;
                @this.set('notification.show', false);
            }, duration);
        }
    }
}

function natureOfWorkManager() {
    return {
        allRecords: @js($all_records),
        filteredRecords: [],
        searchTerm: '',
        searchFocused: false,
        enableServerSearch: @entangle('enableServerSearch').live,
        currentPage: 1,
        perPage: 10,
        
        init() {
            this.filteredRecords = [...this.allRecords];
            this.setupKeyboardShortcuts();
            this.setupLivewireListeners();
        },

        setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    this.focusSearchInput();
                }
            });
        },

        setupLivewireListeners() {
            // Listen for CRUD events to update local data
            window.addEventListener('record-added', (e) => {
                this.allRecords.unshift(e.detail);
                this.filterRecords();
            });

            window.addEventListener('record-updated', (e) => {
                const index = this.allRecords.findIndex(r => r.uuid === e.detail.uuid);
                if (index !== -1) {
                    this.allRecords[index].title = e.detail.title;
                    this.filterRecords();
                }
            });

            window.addEventListener('record-deleted', (e) => {
                this.allRecords = this.allRecords.filter(r => r.uuid !== e.detail);
                this.filterRecords();
            });

            window.addEventListener('status-updated', (e) => {
                const index = this.allRecords.findIndex(r => r.uuid === e.detail.uuid);
                if (index !== -1) {
                    this.allRecords[index].status = e.detail.status;
                    this.filterRecords();
                }
            });
        },

        filterRecords() {
            if (!this.searchTerm.trim()) {
                this.filteredRecords = [...this.allRecords];
            } else {
                const term = this.searchTerm.toLowerCase();
                this.filteredRecords = this.allRecords.filter(record => 
                    record.title.toLowerCase().includes(term) ||
                    (record.created_user?.name || '').toLowerCase().includes(term)
                );
            }
            this.currentPage = 1; // Reset to first page when filtering
        },

        clearClientSearch() {
            this.searchTerm = '';
            this.filterRecords();
            this.focusSearchInput();
        },

        clearAllSearch() {
            this.searchTerm = '';
            this.filterRecords();
            @this.call('clearSearch');
        },

        focusSearchInput() {
            const input = this.enableServerSearch ? this.$refs.serverSearchInput : this.$refs.clientSearchInput;
            if (input) {
                input.focus();
                if (input.value) input.select();
            }
        },

        highlightSearch(text) {
            if (!this.searchTerm || !text) return text;
            const regex = new RegExp(`(${this.escapeRegex(this.searchTerm)})`, 'gi');
            return text.replace(regex, '<mark class="bg-yellow-200 font-semibold">$1</mark>');
        },

        escapeRegex(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        },

        // Pagination methods
        get totalPages() {
            return Math.ceil(this.filteredRecords.length / this.perPage);
        },

        get paginatedRecords() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.filteredRecords.slice(start, end);
        },

        goToPage(page) {
            this.currentPage = page;
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },

        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        getPageNumbers() {
            const pages = [];
            const maxVisible = 5;
            let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
            let end = Math.min(this.totalPages, start + maxVisible - 1);
            
            if (end - start + 1 < maxVisible) {
                start = Math.max(1, end - maxVisible + 1);
            }
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        },

        // CRUD methods that call Livewire
        editRecord(uuid) {
            @this.call('editNatureOfWork', uuid);
        },

        deleteRecord(uuid) {
            @this.call('deleteNatureOfWork', uuid);
        },

        toggleRecordStatus(uuid) {
            @this.call('toggleStatus', uuid);
        }
    }
}
</script>