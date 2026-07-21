<div x-data="{
    showModal: false,
    selectedDocument: null,
    selectedSubDocuments: '',
    init() {
        this.$wire.on('show-document-modal', (data) => {
            this.selectedDocument = data[0].document;
            this.selectedSubDocuments = data[0].subdocument;
            console.log(this.selectedSubDocuments);
            this.showModal = true;
        });
    },
    formatDate(date) {
        if (!date) return '';
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    formatDateIndian(date) {
        if (!date) return '';
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },
    downloading: false,
    downloadFile(attachmentId) {
        this.downloading = true;
        this.$wire.downloadFile(attachmentId)
            .then(() => {
                this.downloading = false;
            })
            .catch(() => {
                this.downloading = false;
            });
    },
    DOC_VALIDITY: @js($doc_validity_list),
}" x-cloak @keydown.escape.window="showModal = false">
<div  class="mx-auto py-2 sm:px-6 lg:px-8">
        <div>
            <div class="flex flex-wrap items-center justify-between">
                <div class="min-w-0 flex-1 pr-2">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">{{ $moduleTitle }}</h3>
                    <p
                        class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600 leading-tight sm:leading-relaxed line-clamp-1 sm:line-clamp-none">
                        List all of your documents!
                    </p>
                </div>
                @if(count($alreadySelectedDocs)>0)
                <div class="flex items-center justify-between" wire:target="clearSelection,selectAllRecords" >
                    <button
                        wire:click="clearSelection"
                        wire:loading.attr="disabled"
                        type="button"
                        class="mr-2 inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0"
                    >
                        <div  wire:target="clearSelection" class="h-5 w-5 mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        
                        <div wire:loading wire:target="clearSelection" class="h-5 w-5 mr-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        
                        Clear
                        <span class="ml-1.5 inline-flex items-center px-2.5 py-0.2 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ count($alreadySelectedDocs) }}
                        </span>
                    </button>
                </div>
                <div class="flex items-center justify-between"  >
                    <a href="{{ route('document-sender') }}"
                        
                        class="mr-2 inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0"
                    >
                        Send Document 
                    </a>
                </div>
                @endif

                <div>
                    <button
                        title="Filter by expire date in 30 days from now"
                        wire:click="toggleExpiringFilter"
                        wire:loading.attr="disabled"
                        type="button"
                        class="mr-2 inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0
                            {{ $showExpiringOnly
                                ? 'bg-red-600 hover:bg-red-700 text-white'
                                : 'bg-blue-400 hover:bg-blue-500 text-white' }}"
                    >
                        <div wire:loading.remove wire:target="toggleExpiringFilter" class="h-5 w-5 mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        
                        <div wire:loading wire:target="toggleExpiringFilter" class="h-5 w-5 mr-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        
                        @if($expiringCount > 0 && $showExpiringOnly)
                        Clear Filter
                        <span class="ml-1.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $expiringCount }}
                            </span>
                        @else
                        Expiring Soon
                        @endif
                    </button>
                </div>
                <div class="mr-2 relative">
                    <input
                        type="text"
                        id="filterGlobalData"
                        wire:model.live.debounce.300ms="filterGlobalData"
                        class="mr-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                        placeholder="Search your doc...">
                    
                    <!-- Loading indicator that shows only during search -->
                    <div wire:loading wire:target="filterGlobalData" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2 sm:mt-0" x-data="{
                showFilters: false,
                      isLoading: false,

                filters: {
                    docTitle: '',
                    docName: '',
                    ownerName: '',
                    docValidity: '',
                    docRenewalDate: '',
                    docNote: '',
                    docCategory: ''
                },
                submitSearch() {
                    this.isLoading = true;
                    this.loadingButton = 'search';
                    // Simulate API call
                    setTimeout(() => {
                        // Your search logic here
                        this.isLoading = false;
                        this.loadingButton = null;
                    }, 1500);
                    $wire.searchDocuments(this.filters);
                },
                resetFilters() {
                    this.isLoading = true;
                    this.loadingButton = 'reset';
                    setTimeout(() => {
                        this.isLoading = false;
                        this.loadingButton = null;
                    }, 1500);
                    this.filters = {
                        docTitle: '',
                        docName: '',
                        ownerName: '',
                        docValidity: '',
                        docRenewalDate: '',
                        docNote: '',
                        docCategory: ''
                    };
                    showFilters: true,
                        $wire.resetSearch();
                }
            }" wire:key="document-filter-module-{{ time() }}">
                <!-- Filter Toggle Button -->
                <button @click="showFilters = !showFilters"
                    class="w-full  inline-flex items-center justify-between border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <div class="flex items-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>

                        <!-- Active filters counter -->
                        <span x-show="Object.values(filters).some(value => value !== '')"
                            class="ml-2 text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">
                            <span x-text="Object.values(filters).filter(value => value !== '').length"></span>
                        </span>
                    </div>
                    <!-- Toggle arrow -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200"
                        :class="showFilters ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Filter Panel -->
                <div x-show="showFilters" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="bg-white p-4 rounded-lg shadow-md mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <!--Document Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document Category</label>
                            <select x-model="filters.docCategory" @click.stop
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Categories</option>
                                @foreach ($doc_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Document Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document Title</label>
                            <input type="text" x-model="filters.docTitle" @click.stop
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Search by title...">
                        </div>
                        <!-- Owner Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner</label>
                            <select x-model="filters.ownerName" @click.stop
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Owners</option>
                                @foreach ($ownerships as $ownership)
                                    <option value="{{ $ownership->id }}">{{ $ownership->owner_title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Document Validity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document Validity</label>
                            <select x-model="filters.docValidity" @click.stop
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Validities</option>
                                @foreach ($doc_validity_list as $key => $validity)
                                    <option value="{{ $key }}">{{ $validity }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Document Renewal Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Renewal Date</label>
                            <input type="date" x-model="filters.docRenewalDate" @click.stop
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">By Note</label>
                            <input type="text" x-model="filters.docNote" @click.stop
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Search by doc note...">
                        </div>

                    </div>

                    <!-- Search and Reset Buttons -->
                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="resetFilters()" 
                            :disabled="isLoading"
                            class="inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
                            <template x-if="isLoading && loadingButton === 'reset'">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span>Clear</span>
                        </button>
                        <button @click="submitSearch()" 
                            :disabled="isLoading"
                            class="inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
                            <template x-if="isLoading && loadingButton === 'search'">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <template x-if="!(isLoading && loadingButton === 'search')">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </template>
                            <span>Search</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex shrink-0 sm:ml-4">
                    <a href="{{ route('documents.index') }}"
                        class="inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mr-1.5 size-4 sm:size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="whitespace-nowrap">Add Document</span>
                    </a>
                </div>
            </div>
        
        </div>
       
        <!-- Success/Error Messages -->
        <div>
            @if (session()->has('success'))
                <div class="bg-green-50 p-4 rounded-lg mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-50 p-4 rounded-lg mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-2 bg-white rounded-lg shadow overflow-x-auto">
            <!-- Desktop Table View -->
            <table class="hidden md:table min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                    <th class="px-2 py-2 text-left">
                    <input
                        type="checkbox"
                        wire:model="masterSelection"
                        wire:click="$toggle('masterSelection')"
                        wire:change="selectAllRecords"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    >

                        </th>
                        <th wire:click="sortBy('ownership_name')" class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Owner
                            @if ($sortField === 'doc_title')
                                <span class="ml-1">
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                </span>
                            @endif
                        </th>
                        <th wire:click="sortBy('doc_title')" class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Title
                            @if ($sortField === 'doc_title')
                                <span class="ml-1">
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                </span>
                            @endif
                        </th>
                        <th wire:click="sortBy('doc_categories_id')" class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Doc Category
                            @if ($sortField === 'doc_categories_id')
                                <span class="ml-1">
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                </span>
                            @endif
                        </th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Exp. Date</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Notes</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Attachments</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        </th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sharing
                        </th>  
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            
                        </th>
                       
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($documents as $document)
                    <tr class="leading-none h-8">
                    <td class="px-3 py-4 whitespace-nowrap">
                            <span wire:loading wire:target="selectAllRecords"
                                class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue"
                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>

                            <input  wire:loading.remove wire:target="selectAllRecords"
                                type="checkbox" 
                                wire:model="selectedItems"
                                wire:change="toggleSelection({{ $document->id }}, $event.target.checked)"
                                value='{{ $document->id }}'
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </td>
                            <td class="px-4 py-3 " wire:click="showDocumentDetails('{{ $document->uuid }}')">
                                <div class="text-sm text-gray-900">{{ $document->ownership->owner_title ?? 'Unknown' }}</div>
                            </td>
                            <td class="px-4 py-3  cursor-pointer">
                                <div class="text-sm font-medium text-gray-900">{{ $document->doc_title }}</div>
                                @if($document->share_with_firm)
                                    <div x-data="{ showNotes: false, showAddNote: true, newNote: '', loading: false }" x-cloak class="relative">
                                        <!-- Notes Summary Button -->
                                        <button @click="showNotes = !showNotes"
                                            class="inline-flex items-center text-indigo-600 hover:text-indigo-900 w-[100px]">
                                            <small><span
                                                class="truncate">{{ $document->notes()->count() }}
                                                Follow Up</span></small>
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
                                                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                                                    <!-- Header with Add Follow Up Button -->
                                                    <div
                                                        class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 px-6 py-4">
                                                        <div
                                                            class="flex justify-between items-center">
                                                            <h3
                                                                class="text-base font-semibold text-gray-900">
                                                                Follow Up About Document</h3>
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
                                                    <div x-show="showAddNote"  x-data="{ 
                                                                showAddNote: true, 
                                                                newNote: '', 
                                                                loading: false,
                                                                saveNote() {
                                                                    if (!this.newNote.trim() || this.loading) return;
                                                                    this.loading = true;
                                                                    $wire.addNote('{{ $document->uuid }}', this.newNote).then(() => {
                                                                        this.loading = false;
                                                                        this.showAddNote = true;
                                                                        this.newNote = '';
                                                                    });
                                                                }
                                                            }" 
                                                            @keydown.ctrl.s.prevent="saveNote()"
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                                        x-transition:enter-end="opacity-100 translate-y-0"
                                                        class="border-b">
                                                        <div class="p-4 bg-gray-50">
                                                            <textarea x-model="newNote" placeholder="Type your note here..."
                                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                                rows="3"></textarea>
                                                                <p class="text-xs text-gray-500 mt-1">💡 Tip: Press <span class="text-red-700"> Ctrl+S </span> to save quickly</p>
                                                            <div class="flex justify-end space-x-2 mt-2">
                                                                <button @click="saveNote()"  :disabled="!newNote.trim() || loading"
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
                                                    <div class="px-4 py-2">
                                                        <div class="space-y-2 max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                                                            @forelse($document->notes()->orderBy('created_at', 'desc')->get() as $note)
                                                                <div class="group bg-white rounded-lg border border-gray-200 hover:border-indigo-300 hover:shadow-sm transition-all duration-200">
                                                                    <div class="flex items-start gap-3 p-3">
                                                                        <!-- Avatar -->
                                                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                                                            <span class="text-white text-sm font-medium">
                                                                                {{ strtoupper(substr($note->user->name, 0, 2)) }}
                                                                            </span>
                                                                        </div>
                                                                        
                                                                        <!-- Content -->
                                                                        <div class="flex-1 min-w-0">
                                                                            <!-- Header -->
                                                                            <div class="flex items-center justify-between mb-1">
                                                                                <div class="flex items-center gap-2">
                                                                                    <span class="text-sm font-medium text-gray-900">{{ $note->user->name }}</span>
                                                                                    <span class="text-xs text-gray-500">{{ $note->created_at->format('F d, Y - h:i:s A') }}</span>
                                                                                </div>
                                                                                <button wire:confirm="Do you want to delete this note?" wire:click="deleteNote('{{ $note->id }}')"
                                                                                    class="opacity-0 group-hover:opacity-100 p-1 rounded text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
                                                                            
                                                                            <!-- Note Content -->
                                                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $note->content }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <div class="text-center py-8 bg-white rounded-lg border border-gray-200">
                                                                    <div class="w-12 h-12 mx-auto mb-3 bg-indigo-50 rounded-full flex items-center justify-center">
                                                                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                @endif
                            </td>
                        
                            <td class="px-4 py-3 " wire:click="showDocumentDetails('{{ $document->uuid }}')">
                                <div class="text-sm text-gray-900">
                                    {{ $document->category->category_title ?? 'Uncategorized' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 " wire:click="showDocumentDetails('{{ $document->uuid }}')">
                                <div class="text-sm text-gray-900">
                                @if($document->doc_expire_date!=NULL)
                                    {{ date('d-m-Y',strtotime($document->doc_expire_date)) }}
                                @endif
                                </div>
                            </td>
                            <td class="px-4 py-3" title="{{ $document->doc_note }}">
                                <!-- Note with Icon - Inline display -->
                                <div class="flex items-center">
                                    @if ($document->doc_note != '')
                                        <div x-data="{ showNotePopup: false }" @keydown.escape.window="showNotePopup = false"
                                            class="underline  cursor-pointer flex items-center w-full">

                                        

                                            <!-- Note Text (Truncated) - Same line as icon -->
                                            <span  @click="showNotePopup = true"
                                                class="text-sm text-gray-900 ">{{ Str::limit($document->doc_note, 128) }}</span>

                                            <!-- Note Popup Dialog -->
                                            <div x-show="showNotePopup" @click.away="showNotePopup = false"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                                style="backdrop-filter: blur(2px);">

                                                <div class="bg-white rounded-lg shadow-xl border border-gray-200 w-full max-w-md p-0 m-4 text-gray-700"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 transform scale-95"
                                                    x-transition:enter-end="opacity-100 transform scale-100"
                                                    x-transition:leave="transition ease-in duration-200"
                                                    x-transition:leave-start="opacity-100 transform scale-100"
                                                    x-transition:leave-end="opacity-0 transform scale-95">

                                                    <!-- Popup Header -->
                                                    <div
                                                        class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                                                        <h3
                                                            class="text-lg font-medium text-gray-900 flex items-center">
                                                            <svg class="w-5 h-5 mr-2 text-blue-500"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Note Details
                                                        </h3>
                                                        <button @click="showNotePopup = false"
                                                            class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-full p-1">
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Popup Body -->
                                                    <div class="px-6 py-4 max-h-64 overflow-y-auto">
                                                        <div class="whitespace-pre-line text-gray-700 leading-relaxed">
                                                            {{ $document->doc_note }}
                                                        </div>
                                                    </div>

                                                    <!-- Popup Footer -->
                                                    <div
                                                        class="px-6 py-3 bg-gray-50 rounded-b-lg text-right border-t border-gray-200">
                                                        <span class="text-xs text-gray-500">Press ESC to close</span>
                                                        <button @click="showNotePopup = false"
                                                            class="ml-4 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Display placeholder when no note is available -->
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-400 italic">No notes available</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-3 py-2">
                                <div x-data="{
                                    count: {{ $document->attachments->count() }},
                                    subDocCount: {{ $document->subDocuments->count()??0 }},
                                    getBgColor() {
                                        return this.count === 0 ? 'bg-gray-100 text-gray-600' :
                                            this.count <= 2 ? 'bg-blue-100 text-blue-700' :
                                            this.count <= 5 ? 'bg-green-100 text-green-700' :
                                            'bg-purple-100 text-purple-700';
                                    },
                                    getSubDocBgColor() {
                                        return this.subDocCount === 0 ? 'bg-gray-100 text-gray-500' :
                                            this.subDocCount <= 2 ? 'bg-indigo-100 text-indigo-700' :
                                            this.subDocCount <= 5 ? 'bg-teal-100 text-teal-700' :
                                            'bg-amber-100 text-amber-700';
                                    },
                                     resetCounters() {
                                        this.count = 0;
                                        this.subDocCount = 0;
                                    }
                                }" class="flex gap-2"  wire:key="document-counter-{{ $document->id }}"
                                >
                                    <!-- Files badge -->
                                    <div :class="getBgColor()" 
                                        class="flex items-center px-2 py-1 rounded-md shadow-sm hover:shadow transition-shadow duration-200"
                                        title="Document attachments">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-xs font-medium ml-1.5" x-text="count"></span>
                                    </div>
                                                                
                                    <!-- Sub-docs badge -->
                                    <a href="{{ route('sub-documents', ['parentId' => $document->uuid]) }}"
                                    :class="getSubDocBgColor()"
                                    class="flex items-center px-2 py-1 rounded-md shadow-sm hover:shadow-md transition-all duration-200 ease-in-out transform hover:scale-105"
                                    title="Sub-documents">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                        <span class="text-xs font-medium ml-1.5" x-text="subDocCount"></span>
                                    </a>
                                </div>
                            </td>
                            <td class="px-4 py-3  group relative cursor-pointer">
                                <div class="flex items-center space-x-2">


                                    <!-- Email Actions Container -->
                                    <div class="flex items-center space-x-4">
                                        <!-- Email Attachments Action -->
                                        <div wire:click="openSendAttachmentsModal('{{ $document->uuid }}')"
                                            title="Send Email"
                                            class="p-2.5 bg-gray-100 rounded-full hover:bg-blue-100 transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md group">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600 group-hover:scale-110 transition-all duration-200"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>

                                        <!-- View Email History Action -->
                                        <div wire:click="viewSentEmails('{{ $document->uuid }}')"
                                            title="View Email History"
                                            class="p-2.5 bg-gray-100 rounded-full hover:bg-indigo-100 transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md group">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-indigo-600 group-hover:scale-110 transition-all duration-200"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>

                                    
                            </td>
                            <td class="px-4 py-3">
                               
                            @if (array_intersect(['1', '2'], $userRoles)) 
                                <div class="space-y-1">
                                    <!-- Sharing Status -->
                                    @if($document->share_with_firm)
                                        <div class=" inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M18 8h1a4 4 0 010 8h-1m-2-4v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8a2 2 0 012 2v4zM8 12a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4z"/>
                                            </svg>
                                            <small class="group relative">
                                                <span class="max-w-[100px] truncate" title="{{ $document->sharedBy->name ?? '' }}">
                                                    Shared by {{ $document->sharedBy->name ?? '' }}
                                                </span>
                                                
                                                <!-- Tooltip on hover -->
                                                @if($document->sharedBy->name ?? '')
                                                    <div class="absolute bottom-full left-0 mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-10 pointer-events-none">
                                                        Shared by {{ $document->sharedBy->name }}
                                                    </div>
                                                @endif
                                            </small>
                                        </div>
                                         <!-- Work Status - Admin/Super Admin with Toggle Switch -->
                                        <div class="flex items-center gap-2">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input 
                                                    type="checkbox" 
                                                    wire:change="toggleCompletion('{{ $document->uuid }}')"
                                                    {{ ($document->is_completed ?? false) ? 'checked' : '' }}
                                                    class="sr-only peer"
                                                >
                                                <div class="relative w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:start-[1px] after:bg-white after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-green-500"></div>
                                            </label>
                                            <div class="flex items-center gap-1 text-xs {{ ($document->is_completed ?? false) ? 'text-green-600' : 'text-amber-600' }}">
                                                @if($document->is_completed ?? false)
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                    </svg>
                                                    <small>Completed</small>
                                                    <?php
                                                    $message = "🎉 Great news! \n\nDear {$document->sharedBy->name}, \n\nYour document '*{$document->doc_title}*' has been successfully processed and entered into our system. All details have been verified and saved. \n\nThank you for choosing our services! 📋✅";
                                                    $encodedMessage = urlencode($message);
                                                    $whatsappUrl = "https://api.whatsapp.com/send?text={$encodedMessage}&phone={$document->sharedBy->mobile}";
                                                    ?>

                                            <a href="{{ $whatsappUrl }}" 
                                            target="_blank" 
                                            title="Send notification to client via your whatsapp!"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1 px-2 py-1hover:bg-green-600 rounded text-xs font-medium hover:shadow-sm transition-all duration-200">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 308 308">
                                                    <path d="M227.904,176.981c-0.6-0.288-23.054-11.345-27.044-12.781c-1.629-0.585-3.374-1.156-5.23-1.156 c-3.032,0-5.579,1.511-7.563,4.479c-2.243,3.334-9.033,11.271-11.131,13.642c-0.274,0.313-0.648,0.687-0.872,0.687 c-0.201,0-3.676-1.431-4.728-1.888c-24.087-10.463-42.37-35.624-44.877-39.867c-0.358-0.61-0.373-0.887-0.376-0.887 c0.088-0.323,0.898-1.135,1.316-1.554c1.223-1.21,2.548-2.805,3.83-4.348c0.607-0.731,1.215-1.463,1.812-2.153 c1.86-2.164,2.688-3.844,3.648-5.79l0.503-1.011c2.344-4.657,0.342-8.587-0.305-9.856c-0.531-1.062-10.012-23.944-11.02-26.348 c-2.424-5.801-5.627-8.502-10.078-8.502c-0.413,0,0,0-1.732,0.073c-2.109,0.089-13.594,1.601-18.672,4.802 c-5.385,3.395-14.495,14.217-14.495,33.249c0,17.129,10.87,33.302,15.537,39.453c0.116,0.155,0.329,0.47,0.638,0.922 c17.873,26.102,40.154,45.446,62.741,54.469c21.745,8.686,32.042,9.69,37.896,9.69c0.001,0,0.001,0,0.001,0 c2.46,0,4.429-0.193,6.166-0.364l1.102-0.105c7.512-0.666,24.02-9.22,27.775-19.655c2.958-8.219,3.738-17.199,1.77-20.458 C233.168,179.508,230.845,178.393,227.904,176.981z"/>
                                                    <path d="M156.734,0C73.318,0,5.454,67.354,5.454,150.143c0,26.777,7.166,52.988,20.741,75.928L0.212,302.716 c-0.484,1.429-0.124,3.009,0.933,4.085C1.908,307.58,2.943,308,4,308c0.405,0,0.813-0.061,1.211-0.188l79.92-25.396 c21.87,11.685,46.588,17.853,71.604,17.853C240.143,300.27,308,232.923,308,150.143C308,67.354,240.143,0,156.734,0z M156.734,268.994c-23.539,0-46.338-6.797-65.936-19.657c-0.659-0.433-1.424-0.655-2.194-0.655c-0.407,0-0.815,0.062-1.212,0.188 l-40.035,12.726l12.924-38.129c0.418-1.234,0.209-2.595-0.561-3.647c-14.924-20.392-22.813-44.485-22.813-69.677 c0-65.543,53.754-118.867,119.826-118.867c66.064,0,119.812,53.324,119.812,118.867 C276.546,215.678,222.799,268.994,156.734,268.994z"/>
                                                </svg>
                                                Notify
                                            </a>
                                                @else
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7,13H17V11H7"/>
                                                    </svg>
                                                    <small>Ppending</small>
                                                @endif
                                               
                                            </div>
                                        </div>
                                     
                                    @else
                                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1ZM12 7C13.1 7 14 7.9 14 9C14 10.1 13.1 11 12 11C10.9 11 10 10.1 10 9C10 7.9 10.9 7 12 7ZM18 15C18 15.39 17.81 15.78 17.5 16C16.47 16.5 14.34 17 12 17C9.66 17 7.53 16.5 6.5 16C6.19 15.78 6 15.39 6 15V13.5C6 13.5 8.5 14.5 12 14.5C15.5 14.5 18 13.5 18 13.5V15Z"/>
                                            </svg>
                                            Private
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="space-y-1">
                                    <!-- Sharing Status -->
                                    @if($document->share_with_firm)
                                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M18 8h1a4 4 0 010 8h-1m-2-4v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8a2 2 0 012 2v4zM8 12a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4z"/>
                                            </svg>
                                            Shared
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1ZM12 7C13.1 7 14 7.9 14 9C14 10.1 13.1 11 12 11C10.9 11 10 10.1 10 9C10 7.9 10.9 7 12 7ZM18 15C18 15.39 17.81 15.78 17.5 16C16.47 16.5 14.34 17 12 17C9.66 17 7.53 16.5 6.5 16C6.19 15.78 6 15.39 6 15V13.5C6 13.5 8.5 14.5 12 14.5C15.5 14.5 18 13.5 18 13.5V15Z"/>
                                            </svg>
                                            Private
                                        </div>
                                    @endif
                                    
                                    <!-- Work Status - Regular Users (Read Only) -->
                                     @if($document->share_with_firm)
                                        @if($document->is_completed)
                                            <div class="flex items-center gap-1 text-xs text-green-600">
                                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                </svg>
                                                <small>Work completed</small>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1 text-xs text-amber-600">
                                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7,13H17V11H7"/>
                                                </svg>
                                                <small>Work pending</small>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endif

                           
                            </td>

                            <td class="relative py-3 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                <div x-data="{
                                    open: false,
                                    menuStyle: {
                                        top: '0px',
                                        left: '0px'
                                    },
                                    initializeMenu() {
                                        const button = this.$refs.menuButton;
                                        const buttonRect = button.getBoundingClientRect();
                                        const windowHeight = window.innerHeight;
                                        const menuHeight = 150;
                                
                                        if (windowHeight - buttonRect.bottom < menuHeight) {
                                            this.menuStyle = {
                                                top: `${buttonRect.top - menuHeight}px`,
                                                left: `${buttonRect.left - 180}px`
                                            };
                                        } else {
                                            this.menuStyle = {
                                                top: `${buttonRect.bottom}px`,
                                                left: `${buttonRect.left - 180}px`
                                            };
                                        }
                                    }
                                }" @click.away="open = false"
                                    class="relative inline-block text-left">
                                    <!-- Menu Button -->
                                    <button @click="open = !open; $nextTick(() => initializeMenu())"
                                        x-ref="menuButton"
                                        class="p-1.5 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div x-show="open" x-ref="menuItems"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95" :style="menuStyle"
                                        class="fixed w-48 rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none z-[9999]">
                                        <div class="py-1 bg-white rounded-md divide-y divide-gray-100">

                                            <button wire:click="showDocumentDetails('{{ $document->uuid }}')"
                                                @click="open = false"
                                                class="flex items-center w-full px-4 py-1.5 text-xs text-gray-700 hover:bg-gray-50 group">
                                                <svg class="mr-2 h-3.5 w-3.5 text-gray-400 group-hover:text-gray-500"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View Details
                                            </button>
                                            <button wire:click="openSendAttachmentsModal('{{ $document->uuid }}')"
                                                @click="open = false"
                                                class="flex items-center w-full px-4 py-1.5 text-xs text-gray-700 hover:bg-gray-50 group">
                                                <svg class="mr-2 h-3.5 w-3.5 text-gray-400 group-hover:text-gray-500"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                Email Attachments
                                            </button>

                                            <a href="{{ route('documents.updated', ['uuid' => $document->uuid]) }}"
                                                class="flex items-center w-full px-4 py-1.5 text-xs text-gray-700 hover:bg-gray-50 group">
                                                <svg class="mr-2 h-3.5 w-3.5 text-gray-400 group-hover:text-gray-500"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>

                                            <button wire:confirm="Are you sure you want to delete this document?"
                                                wire:click="deleteDocument('{{ $document->uuid }}')"
                                                @click="open = false"
                                                class="flex items-center w-full px-4 py-1.5 text-xs text-red-600 hover:bg-red-50 group">
                                                <svg class="mr-2 h-3.5 w-3.5 text-red-400 group-hover:text-red-500"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4  text-center text-gray-500">
                            No documents found. Create your first document to get started!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    
    </div>
    <span class="p-5 m-4">
            @if (isset($documents) && $documents != [])
                <div>
                    {{ $documents->links() }}
                </div>
            @endif
        </span>
    <div x-data="{
        open: false,
        parentId: null,
        parentName: '',
        subDocuments: [],
        loading: false,
        showForm: false,
        formData: {
            title: '',
            doc_name: '',
            doc_update_date: '',
            doc_expire_date:'',
            validity: 1,
            doc_number: '',
            doc_info: ''
        },
        resetForm() {
            this.formData = {
                title: '',
                doc_name: '',
                doc_number: '',
                doc_info: '',
                doc_update_date: '',
                validity: 1,
                files: []
            };
            this.showForm = false;
        }
    }"
        @open-subdoc-modal.window="
                open = true; 
                parentId = $event.detail.parentId;
                loading = true;
                $wire.getParentDocument(parentId).then(result => {
                    parentName = result.doc_title;
                    subDocuments = result.sub_documents || [];
                    console.log(result);
                    loading = false;
                });
            "
        @close-subdoc-modal.window="open = false; resetForm();"
        @subdoc-added.window="
                subDocuments.push($event.detail.document);
                resetForm();
            "
        x-show="open" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" @click="open = false; resetForm();" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full sm:p-6"
                @click.away="if(!showForm) { open = false; resetForm(); }">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button @click="open = false; resetForm();" type="button"
                        class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal header -->
                <div class="sm:flex sm:items-start">
                    <div
                        class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-blue-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Sub Documents for <span class="font-semibold" x-text="parentName"></span>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Manage all sub-documents associated with this parent document.
                        </p>
                    </div>
                </div>

                <!-- Modal content -->
                <div class="mt-4">
                    <!-- Loader -->
                    <div x-show="loading" class="flex justify-center py-8">
                        <svg class="w-10 h-10 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>

                    <!-- List of sub documents -->
                    <div x-show="!loading && !showForm" class="overflow-hidden bg-white shadow sm:rounded-md">
                        <!-- Two-column grid layout for sub documents -->
                        <div class="p-4">

                            <!-- Documents grid - two columns -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <template x-for="(subDoc, index) in subDocuments" :key="index">
                                    <div
                                        class="border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <!-- Document header with basic info -->
                                        <div class="p-4 border-b border-gray-100">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100">
                                                        <svg class="w-4 h-4 text-blue-600"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-gray-900"
                                                                x-text="subDoc.doc_title"></div>
                                                            <div class="ml-2 px-2 py-0.5 text-xs bg-gray-100 rounded-full text-gray-600"
                                                                x-text="subDoc.doc_number"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span
                                                    :class="{
                                                        'bg-green-100 text-green-800': subDoc.doc_validity === 1,
                                                        'bg-yellow-100 text-yellow-800': subDoc.doc_validity === 2,
                                                        'bg-red-100 text-red-800': subDoc.doc_validity === 3
                                                    }"
                                                    class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full"
                                                    x-text="subDoc.doc_validity === 1 ? 'Valid' : subDoc.doc_validity === 2 ? 'Expiring Soon' : 'Expired'"></span>
                                            </div>
                                        </div>

                                        <!-- Attachments section - always visible -->
                                        <div class="p-4">
                                            <!-- When there are attachments -->
                                            <template x-if="subDoc.attachments && subDoc.attachments.length > 0">
                                                <div class="mt-2">
                                                    <!-- List of attachments -->
                                                    <div class="space-y-1">
                                                        <template x-for="(file, fileIndex) in subDoc.attachments"
                                                            :key="fileIndex">
                                                            <div class="flex items-center w-full">
                                                                <!-- File link -->
                                                                <a :href="file.url || '#'" target="_blank"
                                                                    class="group flex items-center flex-grow px-2 py-1.5 bg-gray-50 rounded border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-colors">
                                                                    <!-- File icon based on type -->
                                                                    <!-- PDF -->
                                                                    <template
                                                                        x-if="file.type && file.type.includes('pdf')">
                                                                        <svg class="h-4 w-4 text-red-500 flex-shrink-0"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                        </svg>
                                                                    </template>

                                                                    <!-- Word -->
                                                                    <template
                                                                        x-if="file.type && (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx'))">
                                                                        <svg class="h-4 w-4 text-blue-600 flex-shrink-0"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                        </svg>
                                                                    </template>

                                                                    <!-- Excel -->
                                                                    <template
                                                                        x-if="file.type && (file.type.includes('excel') || file.name.endsWith('.xls') || file.name.endsWith('.xlsx'))">
                                                                        <svg class="h-4 w-4 text-green-600 flex-shrink-0"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                        </svg>
                                                                    </template>

                                                                    <!-- Image -->
                                                                    <template
                                                                        x-if="file.type && file.type.includes('image')">
                                                                        <svg class="h-4 w-4 text-purple-500 flex-shrink-0"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                        </svg>
                                                                    </template>

                                                                    <!-- Default -->
                                                                    <template
                                                                        x-if="!file.type || (!file.type.includes('pdf') && !file.type.includes('word') && !file.name.endsWith('.doc') && !file.name.endsWith('.docx') && !file.type.includes('excel') && !file.name.endsWith('.xls') && !file.name.endsWith('.xlsx') && !file.type.includes('image'))">
                                                                        <svg class="h-4 w-4 text-gray-400 flex-shrink-0"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                        </svg>
                                                                    </template>

                                                                    <!-- File name -->
                                                                    <span
                                                                        class="ml-2 text-xs font-medium text-gray-700 truncate flex-grow"
                                                                        x-text="file.name"></span>

                                                                    <!-- File size -->
                                                                    <span
                                                                        class="ml-2 text-xs text-gray-500 flex-shrink-0"
                                                                        x-text="file.size ? (file.size/1024).toFixed(1) + ' KB' : ''"></span>

                                                                    <!-- Download icon (appears on hover) -->
                                                                    <div
                                                                        class="ml-2 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                                                        <svg class="h-4 w-4 text-gray-500"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                        </svg>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>

                                        </div>

                                        <!-- Actions footer -->
                                        <div
                                            class="flex justify-end p-3 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                                            <!-- Delete button -->
                                            <div x-data="{ showDeleteConfirm: false }">
                                                <button @click="showDeleteConfirm = true"
                                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-600 bg-white border border-gray-200 rounded hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                    type="button">
                                                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>

                                                <!-- Confirmation Modal -->
                                                <div x-show="showDeleteConfirm"
                                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="transition ease-in duration-200"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0"
                                                    @click.away="showDeleteConfirm = false">
                                                    <div
                                                        class="w-full max-w-sm p-6 mx-4 bg-white rounded-lg shadow-xl">
                                                        <div
                                                            class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                                                            <svg class="w-6 h-6 text-red-600"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="mb-4 text-lg font-medium text-center text-gray-900">
                                                            Confirm Deletion</h3>
                                                        <p class="mb-5 text-sm text-center text-gray-500">Are you sure
                                                            you want to delete this document? This action cannot be
                                                            undone.</p>
                                                        <div class="flex justify-center space-x-4">
                                                            <button @click="showDeleteConfirm = false"
                                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                                                                Cancel
                                                            </button>
                                                            <button
                                                                @click="showDeleteConfirm = false; $wire.deleteSubDocument(subDoc.uuid).then(() => {
                                                                    // After deletion completes, refresh the subdocuments list
                                                                    $wire.getParentDocument(parentId).then(result => {
                                                                        subDocuments = result.sub_documents || [];
                                                                    });
                                                                })"
                                                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
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

                            <!-- Empty state -->
                            <div x-show="subDocuments.length === 0" class="py-6 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No sub documents</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new sub document.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add new sub document form with improved layout -->
                    <div x-show="showForm" class="space-y-6">
                        <!-- Two column layout for main form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left column -->
                            <div class="space-y-4">
                                <!-- Document Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Document
                                        Title <span class="text-red-500">*</span></label>
                                    <input type="text" id="title" x-model="formData.title"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <!-- Document Number -->
                                <div>
                                    <label for="doc_number" class="block text-sm font-medium text-gray-700">Document
                                        Number <span class="text-red-500">*</span></label>
                                    <input type="text" id="doc_number" x-model="formData.doc_number"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <!-- Right column -->
                            <div class="space-y-4">
                                <!-- Document Issue Date -->
                                <div>
                                    <label for="doc_update_date" class="block text-sm font-medium text-gray-700">Issue
                                        Date <span class="text-red-500">*</span></label>
                                    <div x-data="{ date: formData.doc_update_date }">
                                        <input x-ref="datepicker" x-init="flatpickr($refs.datepicker, { enableTime: true, dateFormat: 'Y-m-d h:i K', time_24hr: false })" type="text"
                                            placeholder="Select date and time" x-model="formData.doc_update_date"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label for="doc_expire_date" class="block text-sm font-medium text-gray-700">Expire
                                        Date <span class="text-red-500">*</span></label>
                                    <div x-data="{ date: formData.doc_expire_date }">
                                        <input x-ref="datepicker" x-init="flatpickr($refs.datepicker, { enableTime: true, dateFormat: 'Y-m-d h:i K', time_24hr: false })" type="text"
                                            placeholder="Select date and time" x-model="formData.doc_expire_date"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                                <!-- Validity Status -->
                                <div>
                                    <label for="validity" class="block text-sm font-medium text-gray-700">Validity
                                        Status <span class="text-red-500">*</span></label>
                                    <select id="validity" x-model="formData.validity"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="1">Valid</option>
                                        <option value="2">Expiring Soon</option>
                                        <option value="3">Expired</option>
                                    </select>
                                </div>

                                <!-- Document Info -->

                            </div>

                        </div>
                        <div>
                            <label for="doc_info" class="block text-sm font-medium text-gray-700">Document Info <span
                                    class="text-red-500">*</span></label>
                            <textarea id="doc_info" x-model="formData.doc_info" rows="1"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter additional information about this document..."></textarea>
                        </div>
                        <!-- File upload section (full width) -->
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-md font-medium text-gray-700 mb-3">Document Attachments</h4>

                            <!-- Improved File Upload Component -->
                            <div class="space-y-4">
                                <div>
                                    <!-- Upload Container -->
                                    <div
                                        class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition-colors">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="file-upload"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                    <span>Upload files</span>
                                                    <input id="file-upload" wire:model="files" type="file"
                                                        multiple class="sr-only"
                                                        @change="$dispatch('files-selected')">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                PDF, DOC, DOCX, XLS, XLSX, JPG, PNG up to 10MB each
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- File Preview Area with reset functionality -->
                                <div x-data="{
                                    filesAdded: false,
                                    files: [],
                                
                                    // Method to remove a single file
                                    removeFile(index) {
                                        this.files.splice(index, 1);
                                        const input = document.getElementById('file-upload');
                                        const newFileList = new DataTransfer();
                                
                                        for (let i = 0; i < this.files.length; i++) {
                                            newFileList.items.add(this.files[i]);
                                        }
                                
                                        input.files = newFileList.files;
                                        const event = new Event('change');
                                        input.dispatchEvent(event);
                                
                                        // If no files remain, update the state
                                        if (this.files.length === 0) {
                                            this.filesAdded = false;
                                        }
                                    },
                                
                                    // Method to reset the entire file upload component
                                    resetFileUpload() {
                                        this.files = [];
                                        this.filesAdded = false;
                                
                                        // Reset the file input element
                                        const input = document.getElementById('file-upload');
                                        if (input) {
                                            input.value = '';
                                        }
                                
                                        // Reset Livewire file property if using Livewire
                                        if (typeof $wire !== 'undefined' && $wire.files) {
                                            $wire.set('files', []);
                                        }
                                    }
                                }"
                                    @files-selected.window="
                                            const input = document.getElementById('file-upload');
                                            if(input.files) {
                                                filesAdded = true;
                                                files = Array.from(input.files);
                                            }
                                        "
                                    @reset-form.window="resetFileUpload()" @subdoc-added.window="resetFileUpload()"
                                    @close-subdoc-modal.window="resetFileUpload()" class="w-full">
                                    <div x-show="filesAdded && files.length > 0" class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-3">Selected files:</h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                            <template x-for="(file, index) in files" :key="index">
                                                <div
                                                    class="relative flex flex-col border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
                                                    <!-- File Preview (if image) -->
                                                    <div
                                                        class="h-32 bg-gray-50 flex items-center justify-center overflow-hidden">
                                                        <template x-if="file.type.includes('image/')">
                                                            <img :src="URL.createObjectURL(file)"
                                                                class="h-full w-full object-contain" />
                                                        </template>

                                                        <template x-if="!file.type.includes('image/')">
                                                            <div class="flex flex-col items-center justify-center p-4">
                                                                <!-- PDF Icon -->
                                                                <template x-if="file.type.includes('pdf')">
                                                                    <svg class="h-12 w-12 text-red-500"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                    </svg>
                                                                </template>

                                                                <!-- Word Document Icon -->
                                                                <template
                                                                    x-if="file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')">
                                                                    <svg class="h-12 w-12 text-blue-600"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                </template>

                                                                <!-- Excel Spreadsheet Icon -->
                                                                <template
                                                                    x-if="file.type.includes('excel') || file.name.endsWith('.xls') || file.name.endsWith('.xlsx')">
                                                                    <svg class="h-12 w-12 text-green-600"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                </template>

                                                                <!-- Generic Document Icon (default) -->
                                                                <template
                                                                    x-if="!file.type.includes('pdf') && !file.type.includes('word') && !file.name.endsWith('.doc') && !file.name.endsWith('.docx') && !file.type.includes('excel') && !file.name.endsWith('.xls') && !file.name.endsWith('.xlsx')">
                                                                    <svg class="h-12 w-12 text-gray-400"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                </template>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <!-- File Info -->
                                                    <div class="p-3">
                                                        <div class="truncate text-sm font-medium text-gray-900"
                                                            x-text="file.name"></div>
                                                        <div class="text-xs text-gray-500 mt-1"
                                                            x-text="(file.size/1024).toFixed(1) + ' KB'"></div>
                                                    </div>

                                                    <!-- Remove Button -->
                                                    <button @click="removeFile(index)" type="button"
                                                        class="absolute top-2 right-2 rounded-full bg-white bg-opacity-75 p-1 text-red-500 hover:text-red-700 focus:outline-none">
                                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Progress (when files are being uploaded) -->
                                <div wire:loading wire:target="files" class="mt-2">
                                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="animate-spin h-5 w-5 text-blue-600"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-blue-800">Processing files</h3>
                                                <div class="mt-1 text-xs text-blue-700">
                                                    Your files are being uploaded, please wait...
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal actions -->
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <template x-if="!showForm">
                            <button @click="showForm = true" type="button"
                                class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                Add New Sub Document
                            </button>
                        </template>

                        <template x-if="showForm">
                            <div class="flex gap-2 justify-end">
                                <button
                                    @click="$wire.addSubDocument(parentId, formData).then(result => {
                                            if(result.success) {
                                                // Use the existing subdoc-added event to update the list
                                                window.dispatchEvent(new CustomEvent('subdoc-added', {
                                                    detail: { document: result.document }
                                                }));
                                                
                                                // Reset the form and also trigger the file upload reset
                                                resetForm();
                                                $dispatch('reset-form');
                                                
                                                // Hide the form
                                                showForm = false;
                                            }
                                        })"
                                    type="button"
                                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                    Save Document
                                </button>
                                <button @click="resetForm();" type="button"
                                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                    Cancel
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile View (visible only on small screens) -->
    <div class="md:hidden space-y-4">
        @forelse ($documents as $document)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <!-- Document Header -->
                <div class="p-4 bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0 pr-4">
                            <h3 class="text-base font-semibold text-gray-900 truncate">
                                {{ $document->doc_title }}
                            </h3>
                        </div>

                        <!-- Primary Action Button -->
                        <button wire:click="showDocumentDetails('{{ $document->uuid }}')"
                            class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Document Body -->
                <div class="p-4 space-y-4">
                    <!-- Main Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Owner Info -->
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">Owner</p>
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $document->ownership->owner_title ?? 'Unknown' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5.586 5.586c.391.39.586.902.586 1.414V17a4 4 0 01-4 4H7a4 4 0 01-4-4V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">Doc Category</p>
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $document->category->category_title ?? 'Uncategorized' }}
                                </p>
                            </div>
                        </div>

                        <!-- Attachments Count -->
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </div>
                            <div x-data="{
                                count: {{ $document->attachments->count() }},
                                getBgColor() {
                                    if (this.count === 0) return 'bg-gray-100 text-gray-800';
                                    if (this.count <= 2) return 'bg-blue-100 text-blue-800';
                                    if (this.count <= 5) return 'bg-green-100 text-green-800';
                                    return 'bg-purple-100 text-purple-800';
                                }
                            }">
                                <p class="text-xs text-gray-500">Attachments</p>
                                <span :class="getBgColor()"
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium">
                                    <span x-text="`${count} ${count === 1 ? 'file' : 'files'}`"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Sub Documents Section -->
                    <div class="bg-white rounded-xl shadow-sm p-4 mx-auto max-w-md">
                        <div x-data="{
                            count: {{ $document->attachments->count() }},
                            subDocCount: {{ $document->subDocuments->count() }},
                            getBgColor() {
                                if (this.count === 0) return 'bg-gray-100 text-gray-800';
                                if (this.count <= 2) return 'bg-blue-100 text-blue-800';
                                if (this.count <= 5) return 'bg-green-100 text-green-800';
                                return 'bg-purple-100 text-purple-800';
                            },
                            getSubDocBgColor() {
                                if (this.subDocCount === 0) return 'bg-gray-100 text-gray-500';
                                if (this.subDocCount <= 2) return 'bg-indigo-100 text-indigo-800';
                                if (this.subDocCount <= 5) return 'bg-teal-100 text-teal-800';
                                return 'bg-amber-100 text-amber-800';
                            }
                        }" class="flex flex-col space-y-4">
                            <!-- Header -->
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900">Sub Documents</h3>
                                <span :class="getSubDocBgColor()" class="px-2.5 py-1 rounded-full text-xs font-medium"
                                    x-text="`${subDocCount} total`"></span>
                            </div>

                            <!-- Sub-documents counter with details -->
                            <div @click="$dispatch('open-subdoc-modal', { parentId: '{{ $document->uuid }}' })"
                                :class="getSubDocBgColor()"
                                class="flex items-center p-4 rounded-lg cursor-pointer transition-all duration-150 hover:bg-opacity-90 active:scale-98">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="text-sm font-medium"
                                        x-text="`${subDocCount} ${subDocCount === 1 ? 'sub-document' : 'sub-documents'}`">
                                    </div>
                                    <p class="text-xs mt-1 opacity-75">Tap to view details</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Add Sub Doc button -->
                            <button @click="$dispatch('open-subdoc-modal', { parentId: '{{ $document->uuid }}' })"
                                class="flex items-center justify-center w-full px-4 py-3 rounded-lg bg-blue-100 text-blue-800 hover:bg-blue-200 active:bg-blue-150 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span class="ml-2 text-sm font-medium">Add New Sub Document</span>
                            </button>

                            <!-- Bottom hint -->
                            <p class="text-xs text-gray-500 text-center">
                                You can manage all your sub-documents here
                            </p>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="flex flex-col space-y-3 sm:space-y-2">
                        <!-- Validity Badge -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-1 sm:space-y-0">
                            <span class="text-xs sm:text-xs text-gray-500 font-medium">Document Status</span>
                            <span @class([
                                'inline-flex items-center px-3 py-1.5 sm:px-2.5 sm:py-0.5 rounded-full text-sm sm:text-xs font-medium w-fit',
                                'bg-green-100 text-green-800' => $document->doc_validity === 1,
                                'bg-yellow-100 text-yellow-800' => $document->doc_validity === 2,
                                'bg-red-100 text-red-800' => $document->doc_validity === 3,
                            ])>
                                <svg class="h-4 w-4 sm:h-3 sm:w-3 mr-1.5 sm:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ DOC_VALIDITY[$document->doc_validity] }}
                            </span>
                        </div>

                        <!-- Renewal Date -->
                        @if ($document->doc_renewal_dt && $document->doc_validity !== 1)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-1 sm:space-y-0">
                                <span class="text-xs sm:text-xs text-gray-500 font-medium">Renewal Date</span>
                                <span class="text-sm sm:text-xs font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($document->doc_renewal_dt)->format('M d, Y') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Sharing & Work Status Section -->
                    <div class="flex flex-col space-y-3 pt-3 border-t border-gray-100">
                        @if (array_intersect(['1', '2'], $userRoles)) 
                            <!-- Admin/Super Admin View -->
                            <div class="space-y-3">
                                <!-- Sharing Status -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                                    <span class="text-xs text-gray-500 font-medium">Sharing</span>
                                    @if($document->share_with_firm)
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-2.5 sm:py-1 rounded-full text-sm sm:text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 w-fit">
                                            <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M18 8h1a4 4 0 010 8h-1m-2-4v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8a2 2 0 012 2v4zM8 12a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4z"/>
                                            </svg>
                                            Shared with Firm
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-2.5 sm:py-1 rounded-full text-sm sm:text-xs font-medium bg-slate-50 text-slate-600 border border-slate-200 w-fit">
                                            <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1ZM12 7C13.1 7 14 7.9 14 9C14 10.1 13.1 11 12 11C10.9 11 10 10.1 10 9C10 7.9 10.9 7 12 7ZM18 15C18 15.39 17.81 15.78 17.5 16C16.47 16.5 14.34 17 12 17C9.66 17 7.53 16.5 6.5 16C6.19 15.78 6 15.39 6 15V13.5C6 13.5 8.5 14.5 12 14.5C15.5 14.5 18 13.5 18 13.5V15Z"/>
                                            </svg>
                                            Private Document
                                        </div>
                                    @endif
                                </div>

                                <!-- Work Status with Toggle - Mobile Optimized -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                                    <span class="text-xs text-gray-500 font-medium">Work Status</span>
                                    <div class="flex items-center gap-3">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                wire:change="toggleCompletion('{{ $document->uuid }}')"
                                                {{ ($document->is_completed ?? false) ? 'checked' : '' }}
                                                class="sr-only peer"
                                            >
                                            <!-- Larger toggle for mobile -->
                                            <div class="relative w-11 h-6 sm:w-8 sm:h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] sm:after:top-[1px] after:start-[2px] sm:after:start-[1px] after:bg-white after:rounded-full after:h-5 after:w-5 sm:after:h-3.5 sm:after:w-3.5 after:transition-all peer-checked:bg-green-500"></div>
                                        </label>
                                        <div class="flex items-center gap-1.5 {{ ($document->is_completed ?? false) ? 'text-green-600' : 'text-amber-600' }}">
                                            @if($document->is_completed ?? false)
                                                <svg class="w-4 h-4 sm:w-3 sm:h-3" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                </svg>
                                                <span class="text-sm sm:text-xs font-medium">Completed</span>
                                            @else
                                                <svg class="w-4 h-4 sm:w-3 sm:h-3" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7,13H17V11H7"/>
                                                </svg>
                                                <span class="text-sm sm:text-xs font-medium">Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Regular User View -->
                            <div class="space-y-3">
                                <!-- Sharing Status -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                                    <span class="text-xs text-gray-500 font-medium">Sharing</span>
                                    @if($document->share_with_firm)
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-2.5 sm:py-1 rounded-full text-sm sm:text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 w-fit">
                                            <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M18 8h1a4 4 0 010 8h-1m-2-4v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8a2 2 0 012 2v4zM8 12a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4z"/>
                                            </svg>
                                            Shared with Firm
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-2.5 sm:py-1 rounded-full text-sm sm:text-xs font-medium bg-slate-50 text-slate-600 border border-slate-200 w-fit">
                                            <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1ZM12 7C13.1 7 14 7.9 14 9C14 10.1 13.1 11 12 11C10.9 11 10 10.1 10 9C10 7.9 10.9 7 12 7ZM18 15C18 15.39 17.81 15.78 17.5 16C16.47 16.5 14.34 17 12 17C9.66 17 7.53 16.5 6.5 16C6.19 15.78 6 15.39 6 15V13.5C6 13.5 8.5 14.5 12 14.5C15.5 14.5 18 13.5 18 13.5V15Z"/>
                                            </svg>
                                            Private Document
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Work Status - Read Only -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                                    <span class="text-xs text-gray-500 font-medium">Work Status</span>
                                    @if($document->is_completed ?? false && $document->share_with_firm==true)
                                        <div class="flex items-center gap-1.5 text-green-600 w-fit">
                                            <svg class="w-4 h-4 sm:w-3 sm:h-3" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                            </svg>
                                            <span class="text-sm sm:text-xs font-medium">Work completed</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 text-amber-600 w-fit">
                                            <svg class="w-4 h-4 sm:w-3 sm:h-3" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7,13H17V11H7"/>
                                            </svg>
                                            <span class="text-sm sm:text-xs font-medium">Work pending</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-2">
                            <!-- View Button -->
                            <button wire:click="showDocumentDetails('{{ $document->uuid }}')"
                                class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </button>

                            <!-- Edit Button -->
                            <a href="{{ route('documents.updated', ['uuid' => $document->uuid]) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        </div>

                        <!-- Delete Button -->
                        <button wire:confirm="Are you sure you want to delete this document?"
                            wire:click="deleteDocument('{{ $document->uuid }}')"
                            class="inline-flex items-center px-3 py-1.5 bg-white border border-red-200 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="text-center py-12 bg-white rounded-xl border border-gray-100">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No documents</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new document.</p>
            </div>
        @endforelse
    </div>
    <!-- Backdrop -->
    <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
        @click="showModal = false">
    </div>

    <!-- Modal Content -->
    <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="fixed inset-0 z-50 overflow-y-auto" @click.away="showModal = false">

        <div class="flex min-h-full w-full items-center justify-center p-4">
            <div
                class="relative transform bg-white shadow-2xl transition-all rounded-xl overflow-x-auto">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-800" x-text="selectedDocument?.doc_title"></h3>
                        <button @click="showModal = false"
                            class="rounded-full p-1 text-gray-700 hover:bg-black/10 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column - Document Details -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Basic Info -->
                            <div
                                class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200">
                                <!-- Header with Icon -->
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="p-2 bg-blue-50 rounded-lg">
                                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-semibold text-gray-800">Document Information</h4>
                                </div>

                                <!-- Compact Information Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <!-- Left Column -->
                                    <div class="space-y-2">
                                        <!-- Category -->
                                        <div class="group p-2 rounded hover:bg-gray-50 transition-colors">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="h-3.5 w-3.5 text-gray-400 group-hover:text-blue-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5.586 5.586c.391.39.586.902.586 1.414V17a4 4 0 01-4 4H7a4 4 0 01-4-4V7a4 4 0 014-4z" />
                                                </svg>
                                                <span
                                                    class="text-xs text-gray-500 group-hover:text-gray-700">Category</span>
                                            </div>
                                            <p class="text-sm font-medium pl-5 mt-0.5"
                                                x-text="selectedDocument?.doc_categories?.category_title || 'Uncategorized'">
                                            </p>
                                        </div>

                                        <!-- Owner -->
                                        <div class="group p-2 rounded hover:bg-gray-50 transition-colors">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="h-3.5 w-3.5 text-gray-400 group-hover:text-blue-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span
                                                    class="text-xs text-gray-500 group-hover:text-gray-700">Owner</span>
                                            </div>
                                            <p class="text-sm font-medium pl-5 mt-0.5"
                                                x-text="selectedDocument?.ownership?.owner_title ?? 'Unknown'"></p>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="space-y-2">
                                        <!-- Title -->
                                        <div class="group p-2 rounded hover:bg-gray-50 transition-colors">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="h-3.5 w-3.5 text-gray-400 group-hover:text-blue-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span
                                                    class="text-xs text-gray-500 group-hover:text-gray-700">Title</span>
                                            </div>
                                            <p class="text-sm font-medium pl-5 mt-0.5"
                                                x-text="selectedDocument?.doc_title"></p>
                                        </div>

                                        <!-- Number -->
                                        <div class="group p-2 rounded hover:bg-gray-50 transition-colors">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="h-3.5 w-3.5 text-gray-400 group-hover:text-blue-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span
                                                    class="text-xs text-gray-500 group-hover:text-gray-700">Number</span>
                                            </div>
                                            <div class="flex items-center pl-5 mt-0.5">
                                                <p class="text-sm font-medium" x-text="selectedDocument?.doc_number">
                                                </p>
                                                <span
                                                    class="ml-1 px-1.5 py-0 text-xs font-medium bg-blue-100 text-blue-800 rounded-full"
                                                    x-show="selectedDocument?.doc_number">ID</span>
                                            </div>
                                        </div>

                                        <!-- Note -->
                                        <div class="group p-2 rounded hover:bg-gray-50 transition-colors">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="h-3.5 w-3.5 text-gray-400 group-hover:text-blue-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <span
                                                    class="text-xs text-gray-500 group-hover:text-gray-700">Note</span>
                                            </div>
                                            <p class="text-sm font-medium pl-5 mt-0.5"
                                                x-text="selectedDocument?.doc_note"></p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Attachments -->
                            <div x-show="selectedDocument?.attachments?.length > 0"
                                class="bg-gray-50 rounded-lg p-2">
                                <h4 class="text-xs font-medium text-gray-500 mb-2 flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <span x-text="`Attachments (${selectedDocument?.attachments?.length})`"></span>
                                </h4>
                                <div class="bg-white rounded border border-gray-200">
                                    <div x-data="{ downloading: false, async handleDownload(attachmentId) { if (this.downloading) return; try { this.downloading = true;
                                                await this.$wire.downloadAttachment(attachmentId); } catch (error) { console.error('Download failed:', error);
                                                this.$dispatch('show-notification', { type: 'error', message: 'Failed to download file. Please try again.' }); } finally { this.downloading = false; } } }">
                                        <div class="grid grid-cols-2 divide-x divide-y divide-gray-100">
                                            <template x-for="attachment in selectedDocument?.attachments"
                                                :key="attachment.id">
                                                <div class="p-1 hover:bg-gray-50">
                                                    <div class="flex justify-between items-center gap-1">
                                                        <div class="flex items-center gap-1 min-w-0">
                                                            <svg class="h-3 w-3 flex-shrink-0 text-gray-400"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            <span class="text-xs truncate"
                                                                x-text="attachment.original_file_name"></span>
                                                        </div>
                                                        <button @click="handleDownload(attachment.id)"
                                                            :disabled="downloading"
                                                            class="text-xs text-indigo-600 hover:bg-indigo-50 px-1 py-0.5 rounded disabled:opacity-50 whitespace-nowrap">
                                                            <template x-if="!downloading">
                                                                <svg class="h-3 w-3 inline" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="downloading">
                                                                <svg class="animate-spin h-3 w-3 inline text-indigo-600"
                                                                    fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                    </path>
                                                                </svg>
                                                            </template>
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="selectedSubDocuments.length > 0"
                                class="text-sm text-gray-500 italic p-2 text-left ">
                                <h4 class="text-base font-semibold text-gray-800">Sub Attachments</h4>
                            </div>
                            <!-- Sub-documents list -->
                            <div x-data="{ downloading: false, async handleDownload(attachmentId) { if (this.downloading) return; try { this.downloading = true;
                                        await this.$wire.downloadAttachment(attachmentId); } catch (error) { console.error('Download failed:', error);
                                        this.$dispatch('show-notification', { type: 'error', message: 'Failed to download file. Please try again.' }); } finally { this.downloading = false; } } }"
                                x-show="selectedSubDocuments && selectedSubDocuments.length > 0" class="mt-2">

                                <div class="grid grid-cols-1">
                                    <template x-for="(subdoc, index) in selectedSubDocuments" :key="index">
                                        <!-- Sub-document card -->
                                        <div class="border rounded overflow-hidden bg-white">
                                            <!-- Header -->
                                            <div class="p-2 bg-gray-50 border-b">
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center gap-1.5 min-w-0">
                                                        <svg class="h-4 w-4 text-blue-500 flex-shrink-0"
                                                            fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                        <div class="truncate">
                                                            <h4 class="font-medium text-sm truncate"
                                                                x-text="subdoc.doc_title || 'Untitled Document'"></h4>
                                                            <p class="text-xs text-gray-500 truncate"
                                                                x-text="subdoc.doc_name || ''"></p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 ml-1.5 flex-shrink-0">
                                                        <!-- Badges -->
                                                        <span
                                                            :class="{
                                                                'bg-green-100 text-green-800': subdoc.doc_validity ===
                                                                    1,
                                                                'bg-yellow-100 text-yellow-800': subdoc.doc_validity ===
                                                                    2,
                                                                'bg-red-100 text-red-800': subdoc.doc_validity === 3
                                                            }"
                                                            class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full"
                                                            x-text="subdoc.doc_validity === 1 ? 'Valid' : subdoc.doc_validity === 2 ? 'Expiring Soon' : 'Expired'"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Info section -->
                                            <div class="px-3 py-2 text-xs space-y-1.5">
                                                <!-- Renewal date -->
                                                <div class="flex items-center gap-2" x-show="subdoc.doc_renewal_dt">
                                                    <svg class="h-3 w-3 text-gray-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <div class="flex items-center">
                                                        <span class="text-gray-600 font-medium">Renewal:</span>
                                                        <span class="text-gray-900 ml-1"
                                                            x-text="subdoc.doc_renewal_dt"></span>
                                                    </div>
                                                </div>
                                                <!-- Note -->
                                                <div class="flex gap-2" x-show="subdoc.doc_note">
                                                    <svg class="h-3 w-3 text-gray-500 mt-0.5 flex-shrink-0"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <div>
                                                        <span class="text-gray-600 font-medium">Note:</span>
                                                        <p class="text-gray-900 mt-0.5" x-text="subdoc.doc_note">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Attachments section -->
                                            <div class="px-3 py-2 border-t border-gray-100 bg-gray-50"
                                                x-show="subdoc.attachments && subdoc.attachments.length > 0">
                                                <div class="flex items-center gap-1 mb-1.5">
                                                    <svg class="h-3.5 w-3.5 text-blue-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                    </svg>
                                                    <span class="text-xs font-medium text-gray-700">Attachments</span>
                                                    <span class="text-xs text-gray-500"
                                                        x-text="'(' + (subdoc.attachments ? subdoc.attachments.length : 0) + ')'"></span>
                                                </div>

                                                <!-- Attachments list -->
                                                <div class="grid grid-cols-1 gap-1.5 pl-5">
                                                    <template x-for="(attachment, idx) in subdoc.attachments"
                                                        :key="idx">
                                                        <div class="flex items-center gap-2 group">
                                                            <svg class="h-3 w-3 text-gray-400 group-hover:text-blue-500"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            <a @click="handleDownload(attachment.id)"
                                                                :disabled="downloading"
                                                                class="text-xs text-blue-600 hover:text-blue-800 hover:underline truncate cursor-pointer"
                                                                x-text="attachment.original_file_name || 'Attachment'"></a>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Additional attachments section -->
                                            <div class="px-3 py-2 border-t border-gray-100 bg-gray-50"
                                                x-show="subdoc.subattachments && subdoc.subattachments.length > 0">
                                                <div class="flex items-center gap-1 mb-1.5">
                                                    <svg class="h-3.5 w-3.5 text-blue-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                    </svg>
                                                    <span class="text-xs font-medium text-gray-700">Additional
                                                        Files</span>
                                                    <span class="text-xs text-gray-500"
                                                        x-text="'(' + (subdoc.subattachments ? subdoc.subattachments.length : 0) + ')'"></span>
                                                </div>

                                                <div class="grid grid-cols-1 gap-1.5 pl-5">
                                                    <template x-for="(subattachment, sidx) in subdoc.subattachments"
                                                        :key="sidx">
                                                        <div class="flex items-center gap-2 group">
                                                            <svg class="h-3 w-3 text-gray-400 group-hover:text-blue-500"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            <a :href="'/attachment/download/' + subattachment.id"
                                                                class="text-xs text-blue-600 hover:text-blue-800 hover:underline truncate"
                                                                x-text="subattachment.file_name || 'Attachment'"></a>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Dates and Validity -->
                        <div class="space-y-6">
                            <div
                                class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200">
                                <!-- Status Section -->
                                <div class="space-y-5">
                                    <!-- Document Validity -->
                                    <div class="group space-y-2">
                                        <!-- Title with Icon -->
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-500 transition-colors shrink-0"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h4 class="text-sm font-medium text-gray-700">Document Validity</h4>
                                        </div>

                                        <!-- Dynamic Status Badge -->
                                        <div class="pl-6">
                                            <span x-show="selectedDocument?.doc_validity"
                                                :class="{
                                                    'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-150': true,
                                                    'bg-green-100 text-green-800 hover:bg-green-200': selectedDocument
                                                        ?.doc_validity === 1,
                                                    'bg-yellow-100 text-yellow-800 hover:bg-yellow-200': selectedDocument
                                                        ?.doc_validity === 2,
                                                    'bg-red-100 text-red-800 hover:bg-red-200': selectedDocument
                                                        ?.doc_validity === 3
                                                }">
                                                <!-- Dynamic status icon -->
                                                <p
                                                    :class="{
                                                        'text-green-600': selectedDocument?.doc_validity === 1,
                                                        'text-yellow-600': selectedDocument?.doc_validity === 2,
                                                        'text-red-600': selectedDocument?.doc_validity === 3
                                                    }">
                                                    <template x-if="selectedDocument?.doc_validity === 1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </template>
                                                    <template x-if="selectedDocument?.doc_validity === 2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </template>
                                                    <template x-if="selectedDocument?.doc_validity === 3">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </template>
                                                </p>
                                                <span x-text="DOC_VALIDITY[selectedDocument?.doc_validity]"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Renewal Date -->
                                    <div class="group border-t border-gray-100 pt-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-500 transition-colors"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <h4 class="text-sm font-medium text-gray-700">Renewal Date</h4>
                                        </div>
                                        <div class="pl-6">
                                            <template x-if="selectedDocument?.doc_renewal_dt">
                                                <div>
                                                    <p class="text-sm text-gray-900 font-medium"
                                                        x-text="formatDate(selectedDocument?.doc_renewal_dt)"></p>
                                                    <!-- Dynamic Renewal Status -->
                                                    <span x-show="selectedDocument?.doc_renewal_dt"
                                                        x-data="{
                                                            getDaysUntilRenewal() {
                                                                const renewalDate = new Date(selectedDocument?.doc_renewal_dt);
                                                                const today = new Date();
                                                                const diffTime = renewalDate - today;
                                                                return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                                            }
                                                        }"
                                                        :class="{
                                                            'text-xs mt-1 inline-flex items-center gap-1': true,
                                                            'text-red-600': getDaysUntilRenewal() <= 30,
                                                            'text-yellow-600': getDaysUntilRenewal() > 30 &&
                                                                getDaysUntilRenewal() <= 90,
                                                            'text-green-600': getDaysUntilRenewal() > 90
                                                        }">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span
                                                            x-text="`${getDaysUntilRenewal()} days until renewal`"></span>
                                                    </span>
                                                </div>
                                            </template>

                                            <template x-if="!selectedDocument?.doc_renewal_dt">
                                                <div class="flex items-center gap-1.5 text-sm text-gray-500">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>No renewal date available</span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <!-- Doc Year -->
                                    <div class="group border-t border-gray-100 pt-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-500 transition-colors"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <h4 class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                                                Document Year</h4>
                                        </div>
                                        <div class="pl-6">
                                            <!-- When document year is available -->
                                            <template x-if="selectedDocument?.doc_year">
                                                <div>
                                                    <p class="text-sm text-gray-900 font-medium"
                                                        x-text="selectedDocument?.doc_year"></p>
                                                </div>
                                            </template>

                                            <!-- When document year is not available -->
                                            <template x-if="!selectedDocument?.doc_year">
                                                <div class="flex items-center gap-1.5 text-sm text-gray-500">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>Year not specified</span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <!-- Last Updated -->
                                    <div class="group border-t border-gray-100 pt-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-500 transition-colors"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h4 class="text-sm font-medium text-gray-700">Document Issue Date</h4>
                                        </div>
                                        <div class="pl-6">
                                            <p class="text-sm text-gray-900 font-medium"
                                                x-text="formatDate(selectedDocument?.doc_update_date)"></p>
                                            <!-- Dynamic Last Update Status -->
                                            <span x-show="selectedDocument?.doc_update_date"
                                                x-data="{
                                                    getUpdateStatus() {
                                                        const updateDate = new Date(selectedDocument?.doc_update_date);
                                                        const today = new Date();
                                                        const diffDays = Math.ceil((today - updateDate) / (1000 * 60 * 60 * 24));
                                                        return `Last issued ${diffDays} days ago`;
                                                    }
                                                }"
                                                class="text-xs text-gray-500 mt-1 inline-block" 
                                                x-text="getUpdateStatus()">
                                            </span>
                                        </div>
                                    </div>

                                    <div class="group border-t border-gray-100 pt-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-500 transition-colors"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h4 class="text-sm font-medium text-gray-700">Document Expire Date</h4>
                                        </div>
                                        <div class="pl-6">
                                            <p class="text-sm text-gray-900 font-medium"
                                                x-text="formatDateIndian(selectedDocument?.doc_expire_date)"></p>
                                            
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Footer Information -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:justify-between text-xs text-gray-500 space-y-2 sm:space-y-0">
                            <div class="flex items-center">
                                <span>Created:</span>
                                <span class="font-medium ml-1"
                                    x-text="selectedDocument?.created_at && !selectedDocument.created_at.includes('T') ? selectedDocument.created_at : formatReadableDate(selectedDocument?.created_at, true)"></span>
                                <span class="mx-1">•</span>
                                <span
                                    x-text="selectedDocument?.created_at ? extractTimeFromIso(selectedDocument.created_at) : ''"></span>
                            </div>
                            
                            <div class="flex items-center">
                                <span>Updated:</span>
                                <span class="font-medium ml-1"
                                    x-text="selectedDocument?.updated_at && !selectedDocument.updated_at.includes('T') ? selectedDocument.updated_at : formatReadableDate(selectedDocument?.updated_at, true)"></span>
                                <span class="mx-1">•</span>
                                <span
                                    x-text="selectedDocument?.updated_at ? extractTimeFromIso(selectedDocument.updated_at) : ''"></span>
                            </div>
                        </div>
                    </div>
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
</div>
@include('livewire.Document.document-email')
@include('livewire.Document.document-email-history')

<script>
    // Helper functions for your Alpine.js component
    function formatReadableDate(dateString) {
        if (!dateString) return '';

        // If it's already in a readable format, return as is
        if (typeof dateString === 'string' && !dateString.includes('T')) {
            return dateString;
        }

        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return dateString;

            // Format as "Wednesday, March 26, 2025"
            return date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        } catch (error) {
            return dateString || '';
        }
    }

    function extractTimeFromIso(dateString) {
        if (!dateString) return '';

        // If it's already just a time, return as is
        if (dateString.includes(':') && !dateString.includes('-')) {
            return dateString;
        }

        // For ISO format 2025-03-25T19:20:00.000000Z
        if (dateString.includes('T')) {
            try {
                // Extract time portion and format it
                const timePart = dateString.split('T')[1].substring(0, 5);

                // Convert 24-hour time to 12-hour format with AM/PM
                const hourNum = parseInt(timePart.split(':')[0], 10);
                const minutes = timePart.split(':')[1];
                const ampm = hourNum >= 12 ? 'PM' : 'AM';
                const hour12 = hourNum % 12 || 12;

                return `${hour12}:${minutes} ${ampm}`;
            } catch (error) {
                return '';
            }
        }

        return '';
    }
</script>
</div>
