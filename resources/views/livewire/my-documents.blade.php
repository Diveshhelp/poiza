<div class="py-2">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">My Documents</h2>
                <div class="flex flex-wrap gap-2">
                    <!-- Download Sample CSV Button -->
                    <a wire:click="downloadSampleCsv" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Sample CSV
                    </a>
                    <!-- CSV Upload Button Group with Dynamic Visibility -->
                    <div x-data="{ fileSelected: false }" class="flex flex-wrap gap-2">
                        <!-- Upload CSV Button - Shown when no file is selected -->
                        <label 
                            for="csvUpload" 
                            x-show="!fileSelected"
                            class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 cursor-pointer"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Select CSV
                            <input 
                                id="csvUpload" 
                                type="file" 
                                wire:model="csvFile" 
                                @change="fileSelected = $event.target.files.length > 0" 
                                class="hidden" 
                                accept=".csv"
                            >
                        </label>
                        
                        <!-- Selected File Name - Shows when a file is selected -->
                        <div 
                            x-show="fileSelected" 
                            class="px-2 py-1 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded flex items-center text-xs md:text-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                            <span x-text="document.getElementById('csvUpload').files[0]?.name || 'File selected'"></span>
                            
                            <!-- Remove file button -->
                            <button 
                                @click="fileSelected = false; document.getElementById('csvUpload').value = ''; $wire.set('csvFile', null)" 
                                class="ml-2 text-gray-500 hover:text-red-500"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Import Button - Only visible when a file is selected -->
                        <button 
                            wire:click="importCSV" 
                            x-show="fileSelected"
                            class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0" 
                            wire:loading.attr="disabled" 
                            wire:target="importCSV"
                        >
                            <span wire:loading.remove wire:target="importCSV">Import</span>
                            <span wire:loading wire:target="importCSV" class="flex items-center">
                                <svg class="animate-spin h-4 w-4 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Importing...
                            </span>
                        </button>
                    </div>
                    
                    <!-- Add Document Button -->
                    <button wire:click="createDocument" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Document
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="search" 
                        wire:model.live="searchTerm" 
                        placeholder="Search documents..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white rounded-md shadow-sm block w-full"
                    >
                    <div wire:loading wire:target="searchTerm" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <!-- File upload error messages -->
            @error('csvFile')
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-900 dark:text-red-200">
                    <p>{{ $message }}</p>
                </div>
            @enderror

            <!-- Document Table -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">Other Info</th>
                                <th class="px-4 py-3">Created At</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($documents as $document)
                                <tr class="text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 text-sm flex items-center" wire:loading.class="bg-gray-50 opacity-70" wire:target="$navigate">
                                    <a wire:navigate href="{{ route('my-document-list',base64_encode($document->id)) }}"
                                        class="hover:text-blue-500 flex items-center"
                                        wire:loading.class="pointer-events-none"
                                        wire:target="$navigate">
                                        <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z">
                                            </path>
                                        </svg>
                                        
                                            <span wire:loading.remove wire:target="$navigate">
                                                {{ $document->document_title }}
                                            </span>
                                            <span wire:loading wire:target="$navigate" class="flex items-center">
                                                <svg class="animate-spin ml-1 mr-2 h-3 w-3 text-blue-500"
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
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ Str::limit($document->other_info, 50) }}</td>
                                    <td class="px-4 py-3 text-sm">{{ date('M d, Y H:i a', strtotime($document->created_at)) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex items-center justify-end gap-3">
                                            <!-- View Button -->
                                            <a href="{{ route('my-document-list',base64_encode($document->id)) }}" 
                                            class="p-1.5 bg-green-50 rounded-lg text-green-600 hover:bg-green-100 transition-colors flex items-center"
                                            wire:navigate
                                            wire:loading.class="opacity-50 cursor-wait"
                                            wire:target="$navigate">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="sr-only">View</span>
                                            </a>
                                            
                                            <!-- Edit Button -->
                                            <button wire:click="editDocument({{ $document->id }})" 
                                                    class="p-1.5 bg-blue-50 rounded-lg text-blue-600 hover:bg-blue-100 transition-colors flex items-center"
                                                    wire:loading.class="opacity-50 cursor-wait"
                                                    wire:target="editDocument({{ $document->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                                <span class="sr-only">Edit</span>
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <button wire:click="confirmDocumentDeletion({{ $document->id }})" 
                                                    class="p-1.5 bg-red-50 rounded-lg text-red-600 hover:bg-red-100 transition-colors flex items-center"
                                                    wire:loading.class="opacity-50 cursor-wait"
                                                    wire:target="confirmDocumentDeletion({{ $document->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="sr-only">Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <div class="flex flex-col items-center justify-center py-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-600 mb-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-lg text-gray-600 dark:text-gray-400 font-medium">No documents found</p>
                                    <p class="text-gray-500 dark:text-gray-500 mt-1">Click "Add Document" to create your first document</p>
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                   
            </div>
            <div class="mt-4 px-4">
                {{ $documents->links() }}
            </div>   
        </div>
    </div>

<!-- Add/Edit Document Modal with Full-Screen Backdrop -->
<div class="fixed inset-0 z-10 overflow-y-auto" 
     x-data 
     x-show="$wire.showModal" 
     style="display: none;">
    <!-- Full-screen backdrop -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
         x-show="$wire.showModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>
    
    <!-- Modal container -->
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             x-show="$wire.showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Modal header and form -->
            <div class="bg-white dark:bg-gray-800 p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    {{ $document_id ? 'Edit Document' : 'Create New Document' }}
                </h3>
                <div class="mt-4">
                    <div class="mb-4">
                        <label for="documentTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Document Title</label>
                        <input type="text" id="documentTitle" wire:model="documentTitle"
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                        @error('documentTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if($errorMessage!="")<span class="text-red-500 text-xs">{{ $errorMessage }}</span> @enderror
                    </div>
                   
                    <div class="mb-4">
                        <label for="other_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other Information</label>
                        <textarea id="other_info" wire:model="other_info" rows="4"
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white rounded-md shadow-sm"></textarea>
                        @error('other_info') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button wire:click="saveDocument" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 ml-3">
                    <span wire:loading.remove wire:target="saveDocument">Save</span>
                    <span wire:loading wire:target="saveDocument" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
                <button wire:click="resetForm" class="px-2 py-1 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 rounded-md border border-gray-300 dark:border-gray-500 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-xs md:text-sm font-medium">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Delete Confirmation Modal -->
    <div class="fixed inset-0 z-10 overflow-y-auto" x-data x-show="$wire.confirmingDocumentDeletion" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" x-show="$wire.confirmingDocumentDeletion">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
            
            <div class="inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" 
                 x-show="$wire.confirmingDocumentDeletion">
                <div class="bg-white dark:bg-gray-800 p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        Delete Document
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Are you sure you want to delete this document? This action cannot be undone.
                        </p>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteDocument" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-red-600 dark:bg-red-500 before:bg-red-700 before:dark:bg-red-600 hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 ml-3">
                        Delete
                    </button>
                    <button wire:click="$set('confirmingDocumentDeletion', false)" class="px-2 py-1 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 rounded-md border border-gray-300 dark:border-gray-500 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-xs md:text-sm font-medium">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification using Alpine.js -->
    <div
        x-data="{ 
            show: false,
            message: '',
            type: 'success',
            showNotification(data) {
                this.message = data.message;
                this.type = data.type;
                this.show = true;
                setTimeout(() => { this.show = false }, 3000);
            }
        }"
        x-on:notify.window="showNotification($event.detail)"
        x-show="show"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50"
        style="display: none;"
    >
        <div class="max-w-sm w-full shadow-lg rounded-lg pointer-events-auto" 
            :class="{ 
                'bg-green-50 dark:bg-green-900': type === 'success',
                'bg-red-50 dark:bg-red-900': type === 'error'
            }">
            <div class="rounded-lg shadow-xs overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg x-show="type === 'success'" class="h-6 w-6 text-green-400 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg x-show="type === 'error'" class="h-6 w-6 text-red-400 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p x-text="message" class="text-sm leading-5 font-medium" :class="{ 
                                'text-green-800 dark:text-green-200': type === 'success',
                                'text-red-800 dark:text-red-200': type === 'error'
                            }"></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="inline-flex text-gray-400 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>