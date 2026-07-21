<div class="py-0" >
    <div class="max-w-8xl mx-auto sm:px-2 lg:px-2" >
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-1 gap-4">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">My Documents Explorer</h2>
                <div class="flex flex-wrap gap-2">
                    <!-- Download Sample CSV Button -->
                    <a href="{{ route("my-documents") }}"
                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mr-3 size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"></path>
                        </svg>
                        Back To Main List
                    </a>
                </div>
            </div>
            <!-- Breadcrumbs -->
            <nav class="mb-5" aria-label="Breadcrumb">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                </div>
                
                <ol class="flex flex-wrap items-center px-4 py-2 bg-white rounded-lg shadow-sm">
                    <li class="flex items-center">
                        <button wire:click="navigateToFolder('/{{ $myFirstPath }}')" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-150">
                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            <span>Home</span>
                        </button>
                    </li>
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            
                            @if(!$breadcrumb['active'] )
                                <button
                                    wire:click="navigateToFolder('{{ $breadcrumb['path'] }}')"
                                    class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-150 font-medium"
                                    wire:loading.class="opacity-50 cursor-wait"
                                    wire:target="navigateToFolder('{{ $breadcrumb['path'] }}')">
                                    {{ $breadcrumb['name'] }}
                                </button>
                            @else
                                <span class="text-gray-700 font-semibold">{{ $breadcrumb['name'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>

            <div x-data="{ expanded: false }" wire:init="masterFolderSize" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 overflow-hidden">
                <div @click="expanded = !expanded"  wire:loading.remove wire:target="masterFolderSize"
                    class="p-4 cursor-pointer hover:bg-gray-50 transition-colors duration-150 flex items-center justify-between">
                    <div class="flex items-center">
                        <h3 class="text-sm font-semibold text-gray-700">Storage Usage</h3>
                        <span class="text-xs text-gray-500 ml-2">({{ $folderSize }} of {{ ENV('DEFAULT_DRIVE_SIZE') }} GB)</span>
                    </div>
                    <button class="text-gray-500 focus:outline-none">
                        <svg x-show="!expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <svg x-show="expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>
                </div>
                <!-- Basic Spinner - Wire Loading State -->
                <div wire:loading wire:target="masterFolderSize" class="w-full p-4 bg-gray-50 flex items-center justify-center">
                    <div class="flex items-center">
                        <svg class="animate-spin h-5 w-5 text-blue-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm text-gray-600 font-medium">Calculating storage usage...</span>
                    </div>
                </div>

                <!-- Expandable Content -->
                <div x-show="expanded" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="px-4 pb-4">
                    
                    @php
                        // Convert folder size to bytes if it's a formatted string
                        if (is_string($folderSize)) {
                            $sizeValue = (float) $folderSize;
                            $sizeUnit = preg_replace('/[^A-Za-z]/', '', $folderSize);
                            
                            switch ($sizeUnit) {
                                case 'KB': $sizeInBytes = $sizeValue * 1024; break;
                                case 'MB': $sizeInBytes = $sizeValue * 1024 * 1024; break;
                                case 'GB': $sizeInBytes = $sizeValue * 1024 * 1024 * 1024; break;
                                default: $sizeInBytes = $sizeValue; // Bytes
                            }
                        } else {
                            $sizeInBytes = $folderSize;
                        }
                        
                        $totalSpace = 2 * 1024 * 1024 * 1024; // 2GB in bytes
                        $usedPercentage = min(($sizeInBytes / $totalSpace) * 100, 100); // Cap at 100%
                        $remainingSpace = max($totalSpace - $sizeInBytes, 0);
                        $remainingFormatted = formatBytes($remainingSpace);
                        
                        // Helper function to format bytes
                        function formatBytes($bytes, $precision = 2) {
                            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                            $bytes = max($bytes, 0);
                            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                            $pow = min($pow, count($units) - 1);
                            $bytes /= pow(1024, $pow);
                            return round($bytes, $precision) . ' ' . $units[$pow];
                        }
                    @endphp
                    
                    <!-- Usage Bar -->
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                        <div class="h-2.5 rounded-full transition-all duration-500 ease-in-out" 
                            style="width: {{ $usedPercentage }}%"
                            :class="{
                                'bg-green-500': {{ $usedPercentage }} < 70,
                                'bg-yellow-500': {{ $usedPercentage }} >= 70 && {{ $usedPercentage }} < 90,
                                'bg-red-500': {{ $usedPercentage }} >= 90
                            }">
                        </div>
                    </div>
                    
                    <!-- Storage Details -->
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">{{ $remainingFormatted }} available</span>
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 rounded-full mr-1 
                                        {{ $usedPercentage < 70 ? 'bg-green-500' : ($usedPercentage < 90 ? 'bg-yellow-500' : 'bg-red-500') }}">
                            </span>
                            <span class="{{ $usedPercentage < 70 ? 'text-green-600' : ($usedPercentage < 90 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ number_format($usedPercentage, 1) }}% used
                            </span>
                        </div>
                    </div>
                        <div class="mb-2 flex flex-wrap gap-3 items-center"> </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-700">Folder Statistics</h3>
                                <button wire:click="refreshFolderStats" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Show loader while calculating stats -->
                            <div wire:loading wire:target="refreshFolderStats, countFolderContents" class="flex justify-center py-4">
                                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                            </div>
                            
                            <!-- Show stats when loaded -->
                            <div wire:loading.remove wire:target="refreshFolderStats, countFolderContents" class="grid grid-cols-4 gap-3">
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <div class="text-xs text-blue-500 font-medium">Files</div>
                                    <div class="text-lg font-semibold text-blue-700">{{ $folderStats['file_count'] ?? 0 }}</div>
                                </div>
                                
                                <div class="p-3 bg-green-50 rounded-lg">
                                    <div class="text-xs text-green-500 font-medium">Folders</div>
                                    <div class="text-lg font-semibold text-green-700">{{ $folderStats['folder_count'] ?? 0 }}</div>
                                </div>
                                
                                <div class="p-3 bg-purple-50 rounded-lg">
                                    <div class="text-xs text-purple-500 font-medium">Total Size</div>
                                    <div class="text-lg font-semibold text-purple-700">{{ $folderStats['formatted_size'] ?? '0 B' }}</div>
                                </div>
                                
                                <div class="p-3 bg-amber-50 rounded-lg">
                                    <div class="text-xs text-amber-500 font-medium">Space Left</div>
                                    <div class="text-lg font-semibold text-amber-700">{{ $spaceLeft ?? env('DEFAULT_DRIVE_SIZE').'GB'}} </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add these elements above your table -->
                        <div class="mb-4 mt-3 space-y-4">
                            <!-- Navigation Controls Row -->
                            <!-- Action Cards Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Create Folder Card -->
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="bg-blue-50 px-4 py-2 border-b border-gray-200">
                                        <h3 class="text-sm font-semibold text-blue-700 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                            </svg>
                                            Create New Folder
                                        </h3>
                                    </div>
                                    <div class="p-4">
                                        <form wire:submit.prevent="createFolder" class="flex flex-col sm:flex-row gap-3">
                                            <div class="flex-grow">
                                                <input type="text" wire:model.defer="newFolderName" placeholder="Enter folder name"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                @error('newFolderName') <span class="mt-1 text-xs text-red-500 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="flex items-center">
                                                <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm transition-colors duration-150 font-medium flex items-center"
                                                        wire:loading.class="opacity-50 cursor-not-allowed" 
                                                        wire:target="createFolder">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    <span wire:loading.remove wire:target="createFolder">Create</span>
                                                    <span wire:loading wire:target="createFolder">Creating...</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- File Upload Card -->
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" x-data="{ uploading: false, progress: 0 }"
                                    x-on:livewire-upload-start="uploading = true" 
                                    x-on:livewire-upload-finish="uploading = false"
                                    x-on:livewire-upload-cancel="uploading = false" 
                                    x-on:livewire-upload-error="uploading = false"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                                    <div class="bg-green-50 px-4 py-2 border-b border-gray-200">
                                        <h3 class="text-sm font-semibold text-green-700 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Upload Files 
                                        </h3>
                                    </div>
                                    <div class="p-4">
                                        <form wire:submit.prevent="uploadFile" class="flex flex-col sm:flex-row gap-3">
                                            <div class="flex-grow">
                                                <label class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                                    <div class="flex items-center justify-center text-gray-500">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                        </svg>
                                                        <span x-show="!uploading">Choose files</span>
                                                        <span x-show="uploading">Selected</span>
                                                    </div>
                                                    <input type="file" multiple wire:model="uploadedFile" class="hidden">
                                                </label>
                                                @error('uploadedFile') <span class="mt-1 text-xs text-red-500 block">{{ $message }}</span> @enderror
                                                @error('uploadedFile.*') <span class="mt-1 text-xs text-red-500 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="flex items-center">
                                                <button type="submit"
                                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm transition-colors duration-150 font-medium flex items-center"
                                                        wire:loading.class="opacity-50 cursor-not-allowed" 
                                                        wire:target="uploadFile">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                    <span wire:loading.remove wire:target="uploadFile">Upload</span>
                                                    <span wire:loading wire:target="uploadFile">Uploading...</span>
                                                </button>
                                            </div>
                                        </form>

                                        <!-- Upload Progress Bar -->
                                        <div x-show="uploading || {{ $isUploading ? 'true' : 'false' }}" class="mt-3">
                                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="bg-green-600 h-2 rounded-full transition-all duration-300"  
                                                    x-bind:style="{ width: ({{ $uploadProgress ?? 0 }} > 0 ? {{ $uploadProgress }} : progress) + '%' }"></div>
                                            </div>
                                            <p class="text-xs text-gray-600 mt-1">
                                                <span x-text="({{ $uploadProgress ?? 0 }} > 0 ? {{ $uploadProgress }} : progress) + '%'"></span> complete
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Loading Indicator -->
                            @if($loading)
                                <div class="flex justify-center my-8">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                                </div>
                            @else
                                <div>
                                    <div class="bg-white rounded-lg shadow p-4 mb-6">
                                        <!-- Search Input -->
                                        <div class="mb-2">
                                            <label for="searchQuery" class="block text-sm font-medium text-gray-700 mb-1">
                                                Search File Content in Folders
                                            </label>
                                            <div class="flex">
                                                <input type="text" id="searchQuery" wire:model.defer="searchQuery"
                                                    wire:keydown.enter="search" placeholder="Enter text to search within files..."
                                                    class="border rounded-l px-4 py-2 w-full focus:ring-blue-500 focus:border-blue-500">

                                                <button wire:click="search"
                                                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 cursor-pointer">
                                                    <span wire:loading.remove wire:target="search">Search</span>
                                                    <span wire:loading wire:target="search">
                                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                                stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                            </path>
                                                        </svg>
                                                        Searching...
                                                    </span>
                                                </button>
                                                @if(count($searchResults) > 0)
                                                    <button wire:click="clearSearch"
                                                        class="ml-2  px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                                                        Clear
                                                    </button>
                                                @endif
                                            </div>

                                            @error('searchQuery')
                                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>


                                    </div>

                                    <!-- Error Messages -->
                                    @error('search')
                                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <!-- Search Results -->
                                    @if(count($searchResults) > 0)
                                        <div class="bg-white rounded-lg shadow overflow-hidden">
                                            <div class="bg-gray-50 px-4 py-3 border-b">
                                                <h3 class="font-medium">Found {{ count($searchResults) }} results for "{{ $searchQuery }}"</h3>
                                                <p class="text-xs text-gray-500 mt-1">Use ↑↓ arrow keys to navigate, Enter to select, Tab to focus buttons</p>
                                            </div>
                                            <div x-data="{ 
                                                focusedIndex: -1,
                                                totalItems: {{ count($searchResults) }},
                                                
                                                init() {
                                                    // Focus the container on mount
                                                    this.$nextTick(() => {
                                                        this.$refs.searchContainer.focus();
                                                    });
                                                },
                                                
                                                handleKeydown(event) {
                                                    switch(event.key) {
                                                        case 'ArrowDown':
                                                            event.preventDefault();
                                                            this.focusedIndex = (this.focusedIndex + 1) % this.totalItems;
                                                            this.scrollToFocused();
                                                            break;
                                                        case 'ArrowUp':
                                                            event.preventDefault();
                                                            this.focusedIndex = this.focusedIndex <= 0 ? this.totalItems - 1 : this.focusedIndex - 1;
                                                            this.scrollToFocused();
                                                            break;
                                                        case 'Enter':
                                                            event.preventDefault();
                                                            if (this.focusedIndex >= 0) {
                                                                this.selectItem();
                                                            }
                                                            break;
                                                        case 'Escape':
                                                            event.preventDefault();
                                                            this.focusedIndex = -1;
                                                            break;
                                                    }
                                                },
                                                
                                                selectItem() {
                                                    const focusedElement = this.$refs['item-' + this.focusedIndex];
                                                    if (focusedElement) {
                                                        const button = focusedElement.querySelector('button');
                                                        if (button) {
                                                            button.click();
                                                        }
                                                    }
                                                },
                                                
                                                scrollToFocused() {
                                                    if (this.focusedIndex >= 0) {
                                                        const focusedElement = this.$refs['item-' + this.focusedIndex];
                                                        if (focusedElement) {
                                                            focusedElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                                                        }
                                                    }
                                                },
                                                
                                                setFocused(index) {
                                                    this.focusedIndex = index;
                                                },
                                                
                                                moveToBottom: function(el) {
                                                    const li = el.closest('li');
                                                    const parent = li.parentNode;
                                                    parent.removeChild(li);
                                                    parent.appendChild(li);
                                                    
                                                    // Add a highlight effect
                                                    li.classList.add('bg-blue-50');
                                                    setTimeout(() => {
                                                        li.classList.remove('bg-blue-50');
                                                        li.classList.add('bg-white');
                                                    }, 1500);
                                                }
                                            }" 
                                            x-ref="searchContainer"
                                            tabindex="0"
                                            @keydown="handleKeydown($event)"
                                            class="focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset">
                                                <ul class="divide-y divide-gray-200 text-sm bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                                                    @foreach($searchResults as $index => $result)
                                                        <li x-ref="item-{{ $index }}"
                                                            :class="focusedIndex === {{ $index }} ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-50'"
                                                            @mouseenter="setFocused({{ $index }})"
                                                            @mouseleave="focusedIndex = -1"
                                                            class="transition-all duration-200 cursor-pointer">
                                                            <div class="flex items-center gap-3 py-2.5 px-3">
                                                                <!-- Icon with colored background based on type -->
                                                                <div class="px-2 py-1 whitespace-nowrap text-xs">
                                                                    @if($result['metadata']['type'] === 'folder')
                                                                        <a href="#" wire:click.prevent="navigateToFolder('{{ $result['metadata']['path_display'] }}')"
                                                                            class="text-blue-600 hover:underline flex items-center"
                                                                            tabindex="-1">
                                                                            <svg class="w-6 h-6 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z">
                                                                                </path>
                                                                            </svg>
                                                                            <span wire:loading wire:target="navigateToFolder('{{ $result['metadata']['path_display'] }}')">
                                                                                <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-blue-500"
                                                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                                                        stroke-width="4"></circle>
                                                                                    <path class="opacity-75" fill="currentColor"
                                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                                    </path>
                                                                                </svg>
                                                                                Opening...
                                                                            </span>
                                                                        </a>
                                                                    @else
                                                                        <div class="flex items-center">
                                                                            <?php 
                                                                                $extension = pathinfo($result['metadata']['name'] ?? '', PATHINFO_EXTENSION);
                                                                                $iconHtml = $this->getFileTypeIcon($result['metadata']);
                                                                            ?>
                                                                            {!! $iconHtml !!}
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <!-- File/Folder Name with hover effect -->
                                                                <div class="font-medium flex-grow group">
                                                                    <span class="group-hover:text-blue-600 transition-colors duration-150">{{ $result['metadata']['name'] }}</span>
                                                                    <div class="text-xs text-gray-500 mt-0.5">
                                                                        {{ str_replace($actualPath, "", $result['metadata']['path_display']) }}
                                                                    </div>
                                                                </div>

                                                                <!-- Size with attractive styling -->
                                                                <div class="text-xs bg-gray-100 rounded-full px-2 py-1 text-gray-700 flex-shrink-0 min-w-[60px] text-center">
                                                                    @if($result['metadata']['type'] === 'file' && isset($result['metadata']['size']))
                                                                        {{ round($result['metadata']['size'] / 1024, 1) }}KB
                                                                    @elseif($result['metadata']['type'] === 'folder')
                                                                        <span class="text-blue-600 font-medium">Folder</span>
                                                                    @endif
                                                                </div>

                                                                <!-- Action Button with animation -->
                                                                <div class="flex-shrink-0 flex space-x-2">
                                                                    @if($result['metadata']['type'] === 'folder')
                                                                        <button 
                                                                            wire:click="navigateToFolder('{{ $result['metadata']['path_display'] }}')" 
                                                                            x-data
                                                                            @click="document.getElementById('myActualLoad').scrollIntoView({ behavior: 'smooth' })"
                                                                            :class="focusedIndex === {{ $index }} ? 'ring-2 ring-blue-400' : ''"
                                                                            class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 cursor-pointer focus:outline-none">
                                                                            <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                                                                            </svg>
                                                                            Open
                                                                        </button>
                                                                        <button
                                                                            wire:click="downloadFolderAsZip('{{ $result['metadata']['path_display'] }}')"
                                                                            wire:loading.class="opacity-75"
                                                                            wire:target="downloadFolderAsZip('{{ $result['metadata']['path_display'] }}')"
                                                                            :class="focusedIndex === {{ $index }} ? 'ring-2 ring-blue-400' : ''"
                                                                            class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 cursor-pointer focus:outline-none">
                                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                                                </path>
                                                                            </svg>
                                                                            <span wire:loading.remove wire:target="downloadFolderAsZip('{{ $result['metadata']['path_display'] }}')">ZIP</span>
                                                                            <span wire:loading wire:target="downloadFolderAsZip('{{ $result['metadata']['path_display'] }}')">
                                                                                <svg class="animate-spin ml-1 h-3 w-3 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                                    <path class="opacity-75" fill="currentColor"
                                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                                    </path>
                                                                                </svg>
                                                                            </span>
                                                                        </button>
                                                                    @else
                                                                        <button 
                                                                            wire:click="downloadSearchFile('{{ $result['metadata']['path_display'] }}')" 
                                                                            :class="focusedIndex === {{ $index }} ? 'ring-2 ring-blue-400' : ''"
                                                                            class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 cursor-pointer focus:outline-none">
                                                                            <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                            </svg>
                                                                            Download
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @elseif($searchQuery && !$isLoading)
                                        <div class="bg-gray-50 text-center py-8 rounded-lg border">
                                            <p class="text-gray-500">No files containing "{{ $searchQuery }}" were found.</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                <!-- Flash Messages -->
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <!-- Success/Error Messages -->
            @if($successMessage)
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-2 mb-4 text-sm rounded">
                    <div class="flex">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $successMessage }}</span>
                    </div>
                </div>
            @endif
            @if($errorMessage)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-2 mb-4 text-sm rounded">
                    <div class="flex">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $errorMessage }}</span>
                    </div>
                </div>
            @endif
            <!-- Files and Folders List -->
            <div class="border rounded-lg overflow-hidden" id="myActualLoad" wire:init="loadContents">
                
                <!-- File Upload Drop Zone -->
                <div x-data="fileUploadHandler()" 
                    x-on:dragover.prevent="handleDragOver" 
                    x-on:dragleave.prevent="handleDragLeave"
                    x-on:drop.prevent="handleDrop"
                    :class="isDragOver ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
                    class="border-2 border-dashed rounded-lg p-4 m-4 transition-all duration-200"
                    wire:loading.class="opacity-50">
                    
                    <!-- Upload Area -->
                    <div class="text-center">
                        <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        
                        <div class="mt-2">
                            <label for="file-upload" class="cursor-pointer">
                                <span class="text-sm font-medium text-gray-900">
                                    Drop files here or 
                                    <span class="text-blue-600 hover:text-blue-500">browse</span>
                                </span>
                                <input id="file-upload" 
                                    type="file" 
                                    multiple 
                                    class="sr-only"
                                    wire:model="uploadedFile"
                                    x-ref="fileInput">
                            </label>
                            <!-- Hidden input for folder drops -->
                            <input type="file" 
                                multiple 
                                class="sr-only"
                                wire:model="uploadedFile"
                                x-ref="folderFileInput">
                            <p class="mt-1 text-xs text-gray-500">
                                Upload to:  <b>{{ $path ?: 'Root folder' }}</b>
                            </p>
                        </div>
                    </div>
                
                    <!-- Show files ready for upload -->
                    @if(!empty($uploadedFile) && !($isUploading ?? false))
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <h4 class="text-sm font-medium text-gray-900">Ready to Upload</h4>
                                <button wire:click="cancelUpload" 
                                        class="text-red-600 hover:text-red-800 text-sm">
                                    Clear
                                </button>
                            </div>
                            
                            @foreach($uploadedFile as $file)
                                <div class="bg-white p-2 rounded border">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-700 truncate">
                                            {{ $file->getClientOriginalName() }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ number_format($file->getSize() / 1024, 1) }} KB
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="flex space-x-2 mt-3">
                            <button wire:click="uploadFiles" 
                                        wire:loading.attr="disabled"
                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-50">
                                    <span wire:loading.remove wire:target="uploadFiles">Upload Files</span>
                                    <span wire:loading wire:target="uploadFiles">Uploading...</span>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                </div>
                <div x-data="navigationHandler()" 
                    x-ref="tableContainer"
                    x-init="$nextTick(() => $el.focus())"
                    tabindex="0"
                    @keydown="handleKeydown($event)"
                    @click="$el.focus()"
                    @focusout="handleFocusOut($event)"
                    class="focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset">
                    
                    <!-- Keyboard shortcuts hint -->
                    @if(count($contents) > 0)
                        <div class="bg-blue-50 px-4 py-2 border-b text-xs text-blue-700" >
                            <span class="font-medium">Keyboard shortcuts:</span>
                            ↑↓ Navigate • Enter Open/View • N New Folder • Esc Clear focus
                        </div>
                    @endif
                    
                    <table class="min-w-full divide-y divide-gray-200" x-cloak>
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Size</th>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Modified</th>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($creatingFolder ?? false)
                                <tr class="bg-yellow-50 border-l-4 border-yellow-400">
                                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                                        <div class="flex items-center" @click.stop>
                                            <svg class="w-6 h-6 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                            </svg>
                                            <input type="text" 
                                                wire:model.defer="newFolderName" 
                                                placeholder="Enter folder name..."
                                                class="border border-gray-300 px-1 py-0.5 text-xs rounded w-40 bg-white" 
                                                wire:keydown.enter="createFolder" 
                                                wire:keydown.escape="cancelCreateFolder"
                                                wire:loading.attr="disabled" wire:target="createFolder"
                                                x-data="createFolderInput()" 
                                                x-init="focusInput()"
                                                @keydown.stop
                                                @click.stop>
                                            
                                            <!-- Save button -->
                                            <button wire:click="createFolder" class="ml-1 p-1 bg-green-50 text-green-600 rounded hover:bg-green-100" 
                                                wire:loading.class="opacity-50 cursor-wait" wire:target="createFolder"
                                                @click.stop>
                                                <svg wire:loading.remove wire:target="createFolder" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <svg wire:loading wire:target="createFolder" class="animate-spin w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </button>
                                            
                                            <!-- Cancel button -->
                                            <button wire:click="cancelCreateFolder" class="ml-1 p-1 bg-red-50 text-red-600 rounded hover:bg-red-100"
                                                wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled" wire:target="createFolder"
                                                @click.stop>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs text-gray-500">New Folder</td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs text-gray-500">-</td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs text-gray-500">-</td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs text-gray-500">-</td>
                                </tr>
                            @endif
                            @forelse($contents as $index => $item)
                                <tr x-ref="row-{{ $index }}"
                                    @if($item['.tag'] === 'folder')
                                        x-data="folderDropHandler('{{ $item['path_display'] }}')"
                                        x-on:dragover.prevent="handleDragOver" 
                                        x-on:dragleave.prevent="handleDragLeave"
                                        x-on:drop.prevent="handleFolderDrop"
                                        :class="[
                                            focusedIndex === {{ $index }} ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-50'
                                        ]"
                                    @else
                                        :class="focusedIndex === {{ $index }} ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-50'"
                                    @endif
                                    @mouseenter="setFocused({{ $index }})"
                                    @mouseleave="focusedIndex = -1"
                                    class="h-8 transition-all duration-200 cursor-pointer">
                                    <td class="px-2 py-1 whitespace-nowrap text-xs relative" wire:key="names-{{ time() }}">
                                        @if($item['.tag'] === 'folder')
                                            <div class="flex items-center justify-between w-full group">
                                                <a href="javascript:void(0);" wire:click.prevent="navigateToFolder('{{ $item['path_display'] }}')"
                                                    class="text-blue-600 hover:underline flex items-center flex-grow"
                                                    tabindex="-1"
                                                    data-item-type="folder">
                                                    <svg class="w-6 h-6 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                                    </svg>
                                                    
                                                    @if($editingItem === $item['path_display'])
                                                        <div class="flex items-center" @click.stop>
                                                            <input type="text" 
                                                                wire:model.defer="newItemName" 
                                                                class="border border-gray-300 px-1 py-0.5 text-xs rounded w-40" 
                                                                wire:keydown.enter="renameItem" 
                                                                wire:keydown.escape="cancelRename"
                                                                wire:loading.attr="disabled" wire:target="renameItem"
                                                                x-data="renameInput()" 
                                                                x-init="focusAndSelect()"
                                                                @keydown.stop
                                                                @click.stop>
                                                            
                                                            <!-- Save button with loading state -->
                                                            <button wire:click="renameItem" class="ml-1 p-1 bg-green-50 text-green-600 rounded hover:bg-green-100" 
                                                                wire:loading.class="opacity-50 cursor-wait" wire:target="renameItem"
                                                                @click.stop>
                                                                <!-- Save icon (displays when not loading) -->
                                                                <svg wire:loading.remove wire:target="renameItem" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                <!-- Loading spinner (displays when loading) -->
                                                                <svg wire:loading wire:target="renameItem" class="animate-spin w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                            </button>
                                                            <!-- Cancel button -->
                                                            <button wire:click="cancelRename" class="ml-1 p-1 bg-red-50 text-red-600 rounded hover:bg-red-100"
                                                                wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled" wire:target="renameItem"
                                                                @click.stop>
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <span wire:loading.remove wire:target="navigateToFolder('{{ $item['path_display'] }}')">
                                                            {{ basename($item['path_display'] ?? $item['name']) }}
                                                        </span>
                                                        <span wire:loading wire:target="navigateToFolder('{{ $item['path_display'] }}')">
                                                            <div class="flex items-center">
                                                                <svg class="animate-spin h-3 w-3 mr-1 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                                <span>Opening...</span>
                                                            </div>
                                                        </span>
                                                    @endif
                                                </a>
                                                
                                                @if($editingItem !== $item['path_display'])
                                                    <div class="flex space-x-1">
                                                        <!-- Rename button -->
                                                        <button wire:click="startRename('{{ $item['path_display'] }}')" wire:loading.remove
                                                            class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-blue-700 transition-opacity" 
                                                            title="Rename"
                                                            tabindex="-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Delete button -->
                                                        <button wire:confirm="Are you sure you want to completely delete  {{ basename($item['path_display'] ?? $item['name']) }} from the system? This action is permanent and cannot be undone in the future." wire:click="deleteItem('{{ $item['path_display'] }}')" wire:loading.remove
                                                            wire:target="deleteItem, confirmDelete"
                                                            class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-red-600 transition-opacity" 
                                                            title="Delete"
                                                            tabindex="-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Delete loading indicator -->
                                                        <span wire:loading wire:target="deleteItem('{{ $item['path_display'] }}')">
                                                            <svg class="animate-spin w-4 h-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex items-center justify-between w-full group">
                                                <div class="flex items-center flex-grow">
                                                    <?php
                                                        $extension = pathinfo($item['name'] ?? '', PATHINFO_EXTENSION);
                                                        $iconHtml = $this->getFileTypeIcon($item);
                                                    ?>
                                                    {!! $iconHtml !!}
                                                    
                                                    @if($editingItem === $item['path_display'])
                                                        <div class="flex items-center ml-1" @click.stop>
                                                            <input type="text" 
                                                                wire:model.defer="newItemName" 
                                                                class="border border-gray-300 px-1 py-0.5 text-xs rounded w-40" 
                                                                wire:keydown.enter="renameItem" 
                                                                wire:keydown.escape="cancelRename"
                                                                wire:loading.attr="disabled" wire:target="renameItem"
                                                                x-data="renameInput()" 
                                                                x-init="focusAndSelect()"
                                                                @keydown.stop
                                                                @click.stop>
                                                            
                                                            <!-- Save button with loading state -->
                                                            <button wire:click="renameItem" class="ml-1 p-1 bg-green-50 text-green-600 rounded hover:bg-green-100" 
                                                                wire:loading.class="opacity-50 cursor-wait" wire:target="renameItem"
                                                                @click.stop>
                                                                <!-- Save icon (displays when not loading) -->
                                                                <svg wire:loading.remove wire:target="renameItem" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                <!-- Loading spinner (displays when loading) -->
                                                                <svg wire:loading wire:target="renameItem" class="animate-spin w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                            </button>
                                                            <!-- Cancel button -->
                                                            <button wire:click="cancelRename" class="ml-1 p-1 bg-red-50 text-red-600 rounded hover:bg-red-100"
                                                                wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled" wire:target="renameItem"
                                                                @click.stop>
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <a href="javascript:void(0);"  
                                                        wire:click.prevent="viewFile('{{ $item['path_display'] }}', '{{ basename($item['path_display'] ?? $item['name']) }}')"
                                                        class="text-blue-600 hover:underline text-xs ml-1 truncate max-w-xs"
                                                        tabindex="-1"
                                                        data-item-type="file">
                                                            {{ basename($item['path_display'] ?? $item['name']) }}
                                                        </a>
                                                    @endif
                                                </div>
                                                
                                                @if($editingItem !== $item['path_display'])
                                                    <div class="flex space-x-1">
                                                        <!-- Rename button -->
                                                        <button wire:click="startRename('{{ $item['path_display'] }}')" wire:loading.remove
                                                            class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-blue-700 transition-opacity" 
                                                            title="Rename"
                                                            tabindex="-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Delete button -->
                                                        <button wire:confirm="Are you sure you want to completely delete  {{ basename($item['path_display'] ?? $item['name']) }} from the system? This action is permanent and cannot be undone in the future." wire:click="deleteItem('{{ $item['path_display'] }}')" wire:loading.remove
                                                            wire:target="deleteItem, confirmDelete"
                                                            class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-red-600 transition-opacity" 
                                                            title="Delete"
                                                            tabindex="-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Delete loading indicator -->
                                                        <span wire:loading wire:target="deleteItem('{{ $item['path_display'] }}')">
                                                            <svg class="animate-spin w-4 h-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                                        {{ $item['.tag'] === 'folder' ? 'Folder' : 'File' }}
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                                        @if($item['.tag'] === 'file')
                                            {{ round($item['size'] / 1024, 1) }} KB
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                                        @if($item['.tag'] === 'file')
                                            {{ \Carbon\Carbon::parse($item['server_modified'])->format('Y-m-d H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs" wire:key="buttons-{{ time() }}">
                                        <div class="flex space-x-1 items-center">
                                            <!-- View button (files only) -->
                                            @if($item['.tag'] === 'file')
                                                <button
                                                    wire:click="viewFile('{{ $item['path_display'] }}', '{{ basename($item['path_display'] ?? $item['name']) }}')"
                                                    wire:loading.class="opacity-75"
                                                    wire:target="viewFile('{{ $item['path_display'] }}', '{{ basename($item['path_display'] ?? $item['name']) }}')"
                                                    :class="focusedIndex === {{ $index }} ? 'ring-2 ring-blue-400' : ''"
                                                    class="p-1 text-white hover:bg-secondary/80 bg-primary dark:bg-secondary rounded-l flex items-center transition-colors focus:outline-none"
                                                    title="View file"
                                                >
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <svg wire:loading wire:target="viewFile('{{ $item['path_display'] }}', '{{ basename($item['path_display'] ?? $item['name']) }}')" 
                                                        class="animate-spin ml-1 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- Download button (files) or ZIP button (folders) -->
                                            <button
                                                @if($item['.tag'] === 'file')
                                                    wire:click="downloadFile('{{ $item['path_display'] }}', '{{ basename($item['path_display'] ?? $item['name']) }}')"
                                                    wire:loading.class="opacity-75"
                                                    wire:target="downloadFile('{{ $item['path_display'] }}', '{{ basename($item['path_display'] ?? $item['name']) }}')"
                                                    title="Download file"
                                                @else
                                                    wire:click="downloadFolderAsZip('{{ $item['path_display'] }}')"
                                                    wire:loading.class="opacity-75"
                                                    wire:target="downloadFolderAsZip('{{ $item['path_display'] }}')"
                                                    title="Download folder as ZIP"
                                                @endif
                                                :class="focusedIndex === {{ $index }} ? 'ring-2 ring-blue-400' : ''"
                                                class="p-1 text-white hover:bg-secondary/80 bg-primary dark:bg-secondary flex items-center transition-colors {{ $item['.tag'] === 'file' ? ($loop->first ? 'rounded-l' : '') : 'rounded-l' }} focus:outline-none"
                                            >
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                
                                                @if($item['.tag'] === 'file')
                                                    <svg wire:loading wire:target="downloadFile('{{ $item['path_display'] }}', '{{ basename($item['path_display'] ?? $item['name']) }}')" 
                                                        class="animate-spin ml-1 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                @else
                                                    <svg wire:loading wire:target="downloadFolderAsZip('{{ $item['path_display'] }}')" 
                                                        class="animate-spin ml-1 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                @endif
                                            </button>

                                            <!-- Move button for all items -->
                                            <button
                                                wire:click="openMoveModal('{{ $item['path_display'] }}')"
                                                wire:loading.class="opacity-75"
                                                wire:target="openMoveModal('{{ $item['path_display'] }}')"
                                                :class="focusedIndex === {{ $index }} ? 'ring-2 ring-blue-400' : ''"
                                                class="p-1 text-white hover:bg-blue-700/80 bg-blue-600 dark:bg-blue-500 rounded-r flex items-center transition-colors focus:outline-none"
                                                title="Move this item"
                                            >
                                                <!-- Default icon (hidden during loading) -->
                                                <svg 
                                                    wire:loading.remove
                                                    wire:target="openMoveModal('{{ $item['path_display'] }}')"
                                                    class="w-3 h-3" 
                                                    fill="none" 
                                                    stroke="currentColor" 
                                                    viewBox="0 0 24 24" 
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                </svg>
                                                
                                                <!-- Loading spinner (visible only during loading) -->
                                                <svg 
                                                    wire:loading
                                                    wire:target="openMoveModal('{{ $item['path_display'] }}')"
                                                    class="animate-spin h-3 w-3" 
                                                    fill="none" 
                                                    viewBox="0 0 24 24"
                                                >
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="5" wire:loading.remove class="px-4 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 text-sm">No files or folders found in this location</p>
                                        <button wire:click="navigateToFolder({{ $actualPath }})" class="mt-3 px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100">
                                            Return to root folder
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        
                            <!-- Files and Folders Loading Animation -->
                            <tr class="w-full"  wire:loading wire:target="loadContents">
                                <td colspan="5" class="w-full px-4 py-8" >
                                    <div class="w-full flex items-center justify-center flex-col">
                                        <!-- Files and Folders Animation Container -->
                                        <div class="mb-4 relative h-16 w-56">
                                            <!-- Folder -->
                                            <div class="absolute left-1/2 transform -translate-x-1/2 top-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                                </svg>
                                            </div>
                                            
                                            <!-- Animated Files -->
                                            <div class="absolute left-8 bottom-0 opacity-0 animate-file1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            
                                            <div class="absolute left-1/2 transform -translate-x-1/2 bottom-0 opacity-0 animate-file2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            
                                            <div class="absolute right-8 bottom-0 opacity-0 animate-file3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Loading text and spinner -->
                                        <div class="flex items-center">
                                            <svg class="animate-spin h-5 w-5 text-blue-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600 font-medium">Getting folders and files...</span>
                                        </div>
                                    </div>
                                    
                                    <!-- CSS Animations for Files -->
                                    <style>
                                        @keyframes file1-animation {
                                            0% { opacity: 0; transform: translate(0, -20px); }
                                            20% { opacity: 1; transform: translate(0, 0); }
                                            80% { opacity: 1; transform: translate(0, 0); }
                                            100% { opacity: 0; transform: translate(0, -20px); }
                                        }
                                        
                                        @keyframes file2-animation {
                                            0% { opacity: 0; transform: translate(0, -20px); }
                                            15% { opacity: 0; transform: translate(0, -20px); }
                                            35% { opacity: 1; transform: translate(0, 0); }
                                            85% { opacity: 1; transform: translate(0, 0); }
                                            100% { opacity: 0; transform: translate(0, -20px); }
                                        }
                                        
                                        @keyframes file3-animation {
                                            0% { opacity: 0; transform: translate(0, -20px); }
                                            30% { opacity: 0; transform: translate(0, -20px); }
                                            50% { opacity: 1; transform: translate(0, 0); }
                                            90% { opacity: 1; transform: translate(0, 0); }
                                            100% { opacity: 0; transform: translate(0, -20px); }
                                        }
                                        
                                        .animate-file1 {
                                            animation: file1-animation 3s infinite;
                        }
                                        
                                        .animate-file2 {
                                            animation: file2-animation 3s infinite;
                                        }
                                        
                                        .animate-file3 {
                                            animation: file3-animation 3s infinite;
                                        }
                                    </style>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
            function fileUploadHandler() {
                return {
                    isDragOver: false,
                    
                    handleDragOver(event) {
                        event.preventDefault();
                        this.isDragOver = true;
                        this.$wire.set('isDragOver', true);
                    },
                    
                    handleDragLeave(event) {
                        if (!event.currentTarget.contains(event.relatedTarget)) {
                            this.isDragOver = false;
                            this.$wire.set('isDragOver', false);
                        }
                    },
                    
                    handleDrop(event) {
                        event.preventDefault();
                        this.isDragOver = false;
                        this.$wire.set('isDragOver', false);
                        
                        const files = Array.from(event.dataTransfer.files);
                        if (files.length > 0) {
                            this.setFilesToInput(files, this.$refs.fileInput);
                        }
                    },
                    
                    setFilesToInput(files, inputElement) {
                        // Create a new DataTransfer object to hold our files
                        const dataTransfer = new DataTransfer();
                        
                        // Add each file to the DataTransfer object
                        files.forEach(file => {
                            dataTransfer.items.add(file);
                        });
                        
                        // Set the files to the input element
                        inputElement.files = dataTransfer.files;
                        
                        // Trigger the change event to notify Livewire
                        inputElement.dispatchEvent(new Event('change', { bubbles: true }));
                        
                        console.log(`${files.length} file(s) ready for upload to current folder`);
                    }
                }
            }

            function folderDropHandler(folderPath) {
                return {
                    isDropTarget: false,
                    folderPath: folderPath,
                    
                    handleDragOver(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        this.isDropTarget = true;
                    },
                    
                    handleDragLeave(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        
                        if (!event.currentTarget.contains(event.relatedTarget)) {
                            this.isDropTarget = false;
                        }
                    },
                    
                    handleFolderDrop(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        
                        this.isDropTarget = false;
                        
                        const files = Array.from(event.dataTransfer.files);
                        if (files.length > 0) {
                            // Find the hidden file input for folder uploads
                            const folderFileInput = document.querySelector('[x-ref="folderFileInput"]');
                            if (folderFileInput) {
                                this.setFilesToInput(files, folderFileInput, this.folderPath);
                            } else {
                                console.error('Folder file input not found');
                            }
                        }
                    },
                    
                    setFilesToInput(files, inputElement, targetPath) {
                        console.log(`Setting ${files.length} files to input for path: ${targetPath}`);
                        
                        // Create a new DataTransfer object to hold our files
                        const dataTransfer = new DataTransfer();
                        
                        // Add each file to the DataTransfer object
                        files.forEach(file => {
                            console.log(`Adding file: ${file.name} (${file.size} bytes)`);
                            dataTransfer.items.add(file);
                        });
                        
                        // Set the files to the input element
                        inputElement.files = dataTransfer.files;
                        console.log(`Input now has ${inputElement.files.length} files`);
                        
                        // Set the target upload path
                        this.$wire.set('targetUploadPath', targetPath);
                        
                        // Trigger the change event to notify Livewire
                        inputElement.dispatchEvent(new Event('change', { bubbles: true }));
                        
                        console.log(`Files set, triggering upload to folder: ${targetPath}`);
                        
                        // Trigger the folder-specific upload after files are set
                        setTimeout(() => {
                            console.log(`Calling uploadToFolder with path: ${targetPath}`);
                            this.$wire.uploadToFolder(targetPath);
                        }, 100);
                    }
                }
            }

            function navigationHandler() {
                return {
                    focusedIndex: -1,
                    totalItems: 0,
                    editingMode: false,
                    creatingMode: false,
                    
                    init() {
                        this.updateItemCount();
                        
                        // Listen for Livewire events to detect editing mode
                        Livewire.on('editingStarted', () => {
                            this.editingMode = true;
                        });
                        
                        Livewire.on('editingEnded', () => {
                            this.editingMode = false;
                            this.$el.focus();
                        });
                        
                        

                        // Listen for folder creation events
                        Livewire.on('creatingFolderStarted', () => {
                            this.creatingMode = true;
                        });
                        
                        Livewire.on('creatingFolderEnded', () => {
                            this.creatingMode = false;
                            this.$el.focus();
                        });
                        
                        // Listen for retry folder upload event
                        this.$wire.on('retry-folder-upload', (event) => {
                            setTimeout(() => {
                                this.$wire.uploadToFolder(event.folderPath);
                            }, 200);
                        });
                    },
                    
                    updateItemCount() {
                        const rows = this.$el.querySelectorAll('tbody tr[x-ref]');
                        this.totalItems = rows.length;
                        
                        // Reset focus if it's out of bounds
                        if (this.focusedIndex >= this.totalItems) {
                            this.focusedIndex = -1;
                        }
                    },
                    
                    handleKeydown(event) {
                        // Skip keyboard navigation when in editing, creating mode, or dragging files
                        if (this.editingMode || this.creatingMode || this.$wire.isDragOver) {
                            return;
                        }
                        
                        this.updateItemCount();
                        
                        if (this.totalItems === 0 && event.key !== 'n' && event.key !== 'N') return;
                        
                        switch(event.key) {
                            case 'ArrowDown':
                                event.preventDefault();
                                this.focusedIndex = this.focusedIndex < 0 ? 0 : (this.focusedIndex + 1) % this.totalItems;
                                this.scrollToFocused();
                                break;
                            case 'ArrowUp':
                                event.preventDefault();
                                this.focusedIndex = this.focusedIndex <= 0 ? this.totalItems - 1 : this.focusedIndex - 1;
                                this.scrollToFocused();
                                break;
                            case 'Enter':
                                event.preventDefault();
                                if (this.focusedIndex >= 0) {
                                    this.selectItem();
                                }
                                break;
                            case 'n':
                            case 'N':
                                event.preventDefault();
                                this.createNewFolder();
                                break;
                            case 'Escape':
                                event.preventDefault();
                                this.focusedIndex = -1;
                                break;
                        }
                    },
                    
                    handleFocusOut(event) {
                        // Only refocus if we're not in editing or creating mode and the focus isn't going to an input element
                        if (!this.editingMode && !this.creatingMode && !event.relatedTarget?.matches('input, button')) {
                            setTimeout(() => {
                                if (!this.editingMode && !this.creatingMode) {
                                    this.$el.focus();
                                }
                            }, 10);
                        }
                    },
                    
                    createNewFolder() {
                        // Trigger Livewire method to start folder creation
                        this.$wire.startCreateFolder();
                    },
                    
                    // FIXED: Better selectItem method that handles both folders and files
                    selectItem() {
                        // Use document.querySelector instead of $refs for more reliable access
                        const focusedRow = document.querySelector(`[x-ref="row-${this.focusedIndex}"]`);
                        if (focusedRow) {
                            // Look for any clickable link in the row
                            const clickableLink = focusedRow.querySelector('a[data-item-type]');
                            if (clickableLink) {
                                clickableLink.click();
                                return;
                            }
                            
                            // Fallback: look for any link
                            const anyLink = focusedRow.querySelector('a[href]');
                            if (anyLink) {
                                anyLink.click();
                                return;
                            }
                            
                            // Final fallback: trigger the first action button
                            const firstButton = focusedRow.querySelector('button');
                            if (firstButton) {
                                firstButton.click();
                            }
                        }
                    },
                    
                    
                    scrollToFocused() {
                        if (this.focusedIndex >= 0) {
                            // Use document.querySelector instead of $refs for more reliable access
                            const focusedRow = document.querySelector(`[x-ref="row-${this.focusedIndex}"]`);
                            if (focusedRow) {
                                focusedRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        }
                    },
                    
                    setFocused(index) {
                        if (!this.editingMode && !this.creatingMode) {
                            this.focusedIndex = index;
                        }
                    }
                }
                Livewire.on('fileProgress', () => {
                            console.log("File store")
                        });
            }

            function renameInput() {
                return {
                    focusAndSelect() {
                        this.$nextTick(() => {
                            this.$el.focus();
                            this.$el.select();
                        });
                    }
                }
            }

            function createFolderInput() {
                return {
                    focusInput() {
                        this.$nextTick(() => {
                            this.$el.focus();
                        });
                    }
                }
            }

            // FIXED: Add progress tracking for uploads
            document.addEventListener('livewire:init', () => {
                Livewire.on('upload:progress', (event) => {
                    console.log('Upload progress:', event.progress + '%');
                });
                
                Livewire.on('upload:finished', (event) => {
                    console.log('Upload finished:', event.filename);
                });
                
                Livewire.on('upload:error', (event) => {
                    console.error('Upload error:', event.error);
                });
            });
            </script>
           
            @if(session('loading_time'))
            <div class="text-xs text-green-600 mt-2">
                {{ session('loading_time') }}
            </div>
        @endif

   
    </div>
    <!-- Move Modal -->
<div x-data="{ open: @entangle('moveModalVisible') }" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div 
            x-show="open" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            class="fixed inset-0 transition-opacity"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
        
        <div 
            x-show="open" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            @click.away="open = false"
        >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Move or Rename Item
                        </h3>
                        <div class="mt-4 space-y-4">
                            <!-- New name field -->
                            <div>
                                <label for="newItemName" class="block text-sm font-medium text-gray-700">Item Name</label>
                                <div class="mt-1">
                                    <input 
                                        type="text" 
                                        wire:model="newItemName" 
                                        id="newItemName" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    >
                                </div>
                                @error('newItemName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Destination folder selection -->
                             
                            <div>
                                <label for="destinationFolder" class="block text-sm font-medium text-gray-700">Destination Folder</label>
                                <div class="mt-1">
                                    <select 
                                        wire:model="selectedDestinationPath" 
                                        id="destinationFolder" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    >
                                        <option value="">Select destination folder</option>
                                        @foreach($availableFolders as $folder)
                                            <option value="{{ $folder['path'] }}">
                                                {{ str_repeat('— ', $folder['level']) }} {{ $folder['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('selectedDestinationPath') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Current path info -->
                            <div class="text-sm text-gray-500">
                                <p>Current location: <span class="font-medium">{{ dirname($itemToMove) }}</span></p>
                                <p>Current name: <span class="font-medium">{{ basename($itemToMove) }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    wire:click="moveItem" 
                    type="button" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                <span wire:loading.remove wire:target="moveItem">Move</span>
                    

                    <span wire:loading wire:target="moveItem">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Moving...
                </span>
                </button>
                <button 
                    wire:click="cancelMove" 
                    type="button" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    <span wire:loading.remove wire:target="cancelMove">Cancel</span>
                    <span wire:loading wire:target="cancelMove">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Wait...
                </span>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="flex flex-wrap items-center gap-2 mb-3 mt-2" 
     x-data="{ 
        canNavigate: {{ $this->canNavigateUp() ? 'true' : 'false' }},
        handleBackspace(event) {
            if (event.key === 'Backspace' && 
                !['INPUT', 'TEXTAREA', 'SELECT'].includes(event.target.tagName) && 
                !event.target.isContentEditable && 
                this.canNavigate) {
                event.preventDefault();
                this.$wire.navigateUp();
            }
        }
     }" 
     @keydown.window="handleBackspace($event)">
    @if($this->canNavigateUp())
        <button wire:click="navigateUp"
                wire:loading.attr="disabled"
                wire:target="navigateUp"
                class="flex items-center px-3 py-2 bg-white rounded-lg shadow-sm text-blue-600 hover:bg-blue-50 transition-all duration-200 border border-gray-200">
            <span wire:loading.remove wire:target="navigateUp" class="flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">Back One Step</span>
            </span>
            <span wire:loading wire:target="navigateUp" class="flex items-center justify-center">
                <svg class="animate-spin mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="font-medium">Navigating...</span>
            </span>
        </button>
    @endif

    <!-- Optional: Show keyboard shortcut hint -->
    @if($this->canNavigateUp())
        <div class="text-xs text-gray-500 bg-blue-100 px-2 py-1 rounded">
            Press <kbd class="bg-white px-1 rounded border">Backspace</kbd> to go back
        </div>
    @endif
    <button wire:click="refreshContents"
        class="inline-flex items-center px-6 py-3 mt-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:from-blue-600 hover:to-purple-700 hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-300/50 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
        wire:loading.attr="disabled">
    <svg wire:loading.remove wire:target="refreshContents" 
         class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
    </svg>
    <svg wire:loading wire:target="refreshContents" 
         class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" 
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <span wire:loading.remove wire:target="refreshContents">Refresh</span>
    <span wire:loading wire:target="refreshContents">Refreshing...</span>
</button>
</div>
        <!-- File Viewer Modal -->
        @if($viewingFile)
            <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 overflow-hidden">
                <div class="bg-white rounded-lg shadow-2xl w-full h-full max-h-screen flex flex-col">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b flex items-center justify-between bg-gray-50">
                        <div class="flex items-center">
                            <!-- File Icon based on type -->
                            <span class="mr-3">
                                @switch($viewingFile['type'])
                                    @case('pdf')
                                        <svg class="w-8 h-8 text-red-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M7 18H17V16H7V18M7 14H17V12H7V14M7 10H17V8H7V10M19 3H5C3.89 3 3 3.89 3 5V19C3 20.11 3.89 21 5 21H19C20.11 21 21 20.11 21 19V5C21 3.89 20.11 3 19 3Z" />
                                        </svg>
                                        @break
                                    @case('image')
                                        <svg class="w-8 h-8 text-blue-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M5,3A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3H5M5,5H19V19H5V5M11.5,7A2.5,2.5 0 0,0 9,9.5A2.5,2.5 0 0,0 11.5,12A2.5,2.5 0 0,0 14,9.5A2.5,2.5 0 0,0 11.5,7M11.5,9A0.5,0.5 0 0,1 12,9.5A0.5,0.5 0 0,1 11.5,10A0.5,0.5 0 0,1 11,9.5A0.5,0.5 0 0,1 11.5,9M7,14H17V17H7V14Z" />
                                        </svg>
                                        @break
                                    @case('spreadsheet')
                                        <svg class="w-8 h-8 text-green-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M3,5H21A1,1 0 0,1 22,6V18A1,1 0 0,1 21,19H3A1,1 0 0,1 2,18V6A1,1 0 0,1 3,5M3,7V10H10V7H3M10,12H3V15H10V12M10,17H3V18H10V17M21,7H12V10H21V7M21,12H12V15H21V12M21,17H12V18H21V17Z" />
                                        </svg>
                                        @break
                                    @case('document')
                                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M8,12V14H16V12H8M8,16V18H13V16H8Z" />
                                        </svg>
                                        @break
                                    @case('presentation')
                                        <svg class="w-8 h-8 text-orange-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M19,16H5V8H19M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z" />
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-8 h-8 text-gray-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                        </svg>
                                @endswitch
                            </span>
                            <!-- Filename -->
                            <h3 class="text-xl font-semibold text-gray-800 truncate">{{ $viewingFile['name'] }}</h3>
                        </div>
                        
                        <button wire:click="closeFileViewer" class="rounded-full p-2 hover:bg-gray-200 transition-colors">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                     <!-- <?php echo json_encode($viewingFile);?>   -->
                    <div class="flex-1 overflow-auto bg-gray-100 p-4">
                        @switch($viewingFile['type'])
                            @case('pdf')
                                <div class="w-full h-full bg-white rounded-lg shadow">
                                    <iframe src="{{ Storage::url('public/' . $viewingFile['storagePath']) }}"
                                        class="w-full h-full rounded-lg" style="min-height: 80vh;"></iframe>
                                </div>
                                @break
                                
                            @case('image')
                                <div class="w-full h-full flex items-center justify-center bg-neutral-800 rounded-lg p-2">
                                    <img src="{{ Storage::url('public/' . $viewingFile['storagePath']) }}"
                                        alt="{{ $viewingFile['name'] }}" class="max-w-full max-h-full object-contain">
                                </div>
                                @break
                            @case('text')
                                <div class="w-full h-full bg-white rounded-lg shadow">
                                    <iframe src="{{url(Storage::url('public/' . $viewingFile['storagePath'])) }}"
                                        class="w-full h-full rounded-lg" style="min-height: 80vh;"></iframe>
                                </div>
                                @break
                            @case('binary')
                            @case('document')
                            @case('presentation')
                                <div class="w-full h-full bg-white rounded-lg shadow">
                                    <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{url(Storage::url('public/' . $viewingFile['storagePath'])) }}"
                                        class="w-full h-full rounded-lg" style="min-height: 80vh;"></iframe>
                                </div>
                                @break
                                
                            @default
                                <div class="flex flex-col items-center justify-center h-full">
                                    <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-lg">
                                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                        </svg>
                                        <h4 class="text-xl font-semibold text-gray-800 mb-2">File Preview Not Available</h4>
                                        <p class="text-gray-600 mb-6">This file type cannot be previewed in the browser. You can download it to view it on your device.</p>
                                        <a href="{{ Storage::url('public/' . $viewingFile['storagePath']) }}" 
                                        download="{{ $viewingFile['name'] }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Download File
                                        </a>
                                    </div>
                                </div>
                        @endswitch
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="px-6 py-3 border-t flex items-center justify-between bg-white">
                        <div class="text-sm text-gray-600">
                            <!-- File info -->
                            @if(isset($viewingFile['size']))
                                <span>{{ $this->formatFileSize($viewingFile['size']) }}</span>
                            @endif
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ Storage::url('public/' . $viewingFile['storagePath']) }}" 
                            download="{{ $viewingFile['name'] }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download
                            </a>
                            <button wire:click="closeFileViewer" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- JavaScript for handling file downloads in Livewire 3 -->
        <script>
            // For Livewire 3
            document.addEventListener('livewire:init', function () {
                Livewire.on('download-file', (data) => {
                    window.open(data[0].url, '_blank');
                });
            });

        </script>
        
    </div>
</div>