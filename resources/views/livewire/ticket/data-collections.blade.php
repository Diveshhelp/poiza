<div x-data="{ showModal: @entangle('showModal') }" x-cloak>
    <!-- Create/Edit Ticket Modal -->
    <div x-show="showModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="$wire.closeModal()">
        
        <!-- Modal Backdrop -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.closeModal()"></div>

            <!-- Modal Content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isUpdate ? 'Edit Ticket' : 'Create New Ticket' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Modal Form -->
                    <form wire:submit="saveTicket">
                        <div class="space-y-4">
                            <!-- Ticket Number (Full Width) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Ticket Number <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    readonly
                                    type="text" 
                                    wire:model="ticket_unique_no"
                                    placeholder="Auto-generated ticket number"
                                    class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 bg-gray-50 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                >
                                @error('ticket_unique_no')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Two Column Layout for Branch and Nature of Work -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Select Branch -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Branch <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="branch_id"
                                        class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                    >
                                        <option value="">Select branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Select Nature Of Work -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nature of Work <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="nature_of_work_id"
                                        class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                    >
                                        <option value="">Select work type</option>
                                        @foreach($natureofwork as $nature)
                                            <option value="{{ $nature->id }}">{{ $nature->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('nature_of_work_id')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Establish Name (Full Width) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Establishment Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="establish_name"
                                    placeholder="Enter establishment name"
                                    class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                >
                                @error('establish_name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Two Column Layout for User Assignments -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Ticket Generated By -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Generated By <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="generated_by"
                                        class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                    >
                                        <option value="">Select user</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('generated_by')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Work Allotted To -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Allotted To <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="work_alloted_to"
                                        class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                    >
                                        <option value="">Select user</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('work_alloted_to')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Compact Modal Footer -->
                        <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                            <button 
                                type="button" 
                                wire:click="closeModal"
                                class="inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0"
                            >
                                <span wire:loading.remove>{{ $isUpdate ? 'Update' : 'Create' }} Ticket</span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Alternative Compact Card Layout -->
                    <div class="hidden">
                        <form wire:submit="saveTicket" class="space-y-3">
                            <!-- Header Card -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Ticket Information</h4>
                                
                                <!-- Ticket Number -->
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Ticket Number</label>
                                    <input 
                                        readonly
                                        type="text" 
                                        wire:model="ticket_unique_no"
                                        class="block w-full rounded text-sm py-1.5 px-2 bg-white border border-gray-300 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                </div>
                                
                                <!-- Establishment Name -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Establishment Name <span class="text-red-500">*</span></label>
                                    <input 
                                        type="text" 
                                        wire:model="establish_name"
                                        placeholder="Enter establishment name"
                                        class="block w-full rounded text-sm py-1.5 px-2 border border-gray-300 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                    @error('establish_name')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Assignment Card -->
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Work Assignment</h4>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <!-- Branch & Nature of Work -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Branch <span class="text-red-500">*</span></label>
                                        <select wire:model="branch_id" class="block w-full rounded text-sm py-1.5 px-2 border border-gray-300 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Select</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('branch_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Work Type <span class="text-red-500">*</span></label>
                                        <select wire:model="nature_of_work_id" class="block w-full rounded text-sm py-1.5 px-2 border border-gray-300 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Select</option>
                                            @foreach($natureofwork as $nature)
                                                <option value="{{ $nature->id }}">{{ $nature->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('nature_of_work_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                                    </div>
                                    
                                    <!-- User Assignments -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Generated By <span class="text-red-500">*</span></label>
                                        <select wire:model="generated_by" class="block w-full rounded text-sm py-1.5 px-2 border border-gray-300 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Select</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('generated_by')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Allotted To <span class="text-red-500">*</span></label>
                                        <select wire:model="work_alloted_to" class="block w-full rounded text-sm py-1.5 px-2 border border-gray-300 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Select</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('work_alloted_to')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-2 pt-4">
                                <button type="button" wire:click="closeModal" class="px-3 py-1.5 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit" wire:loading.attr="disabled" class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700 disabled:opacity-50">
                                    <span wire:loading.remove>{{ $isUpdate ? 'Update' : 'Create' }}</span>
                                    <span wire:loading>Processing...</span>
                                </button>
                            </div>
                        </form>
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
                <h1 class="text-2xl font-bold text-gray-900">Ticket Management</h1>
                <p class="mt-1 text-sm text-gray-600">Create and manage tickets for better task tracking.</p>
            </div>
            
            <!-- Create Button -->
            <button 
                wire:click="openModal"
                class="inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Ticket
            </button>
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
                            placeholder="Search by ticket#, establishment, or user..."
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
                        <option value="open">Open Tickets</option>
                        <option value="closed">Closed Tickets</option>
                        <option value="pending">Pending Approval</option>
                        <option value="transferred">Transferred</option>
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
                        @if( $this->hasActiveFilters())
                            <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $this->activeFiltersCount() }}
                            </span>
                        @endif
                    </button>

                    @if( $this->hasActiveFilters())
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
                    <!-- Generated By Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Generated By</label>
                        <select 
                            wire:model.live="generatedByFilter"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Allocated To Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Allocated To</label>
                        <select 
                            wire:model.live="allocatedToFilter"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="">All Users</option>
                            <option value="unassigned">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nature of Work Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nature of Work</label>
                        <select 
                            wire:model.live="natureOfWorkFilter"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="">All Work Types</option>
                            @foreach($natureofwork as $nature)
                                <option value="{{ $nature->id }}">{{ $nature->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Branch Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                        <select 
                            wire:model.live="branchFilter"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->title }}</option>
                            @endforeach
                        </select>
                    </div>

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

                    <!-- Approved By Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                        <select 
                            wire:model.live="approvedByFilter"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="">All Approvers</option>
                            <option value="pending">Pending Approval</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority Filter (if you have priority field) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select 
                            wire:model.live="priorityFilter"
                            class="block w-full py-2 px-3 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="">All Priorities</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
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

                <!-- Active Filters Summary -->
                @if( $this->hasActiveFilters())
                    <div class="mt-4 p-3 bg-gray-50 rounded-md">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-wrap gap-2">
                                <span class="text-sm font-medium text-gray-700">Active filters:</span>
                                
                                @if($generatedByFilter)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Generated: {{ $users->find($generatedByFilter)?->name }}
                                        <button wire:click="$set('generatedByFilter', '')" class="ml-1 hover:bg-blue-200 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                                
                                @if($branchFilter)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Branch : {{ $branchFilter === 'unassigned' ? 'Unassigned' : $branches->find($branchFilter)?->title }}
                                        <button wire:click="$set('branchFilter', '')" class="ml-1 hover:bg-green-200 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </span>
                                @endif

                                @if($allocatedToFilter)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Allocated: {{ $allocatedToFilter === 'unassigned' ? 'Unassigned' : $users->find($allocatedToFilter)?->name }}
                                        <button wire:click="$set('allocatedToFilter', '')" class="ml-1 hover:bg-green-200 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </span>
                                @endif

                                @if($natureOfWorkFilter)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Work: {{ $natureofwork->find($natureOfWorkFilter)?->title }}
                                        <button wire:click="$set('natureOfWorkFilter', '')" class="ml-1 hover:bg-purple-200 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </span>
                                @endif

                                @if($fromDate || $toDate)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Date: {{ $fromDate ? $fromDate : 'Start' }} - {{ $toDate ? $toDate : 'End' }}
                                        <button wire:click="clearDateFilters" class="ml-1 hover:bg-yellow-200 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Ticket Tabs and Table Container -->
        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
            <!-- Ticket Status Tabs -->
            <div class="border-b border-gray-200">
                <!-- Desktop Tabs -->
                <nav class="lg:flex -mb-px space-x-2 px-4 overflow-x-auto" aria-label="Tabs">
            <button 
                wire:click="setStatusFilter('all')"
                class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                All
                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'all' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }}">
                    {{ $allTicketsCount }}
                </span>
            </button>
            
            <button 
                wire:click="setStatusFilter('created_approval')"
                class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'created_approval' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Created Approval
                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'created_approval' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600' }}">
                    {{ $createdApprovalCount }}
                </span>
            </button>
            
            <button 
                wire:click="setStatusFilter('closed_approval')"
                class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'closed_approval' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Close Approval
                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'closed_approval' ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-600' }}">
                    {{ $closedApprovalCount }}
                </span>
            </button>
            
            <button 
                wire:click="setStatusFilter('running')"
                class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'running' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Running Ticket
                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'running' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                    {{ $runningTicketsCount }}
                </span>
            </button>
            
            <button 
                wire:click="setStatusFilter('closed')"
                class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'closed' ? 'border-gray-500 text-gray-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Closed Ticket
                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'closed' ? 'bg-gray-100 text-gray-600' : 'bg-gray-100 text-gray-600' }}">
                    {{ $closedTicketsCount }}
                </span>
            </button>
            
            <button 
                wire:click="setStatusFilter('transferred_running')"
                class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'transferred_running' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
            Transfer Running
                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'transferred_running' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                    {{ $transferredRunningCount }}
                </span>
            </button>
            
            <button 
                wire:click="setStatusFilter('transferred_closed')"
                class="whitespace-nowrap border-b-2 py-2 px-2 text-xs font-medium transition-colors duration-200 {{ $statusFilter === 'transferred_closed' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Transfer Closed
                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusFilter === 'transferred_closed' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' }}">
                    {{ $transferredClosedCount }}
                </span>
            </button>
        </nav>


        <!-- Mobile Dropdown -->
        <div class="lg:hidden px-4 py-3" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span>{{ $this->getFilterTitle() }}</span>
                <svg class="w-5 h-5 ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
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
                 x-cloak
                 class="absolute z-10 w-full max-w-sm mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                <div class="py-1">
                    <button wire:click="setStatusFilter('all')" @click="open = false" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $statusFilter === 'all' ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <span>All Tickets</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $allTicketsCount }}</span>
                    </button>
                    <button wire:click="setStatusFilter('created_approval')" @click="open = false" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $statusFilter === 'created_approval' ? 'bg-yellow-50 text-yellow-700' : '' }}">
                        <span>Created Approval</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $createdApprovalCount }}</span>
                    </button>
                    <button wire:click="setStatusFilter('closed_approval')" @click="open = false" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $statusFilter === 'closed_approval' ? 'bg-orange-50 text-orange-700' : '' }}">
                        <span>Closed Approval</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $closedApprovalCount }}</span>
                    </button>
                    <button wire:click="setStatusFilter('running')" @click="open = false" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $statusFilter === 'running' ? 'bg-green-50 text-green-700' : '' }}">
                        <span>Running Tickets</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $runningTicketsCount }}</span>
                    </button>
                    <button wire:click="setStatusFilter('closed')" @click="open = false" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $statusFilter === 'closed' ? 'bg-gray-50 text-gray-700' : '' }}">
                        <span>Closed Tickets</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $closedTicketsCount }}</span>
                    </button>
                    <button wire:click="setStatusFilter('transferred_running')" @click="open = false" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $statusFilter === 'transferred_running' ? 'bg-blue-50 text-blue-700' : '' }}">
                        <span>Transferred Running</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $transferredRunningCount }}</span>
                    </button>
                    <button wire:click="setStatusFilter('transferred_closed')" @click="open = false" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $statusFilter === 'transferred_closed' ? 'bg-purple-50 text-purple-700' : '' }}">
                        <span>Transferred Closed</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $transferredClosedCount }}</span>
                    </button>
                </div>
            </div>
        </div>
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
                    {{ $tickets->total() }} tickets
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

    <!-- Table Wrapper with Horizontal Scroll -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <!-- Sticky Ticket Number Column -->
                    <th scope="col" class="sticky left-0 z-10 bg-gray-50 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200 whitespace-nowrap">
                    Establishment Name
                    </th>
                    
                    
                    <!-- Regular Columns -->
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Nature Of Work
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Created Date
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-28">
                        Branch
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Generated By
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Approved By
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Approved Date
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Allotted To
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Transfer To
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Transfer Date
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Closed By
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Close Approved By
                    </th>
                    
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-32">
                        Close Approved Date
                    </th>
                    
                    <!-- Status Column -->
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-24">
                        Status
                    </th>
                    
                    <!-- Actions Column -->
                    <th scope="col" class="sticky right-0 z-10 bg-gray-50 px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-l border-gray-200 whitespace-nowrap min-w-24">
                        Actions
                    </th>
                </tr>
            </thead>
            
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <!-- Sticky Ticket Number -->
                        <td class="sticky left-0 z-10 bg-white px-4 py-3 text-sm font-medium text-gray-900 border-r border-gray-200 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-indigo-600 font-semibold">{{ $ticket->ticket_unique_no }}</span>
                               
                            </div>
                            <div class="font-medium text-gray-900 truncate" title="{{ $ticket->establish_name }}">
                                    {{ $ticket->establish_name }}
                                </div>
                        </td>
                        <!-- Nature Of Work -->
                        <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <a href="{{ route('ticketsForm') }}?form={{ $ticket->nature_of_work_id }}&link={{ $ticket->id }}&token={{ $ticket->uuid }}" target="_blank">{{ $ticket->natureOfWork->title ?? 'N/A' }}</a>
                            </span>
                        </td>
                        
                        <!-- Created Date -->
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-gray-900">{{ $ticket->created_at->format('M d, Y') }}</span>
                                <span class="text-xs text-gray-500">{{ $ticket->created_at->format('h:i A') }}</span>
                            </div>
                        </td>
                        
                        <!-- Branch -->
                        <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                            {{ $ticket->branch->title ?? 'N/A' }}
                        </td>
                        
                        <!-- Generated By -->
                        <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-700">
                                            {{ substr($ticket->generatedBy->name ?? 'N', 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $ticket->generatedBy->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Approved By -->
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                            @if($ticket->generated_by == $user_id)
                                {{-- Only created by me can approve by me --}}
                                @if($ticket->approvedBy)
                                    {{-- Approved status with done icon --}}
                                    <div class="flex items-center space-x-2">
                                        <div class="flex items-center space-x-1.5">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-green-700">Approved</span>
                                        </div>
                                       
                                    </div>
                                    <p class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-600">BY {{ $ticket->approvedBy->name }}</span>
                                        </p>
                                @else
                                    {{-- Approve button for my tickets --}}
                                    <div class="flex items-center space-x-3">
                                        <button
                                            wire:confirm="Are you sure you want to approve this ticket?"
                                            wire:click="changeApprove({{ $ticket->id }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 shadow-sm"
                                            title="Click to approve this ticket"
                                        >
                                            <svg class="h-3 w-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Approve
                                        </button>
                                 
                                    </div>
                                    <span class="text-sm text-yellow-600 font-medium flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Pending
                                        </span>
                                @endif
                            @else
                                {{-- Created by other person - only show pending status --}}
                                @if($ticket->approvedBy)
                                    {{-- Show approved status for tickets created by others --}}
                                    <div class="flex items-center space-x-2">
                                        <div class="flex items-center space-x-1.5">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-green-700">Approved</span>
                                        </div>
                                       
                                    </div>
                                    <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-600">{{ $ticket->approvedBy->name }}</span>
                                        </div>
                                @else
                                    {{-- Pending status for tickets created by others --}}
                                    <div class="flex items-center space-x-1.5">
                                        <svg class="h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm text-yellow-600 font-medium">Pending</span>
                                    </div>
                                @endif
                            @endif
                        </td>
                        
                        <!-- Approved Date -->
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                            @if($ticket->ticket_approve_date)
                                <div class="flex flex-col">
                                    <span>{{ $ticket->ticket_approve_date->format('M d, Y') }}</span>
                                    <span class="text-xs">{{ $ticket->ticket_approve_date->format('h:i A') }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                        
                        <!-- Allotted To -->
                        <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                            @if($ticket->workAllottedTo)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-6 w-6">
                                        <div class="h-6 w-6 rounded-full bg-purple-100 flex items-center justify-center">
                                            <span class="text-xs font-medium text-purple-700">
                                                {{ substr($ticket->workAllottedTo->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="ml-2 text-sm">{{ $ticket->workAllottedTo->name }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 italic">Unassigned</span>
                            @endif
                        </td>
                     <!-- Transfer To -->
                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                        @if($ticket->ticketTransferredTo)
                            {{-- Already transferred - show transferred user --}}
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center space-x-1.5">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414L2.586 8l3.707-3.707a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-blue-700">Transferred</span>
                                </div>
                            
                            </div>
                            <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-600">{{ $ticket->ticketTransferredTo->name }}</span>
                                </div>
                        @else
                            {{-- Not transferred yet - show transfer button/dropdown --}}
                            @if($ticket->work_alloted_to == auth()->id())
                                {{-- Only creator or admin can transfer --}}
                                <div class="flex items-center space-x-2">
                                    @if($showTransferDropdown && $selectedTicketId == $ticket->id)
                                        {{-- Transfer dropdown is open --}}
                                        <div class="relative inline-block">
                                            <select 
                                                wire:model="selectedUserId"
                                                wire:change="transferTicket({{ $ticket->id }})"
                                                class="block w-48 px-3 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            >
                                                <option value="">Select User...</option>
                                                @foreach($availableUsers as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            <button 
                                                wire:click="cancelTransfer"
                                                class="ml-2 text-gray-400 hover:text-gray-600"
                                                title="Cancel transfer"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        {{-- Transfer button --}}
                                        <button
                                            wire:click="showTransferOptions({{ $ticket->id }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200 shadow-sm"
                                            title="Transfer this ticket to another user"
                                        >
                                            <svg class="h-3 w-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                            Transfer
                                        </button>
                                    @endif
                                </div>
                            @else
                                {{-- User cannot transfer this ticket --}}
                                <div class="flex items-center space-x-1.5">
                                    <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-400 italic">No Access</span>
                                </div>
                            @endif
                        @endif
                    </td>

                    <!-- Transfer Date -->
                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                        @if($ticket->ticket_transfered_date)
                            <div class="flex items-center space-x-1.5">
                                <svg class="h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-600 font-medium">
                                    {{ $ticket->ticket_transfered_date->format('M d, Y') }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $ticket->ticket_transfered_date->format('h:i A') }}
                                </span>
                            </div>
                        @else
                            <div class="flex items-center space-x-1.5">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-400 italic">Not Transferred</span>
                            </div>
                        @endif
                    </td>
                                            
                    <!-- Closed By -->
                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                        @if($ticket->ticketClosedBy)
                            {{-- Already closed - show who closed it --}}
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center space-x-1.5">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5 9.293 10.793a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-red-700">Closed</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">{{ $ticket->ticketClosedBy->name }}</span>
                            </div>
                        @else
                            {{-- Ticket is not closed yet --}}
                            @if($ticket->generated_by == $ticket->user_id && $ticket->approvedBy)
                                {{-- Only creator can close AND ticket must be approved --}}
                                <button
                                    wire:confirm="Are you sure you want to close this ticket?"
                                    wire:click="closeTicket({{ $ticket->id }})"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 shadow-sm"
                                    title="Click to close this ticket"
                                >
                                    <svg class="h-3 w-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Close
                                </button>
                            @elseif($ticket->generated_by == $ticket->user_id && !$ticket->approvedBy)
                                {{-- My ticket but not approved yet --}}
                                <div class="flex items-center space-x-1.5">
                                    <svg class="h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-amber-600 font-medium">Awaiting Approval</span>
                                </div>
                            @elseif($ticket->generated_by != $ticket->user_id)
                                {{-- Not my ticket --}}
                                <div class="flex items-center space-x-1.5">
                                    <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-400 font-medium">Restricted</span>
                                </div>
                            @else
                                {{-- Default case --}}
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        @endif
                    </td>
                        
                   <!-- Close Approved By -->
                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                        @if($ticket->ticketCloseApprovedBy)
                            {{-- Close approval is done - show who approved --}}
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center space-x-1.5">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-green-700">Approved</span>
                                </div>
                                
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">BY {{ $ticket->ticketCloseApprovedBy->name }}</span>
                            </div>
                        @elseif($ticket->ticketClosedBy)
                            {{-- Ticket is closed but not approved yet --}}
                            @if($ticket->generated_by == auth()->id())
                                {{-- Current user is the creator - can approve close --}}
                                <div class="flex items-center space-x-3">
                                    <button
                                        wire:confirm="Are you sure you want to approve the closure of this ticket?"
                                        wire:click="approveTicketClose({{ $ticket->id }})"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-sm"
                                        title="Approve ticket closure"
                                    >
                                        <svg class="h-3 w-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                        </svg>
                                        Approve Close
                                    </button>
                                    
                                </div>
                                <span class="text-sm text-orange-600 font-medium flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        Awaiting Your Approval
                                    </span>
                            @else
                                {{-- Current user is not the creator - can't approve --}}
                                <div class="flex items-center space-x-1.5">
                                    <svg class="h-4 w-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-orange-600 font-medium">Pending Creator Approval</span>
                                </div>
                            @endif
                        @else
                            {{-- Ticket is not closed yet --}}
                            <div class="flex items-center space-x-1.5">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-400 italic">Not Applicable</span>
                            </div>
                        @endif
                    </td>
                        
                        <!-- Close Approved Date -->
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                            @if($ticket->ticket_close_approve_date)
                                {{ $ticket->ticket_close_approve_date->format('M d, Y') }}
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                        
                        <!-- Status -->
                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                            <button 
                                wire:click="toggleStatus({{ $ticket->id }})"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 hover:opacity-80 {{ $ticket->status == '1' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}"
                                title="Click to toggle status"
                            >
                                {{ $ticket->status == '1' ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        
                        <!-- Sticky Actions -->
                        <td class="sticky right-0 z-10 bg-white px-4 py-3 text-right text-sm font-medium border-l border-gray-200 whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-2">
                                <button 
                                    wire:click="editTicket({{ $ticket->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                                    title="Edit ticket"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button 
                                    wire:click="deleteTicket({{ $ticket->id }})" 
                                    wire:confirm="Are you sure you want to delete this ticket?"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                    title="Delete ticket"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="16" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0-1.125.504-1.125 1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">No tickets found</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating your first ticket.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($tickets->hasPages())
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            {{ $tickets->links() }}
        </div>
    @endif
</div>

<!-- Mobile-Optimized Card View (Alternative for very small screens) -->
<div class="block md:hidden space-y-4">
    @foreach($tickets as $ticket)
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
            <!-- Header -->
            <div class="flex items-center justify-between mb-3">
                <div class="font-semibold text-indigo-600">{{ $ticket->ticket_unique_no }}</div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->status == '1' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $ticket->status == '1' ? 'Active' : 'Inactive' }}
                </span>
            </div>
            
            <!-- Content -->
            <div class="space-y-2 text-sm">
                <div><span class="font-medium text-gray-700">Establishment:</span> {{ $ticket->establish_name }}</div>
                <div><span class="font-medium text-gray-700">Branch:</span> {{ $ticket->branch->title ?? 'N/A' }}</div>
                <div><span class="font-medium text-gray-700">Nature of Work:</span> {{ $ticket->natureOfWork->title ?? 'N/A' }}</div>
                <div><span class="font-medium text-gray-700">Generated By:</span> {{ $ticket->generatedBy->name ?? 'N/A' }}</div>
                <div><span class="font-medium text-gray-700">Allotted To:</span> {{ $ticket->workAllottedTo->name ?? 'Unassigned' }}</div>
                <div><span class="font-medium text-gray-700">Created:</span> {{ $ticket->created_at->format('M d, Y h:i A') }}</div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-end space-x-2 mt-4 pt-3 border-t border-gray-200">
                <button wire:click="editTicket({{ $ticket->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                    Edit
                </button>
                <button wire:click="deleteTicket({{ $ticket->id }})" wire:confirm="Are you sure?" class="text-red-600 hover:text-red-900 text-sm font-medium">
                    Delete
                </button>
            </div>
        </div>
    @endforeach
</div>
        <!-- Mobile-Optimized Card View (Alternative for very small screens) -->
        <div class="block md:hidden space-y-4">
            @foreach($tickets as $ticket)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-indigo-600">{{ $ticket->ticket_unique_no }}</div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->status == '1' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $ticket->status == '1' ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <!-- Content -->
                    <div class="space-y-2 text-sm">
                        <div><span class="font-medium text-gray-700">Establishment:</span> {{ $ticket->establish_name }}</div>
                        <div><span class="font-medium text-gray-700">Branch:</span> {{ $ticket->branch->title ?? 'N/A' }}</div>
                        <div><span class="font-medium text-gray-700">Nature of Work:</span> {{ $ticket->natureOfWork->title ?? 'N/A' }}</div>
                        <div><span class="font-medium text-gray-700">Generated By:</span> {{ $ticket->generatedBy->name ?? 'N/A' }}</div>
                        <div><span class="font-medium text-gray-700">Allotted To:</span> {{ $ticket->workAllottedTo->name ?? 'Unassigned' }}</div>
                        <div><span class="font-medium text-gray-700">Created:</span> {{ $ticket->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex justify-end space-x-2 mt-4 pt-3 border-t border-gray-200">
                        <button wire:click="editTicket({{ $ticket->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            Edit
                        </button>
                        <button wire:click="deleteTicket({{ $ticket->id }})" wire:confirm="Are you sure?" class="text-red-600 hover:text-red-900 text-sm font-medium">
                            Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>