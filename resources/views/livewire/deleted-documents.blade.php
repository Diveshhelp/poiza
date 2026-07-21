<div>
<div  class="mx-auto py-2 sm:px-6 lg:px-8">
        <div>
        <div class="flex flex-wrap items-center justify-between">
                <div class="min-w-0 flex-1 pr-2">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">{{ $moduleTitle }}</h3>
                    <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600 leading-tight sm:leading-relaxed line-clamp-1 sm:line-clamp-none">
                    View and manage deleted documents
                    </p>
                </div>
                <div class="flex shrink-0 sm:ml-4">
                    <a href="{{ route('document-collections') }}"
                    class="inline-flex items-center justify-center px-2 py-1.5 text-white text-xs sm:text-sm font-medium hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] before:w-0 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="mr-1.5 size-4 sm:size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="whitespace-nowrap">Back</span>
                    </a>
                </div>
            </div>


            <div class="mt-4">
                <div class="bg-white rounded-lg shadow overflow-x-auto">
                    <!-- Desktop Table View -->
                    <table class="hidden md:table min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr> 
                                <th wire:click="sortBy('ownership_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Owner
                                    @if ($sortField === 'ownership_name')
                                        <span class="ml-1">
                                            @if ($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        </span>
                                    @endif
                                </th>
                                <th wire:click="sortBy('doc_title')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Document Title
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
                                <th wire:click="sortBy('doc_categories_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Category
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deleted At
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Notes 
                                </th>     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Files 
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($documents as $document)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $document->ownership->owner_title ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $document->doc_title??'' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $document->doc_categories->category_title ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $document->deleted_at->format('d M Y, h:i A') }}</div>
                                    </td>
                                    <td class="px-4 py-3" title="{{ $document->doc_note }}">
                                        <!-- Note with Icon - Inline display -->
                                        <div class="flex items-center">
                                            @if ($document->doc_note != '')
                                                <div x-data="{ showNotePopup: false }" @keydown.escape.window="showNotePopup = false"
                                                    class="flex items-center w-full">

                                                    <!-- Icon Button -->
                                                    <button @click="showNotePopup = true"
                                                        class="flex-shrink-0 mr-2 p-2 bg-gray-100 hover:bg-blue-100 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
                                                        title="View note details">
                                                        <svg class="w-5 h-5 text-gray-600 hover:text-blue-600"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Note Text (Truncated) - Same line as icon -->
                                                    <span
                                                        class="text-sm text-gray-900 truncate w-64">{{ $document->doc_note }}</span>

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
                                                    <div class="flex-shrink-0 mr-2 p-2 bg-gray-100 rounded-full opacity-50">
                                                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm text-gray-400 italic">No notes available</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
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
                                        }" class="flex items-center space-x-2">
                                            <!-- Attachments counter -->
                                            <span :class="getBgColor()"
                                                class="px-2.5 py-1 inline-flex items-center gap-1.5 text-xs font-medium rounded-full transition-colors duration-150 hover:bg-opacity-80">
                                                <!-- Document icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span x-text="`${count} ${count === 1 ? 'file' : 'files'}`"></span>
                                            </span>

                                            
                                            <a href="#"
                                        :class="getSubDocBgColor()"
                                        class="px-2.5 py-1 inline-flex items-center gap-1.5 text-xs font-medium rounded-full transition-colors duration-150 hover:bg-opacity-80 cursor-pointer">
                                        <!-- Folder/sub-document icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                        <span
                                            x-text="`${subDocCount} ${subDocCount === 1 ? 'sub-doc' : 'sub-docs'}`"></span>
                                    </a>

                                </div>
                            </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-4">
                                            <button wire:click="restore('{{ $document->uuid }}')"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-100 rounded-full hover:bg-indigo-200"
                                                wire:confirm='{{ COMMON_RESTORE_CONFIRM }}'>
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Restore
                                            </button>
                                            <button                             
                                                wire:click="confirmDelete('{{ $document->uuid }}')" 
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-100 rounded-full hover:bg-red-200">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete Permanently
                                            </button>
                                        </div>
                                    </td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No deleted documents found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                
                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4">
                    @forelse ($documents as $document)
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <!-- Document Title Header -->
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                    {{ $document->doc_title }}
                                </h3>
                            </div>
                            
                            <!-- Info Grid -->
                            <div class="p-4 space-y-3">
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="text-sm font-medium text-gray-500">Owner</div>
                                    <div class="col-span-2 text-sm text-gray-900">{{ $document->ownership->owner_title ?? 'N/A' }}</div>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="text-sm font-medium text-gray-500">Category</div>
                                    <div class="col-span-2 text-sm text-gray-900">{{ $document->doc_categories->category_title ?? 'N/A' }}</div>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="text-sm font-medium text-gray-500">Deleted At</div>
                                    <div class="col-span-2 text-sm text-gray-900">{{ $document->deleted_at->format('d M Y, h:i A') }}</div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end space-x-2">
                                <button wire:click="restore('{{ $document->uuid }}')"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full hover:bg-indigo-200"
                                    wire:confirm='{{ COMMON_RESTORE_CONFIRM }}'>
                                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Restore
                                </button>
                                <button                             wire:click="confirmDelete('{{ $document->uuid }}')" 
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-100 rounded-full hover:bg-red-200"
                                                x>
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete Permanently
                                            </button>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="px-4 py-5 text-center text-gray-500">
                                No deleted documents found
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
  <!-- Delete Confirmation Modal -->
  @if($confirmingDelete)
      <!-- Email Modal -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div class="inline-block w-full overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:max-w-lg">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    Permanent Delete Confirmation
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to permanently delete this document? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Security Code Input -->
                        <div class="mt-4">
                            <label for="securityCode" class="block text-sm font-medium text-gray-700">
                                Enter security code to confirm
                            </label>
                            <div class="mt-1">
                                <input wire:model.defer="securityCode" maxlength="6" type="text" autofocus name="securityCode" id="securityCode" 
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                                    placeholder="Enter security code" required>
                            </div>
                            @error('securityCode') 
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="permanentDelete('{{ $documentToDelete }}')" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button wire:click="cancelDelete" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>