<div>
    <div class="max-w-7xl mx-auto py-1 sm:px-6 lg:px-8" wire:key="sub-doc-manager-module-{{ time() }}">
        <div>
            <!-- Header -->
            <div class="flex flex-wrap items-center justify-between">
                <div class="min-w-0 flex-1 pr-2">
                    <h3 class="text-lg font-medium text-gray-900">{{ $isEditing ? 'Edit' : 'Add' }} Sub Document for
                        <span class="font-medium text-gray-700">{{ $parentDocument->doc_title }}</span>
                    </h3>
                    <p
                        class="mt-1 md:mt-2 text-xs md:text-sm text-gray-600 leading-relaxed line-clamp-1 md:line-clamp-none">
                        @if ($isEditing)
                            Edit sub document related details with required information and attachments.
                        @else
                            Add sub document related details with required information and attachments.
                        @endif
                    </p>
                </div>
                <div class="flex shrink-0 md:ml-4 md:mt-0">
                    <a href="{{ route('document-collections') }}"
                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mr-1 md:mr-3 size-4 md:size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        <span class="whitespace-nowrap">Go back</span>
                    </a>
                </div>
            </div>
            <!-- Header -->

            <div class="mt-5 md:col-span-2">
                <!-- Form -->
                <form wire:submit.prevent="saveDocument">
                    <div class="mt-3 px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                        <div class="space-y-6">
                            <!-- Three column layout -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Document
                                        Title
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" id="title" wire:model="doc_title"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        >
                                        <div class="text-red-500">@error('doc_title') {{ $message }} @enderror</div>

                                </div>

                                <!-- Document Number -->
                                <div>
                                    <label for="doc_number" class="block text-sm font-medium text-gray-700">
                                        Document Number
                                    </label>
                                    <input type="text" 
                                        id="doc_number" 
                                        wire:model="doc_number"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <div class="text-red-500">@error('doc_number') {{ $message }} @enderror</div>
                                </div>

                                <!-- Validity Status -->
                                <div>
                                    <label for="validity" class="block text-sm font-medium text-gray-700">
                                        Validity Status
                                    </label>
                                    <select id="validity" 
                                        wire:model="validity"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="1">Valid</option>
                                        <option value="2">Expiring Soon</option>
                                        <option value="3">Expired</option>
                                    </select>
                                    <div class="text-red-500">@error('validity') {{ $message }} @enderror</div>
                                </div>

                                
                            </div> 
                            

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <!-- Issue Date -->
                                
                                <div>
                                    <label for="doc_update_date" class="block text-sm font-medium text-gray-700">
                                        Issue Date
                                    </label>
                                    <input type="date" 
                                        id="doc_update_date" 
                                        wire:model="doc_update_date"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <div class="text-red-500">@error('doc_update_date') {{ $message }} @enderror</div>
                                </div>

                                <div>
                                    <label for="doc_update_date" class="block text-sm font-medium text-gray-700">
                                        Exp. Date
                                    </label>
                                    <input type="date" 
                                        id="doc_expire_date" 
                                        wire:model="doc_expire_date"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <div class="text-red-500">@error('doc_expire_date') {{ $message }} @enderror</div>
                                </div>
                                <!-- Document Year --> 
                                <div>
                                    <label for="doc_year" class="block text-sm font-medium text-gray-700">
                                        Document Year
                                    </label>
                                    <input type="text" 
                                        id="doc_year" 
                                        wire:model.blur="doc_year"
                                        placeholder="YYYY-YYYY" 
                                        pattern="\d{4}-\d{4}" 
                                        maxlength="9"
                                        @input="$el.value = $el.value.replace(/[^0-9-]/g, '').replace(/(....)-?(....)?/, '$1-$2')"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        {{-- <div class="text-red-500">@error('doc_year') {{ $message }} @enderror</div> --}}
                                        @error('doc_year') 
                                            <div class="text-red-500">{{ $message }}</div>
                                        @enderror
                                </div>

                                <!-- Document Info -->
                              
                                <div>
                                    <label for="doc_info" class="block text-sm font-medium text-gray-700">
                                        Document Info
                                    </label>
                                    <textarea 
                                        id="doc_info" 
                                        wire:model="doc_info"
                                        rows="1"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="Enter additional information..."></textarea>
                                    <div class="text-red-500">@error('doc_info') {{ $message }} @enderror</div>
                                </div>
                                
                            </div>

                            <!-- File Upload Section -->
                            <div class="pt-4 border-t border-gray-200">
                                <!-- Improved File Upload Component -->
                                <div class="space-y-4" x-data="{
                                    filesAdded: {{ count($files) > 0 ? 'true' : 'false' }},
                                    files: [],
                                    isEditing: {{ $isEditing ? 'true' : 'false' }},
                                    existingFiles: {{ json_encode($existingFiles) }},
                                    handleFileSelect(event) {
                                        this.filesAdded = true;
                                        this.files = Array.from(event.target.files);
                                        @this.set('files', event.target.files);
                                    },
                                    removeExistingFile(uuid) {
                                        @this.removeExistingFile(uuid);
                                    }
                                }"
                                    @document-updated.window="filesAdded = false; files = []">
                                    <div>
                                        <!-- Upload Container -->

                                        <div class="mt-2 flex items-center justify-between transition-colors">
                                            <div class="flex-1">
                                                <label class="text-sm font-medium text-gray-700 mb-2">Upload
                                                    Documents</label>
                                                <div
                                                    class="mt-1 flex items-center px-4 py-3 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                                                    <div class="flex items-center space-x-2">
                                                        <label for="file-upload"
                                                            class="cursor-pointer bg-white font-medium text-sm text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                            <span>Upload files</span>
                                                            <input id="file-upload" type="file" multiple
                                                                class="sr-only" wire:model.blur="files" x-ref="fileInput"
                                                                @change="handleFileSelect($event)">
                                                        </label>
                                                        <span class="text-xs text-gray-500">(PNG, JPG, GIF, PDF,
                                                            DOC,
                                                            DOCX)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div x-show="files.length" class="text-sm text-gray-500">
                                            <span x-text="files.length + ' file(s) selected'"></span>
                                        </div>
                                    </div>

                                    <!-- File Preview Area -->
                                    @if (count($files) > 0)
                                        <div class="mt-4">
                                            <h4 class="text-sm font-medium text-gray-700 mb-3">Selected files:</h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach ($files as $index => $file)
                                                    <div
                                                        class="relative flex flex-col border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                                        <!-- Remove Button -->
                                                        <button wire:click="removeUploadedFile({{ $index }})"
                                                            type="button"
                                                            class="absolute top-2 right-2 p-1 bg-white rounded-full shadow hover:bg-red-50">
                                                            <svg class="h-4 w-4 text-red-500"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>

                                                        <!-- File Preview -->
                                                        <div class="h-32 bg-gray-50 flex items-center justify-center">
                                                            @if (str_contains($file->getMimeType(), 'image/'))
                                                                <img src="{{ $file->temporaryUrl() }}"
                                                                    class="h-full w-full object-contain" />
                                                            @else
                                                                <svg class="h-12 w-12 text-gray-400"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                </svg>
                                                            @endif
                                                        </div>

                                                        <!-- File Info -->
                                                        <div class="p-3">
                                                            <div class="truncate text-sm font-medium text-gray-900">
                                                                {{ $file->getClientOriginalName() }}</div>
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                {{ number_format($file->getSize() / 1024, 1) }} KB
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Upload Progress (when files are being uploaded) -->
                                    <div wire:loading wire:target="files" class="mt-2">
                                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                            <div class="flex">
                                                <svg class="animate-spin h-5 w-5 text-blue-600 mr-3"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-800">Uploading files...
                                                    </p>
                                                    <p class="mt-1 text-sm text-blue-600">Please wait while your
                                                        files
                                                        are being uploaded.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Update the existing files section -->
                                    @if ($isEditing && count($existingFiles) > 0)
                                        <div class="mt-2">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-medium text-gray-600">Existing
                                                    Files</span>
                                                <span class="text-xs text-gray-400">{{ count($existingFiles) }}
                                                    items</span>
                                            </div>

                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach ($existingFiles as $file)
                                                    <div
                                                        class="flex items-center bg-white rounded border border-gray-200 px-2 py-1.5">
                                                        <!-- File icon/thumbnail -->
                                                        <div
                                                            class="h-8 w-8 flex-shrink-0 flex items-center justify-center mr-2">
                                                            @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file->original_file_name))
                                                                <img src="{{ Storage::url($file->file_path) }}"
                                                                    class="h-8 w-8 object-cover rounded" />
                                                            @elseif(str_ends_with($file->original_file_name, '.pdf'))
                                                                <svg class="h-6 w-6 text-red-500"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                </svg>
                                                            @endif
                                                        </div>

                                                        <!-- File details -->
                                                        <div class="flex-grow min-w-0">
                                                            <div class="truncate text-xs font-medium text-gray-700">
                                                                {{ $file->original_file_name }}</div>
                                                        </div>

                                                        <!-- Delete button -->
                                                        <button
                                                            x-on:click="if (confirm('Are you sure you want to delete this file?')) { $wire.removeExistingFile('{{ $file->uuid }}') }"
                                                            type="button"
                                                            class="ml-2 p-1 text-gray-400 hover:text-red-500">
                                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">

                        <a href="{{ route('sub-documents', ['parentId' => $parentDocument->uuid]) }}"
                            class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                            Cancel
                        </a>

                        <button type="submit" wire:loading.remove wire:target="saveDocument"
                            class="ml-4 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                            {{ $isEditing ? 'Update Document' : 'Add Document' }}
                        </button>
                        <button type="button" wire:loading wire:target="saveDocument" disabled
                            class="ml-4 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Saving...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
