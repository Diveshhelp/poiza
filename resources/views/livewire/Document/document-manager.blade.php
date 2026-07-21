<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div>
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h3 class="text-lg font-medium text-gray-900">{{ $moduleTitle }}
                        {{ $isEditing ? 'Edit' : 'Collection' }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        {{ $isEditing ? 'Edit your document details' : 'Add your document details and manage your documents effectively' }}
                    </p>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('document-collections') }}"
                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mr-3 size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        Back to Documents
                    </a>
                </div>
            </div>

            <div class="mt-5 md:col-span-2">
                <form wire:submit="saveDocument">
                    <div class="mt-3 px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-3">
                            
                            <!-- Ownership Name -->
                            <div class="sm:col-span-1" x-data="{ 
                                showInput: false, 
                                newOwnerTitle: '',
                                closeInput() {
                                    this.showInput = false;
                                    this.newOwnerTitle = '';
                                }
                            }" @ownership-added.window="closeInput()">
                                <label class="block font-medium text-sm text-gray-700" for="ownership_name">
                                    Ownership <span class="text-red-500">*</span>
                                </label>
                                
                                <div class="mt-2" x-show="!showInput">
                                    <select wire:model="ownership_name"
                                        @change="$event.target.value === 'new' ? showInput = true : showInput = false"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Select Owner</option>
                                        <option value="new">Add New Ownership Name</option>
                                        @foreach ($ownerships as $ownership)
                                            <option value="{{ $ownership->id }}">{{ $ownership->owner_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="mt-2" x-show="showInput">
                                    <div class="flex gap-2">
                                        <input type="text" 
                                            wire:model="new_ownership_title"
                                            placeholder="Enter new ownership name"
                                            @keydown.enter.prevent="$wire.saveNewOwnership()"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>
                                @error('ownership_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                @error('new_ownership_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Department -->
                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="department_id">
                                    Department <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="department_id"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Doc Category -->
                            <div class="sm:col-span-1" x-data="{ 
                                showInput: false, 
                                newCategoryTitle: '',
                                closeInput() {
                                    this.showInput = false;
                                    this.newCategoryTitle = '';
                                }
                            }" @category-added.window="closeInput()">
                                <label class="block font-medium text-sm text-gray-700" for="doc_categories_id">
                                    Document Category <span class="text-red-500">*</span>
                                </label>
                                
                                <div class="mt-2" x-show="!showInput">
                                    <select wire:model="doc_categories_id"
                                        @change="$event.target.value === 'new' ? showInput = true : showInput = false"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Select Category</option>
                                        <option value="new">Add New Document Category Name</option>
                                        @foreach ($doc_categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="mt-2" x-show="showInput">
                                    <div class="flex gap-2">
                                        <input type="text" 
                                            wire:model="new_category_title"
                                            placeholder="Enter new category name"
                                            @keydown.enter.prevent="$wire.saveNewCategory()"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>
                                @error('doc_categories_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                @error('new_category_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Doc Title -->
                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="doc_title">
                                    Doc Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="doc_title"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    placeholder="Enter document title">
                                @error('doc_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


    
                                
                            <!-- Doc Update Date -->
                            <div class="sm:grid grid-cols-2 gap-4" x-data="{ date: @entangle('doc_update_date') }">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700" for="doc_update_date">
                                        Doc Issue Date 
                                    </label>
                                    <input
                                        type="date"
                                        placeholder="DD-MM-YYYY"
                                        wire:model="doc_update_date"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                    @error('doc_update_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div x-data="{ date: @entangle('doc_year') }">
                                    <label class="block font-medium text-sm text-gray-700" for="doc_year">
                                        Doc Year 
                                    </label>
                                    <input 
                                        type="text" 
                                        placeholder="YYYY-YYYY"
                                        wire:model="doc_year"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                    @error('doc_year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Doc Validity and Renewal Date Section -->
                            <div class="sm:grid grid-cols-2 gap-4" x-data="{
                                validityValue: @entangle('doc_validity'),
                                updateRenewalDate() {
                                if (this.validityValue == 1) {
                                    $wire.doc_renewal_dt = 'NO';
                                } else {
                                    $wire.doc_renewal_dt = null;
                                }
                                }
                                }">
                                <!-- Doc Validity -->
                                <div>
                                    <label class="block font-medium text-sm text-gray-700" for="doc_validity">
                                    Doc Validity <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                    x-model="validityValue"
                                    wire:model.live="doc_validity"
                                    @change="updateRenewalDate()"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    @foreach ($doc_validity_list as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                    @error('doc_validity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Doc Renewal Date -->
                                <div>
                                    <label class="block font-medium text-sm text-gray-700" for="doc_renewal_dt">
                                    Doc Renewal Date <span class="text-red-500">*</span>
                                    </label>
                                    <template x-if="validityValue == 1">
                                    <input type="text"
                                        value="NO"
                                        disabled
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 bg-gray-100 sm:text-sm sm:leading-6">
                                    </template>
                                    <template x-if="validityValue != 1">
                                    <input type="date"
                                        placeholder="DD-MM-YYYY"
                                        wire:model="doc_renewal_dt"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </template>
                                    @error('doc_renewal_dt')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="sm:grid grid-cols-2 gap-4" x-data="{ date: @entangle('doc_number') }">
                                <div >
                                    <label class="block font-medium text-sm text-gray-700" for="doc_year">
                                        Doc Number 
                                    </label>
                                    <input 
                                        type="text" 
                                        placeholder="Document Number" 
                                        wire:model="doc_number"
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                    @error('doc_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700" for="doc_expire_date">
                                            Doc Exp Date 
                                        </label>
                                        <input
                                            type="date"
                                            placeholder="DD-MM-YYYY"
                                            wire:model="doc_expire_date"
                                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            />
                                        @error('doc_update_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="sm:col-span-2">
                                        <label class="block font-medium text-sm text-gray-700" for="name">
                                            Document Notes
                                        </label>
                                        <div class="mt-2">
                                            <textarea wire:model="doc_note" rows="2"
                                                placeholder="Document Notes" autocomplete="doc_note"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                        </div>
                                        <div class="text-red-500">@error('doc_note') {{ $message }} @enderror
                                        </div>
                                    </div>
                                   

                                <div class="sm:col-span-3">
                                    <label for="doc_file" class="text-sm font-medium text-gray-700">Upload Documents</label>
                                    <div class="mt-1 flex items-center px-4 py-3 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                                        <div class="flex items-center space-x-2">
                                        <label for="doc_file" class="cursor-pointer bg-white font-medium text-sm text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload files</span>
                                            <input id="doc_file" type="file" wire:model="temp_files" class="sr-only" multiple>
                                        </label>
                                        <span class="text-xs text-gray-500">(PNG, JPG, GIF, PDF, DOC, DOCX, XLS, XLSX)</span>
                                        </div>
                                    </div>
                                </div> 
                                <div class="sm:col-span-3 mt-2">
                                    <div class="flex items-start">
                                        <input
                                            id="share_with_firm"
                                            type="checkbox"
                                            wire:model="share_with_firm"
                                            class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                        >
                                        <div class="ml-2">
                                            <label for="share_with_firm" class="block text-sm font-medium text-gray-700">
                                                Do you want to share this document with firm?
                                            </label>
                                            <p class="mt-1 text-xs text-red-500">
                                                <b>Notice:</b> When checked, this document will be visible to all members of the firm. Leave unchecked to keep it private to your account only.
                                            </p>
                                        </div>
                                    </div>
                                    @error('share_with_firm')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Loading State -->
                                <div wire:loading wire:target="temp_files" class="mt-2">
                                    <div class="inline-flex items-center text-sm text-blue-600">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Processing files...
                                    </div>
                                </div>

                                <!-- Error Messages -->
                                @error('temp_files.*')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                               
                                           
                            </div>
                             <!-- Files Preview Section -->
                             @if($doc_file && count($doc_file) > 0)
                                    <div class="mt-4 space-y-4">
                                        <div class="text-sm font-medium text-gray-700">Selected files:</div>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 gap-2">
                                        @foreach($doc_file as $index => $file)
                                                <div class="relative group">
                                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors duration-150">
                                                        @php
                                                            $mimeType = $file->getMimeType();
                                                            $extension = strtoupper($file->getClientOriginalExtension());
                                                            $fileIcon = match (true) {
                                                                str_contains($mimeType, 'pdf') => '<svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 384 512"><path d="M181.9 256.1c-5-16-4.9-46.9-2-46.9 8.4 0 7.6 36.9 2 46.9zm-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7 18.3-7 39-17.2 62.9-21.9-12.7-9.6-24.9-23.4-34.5-40.8zM86.1 428.1c0 .8 13.2-5.4 34.9-40.2-6.7 6.3-29.1 24.5-34.9 40.2zM248 160h136v328c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V24C0 10.7 10.7 0 24 0h200v136c0 13.2 10.8 24 24 24zm-8 171.8c-20-12.2-33.3-29-42.7-53.8 4.5-18.5 11.6-46.6 6.2-64.2-4.7-29.4-42.4-26.5-47.8-6.8-5 18.3-.4 44.1 8.1 77-11.6 27.6-28.7 64.6-40.8 85.8-.1 0-.1.1-.2.1-27.1 13.9-73.6 44.5-54.5 68 5.6 6.9 16 10 21.5 10 17.9 0 35.7-18 61.1-61.8 25.8-8.5 54.1-19.1 79-23.2 21.7 11.8 47.1 19.5 64 19.5 29.2 0 31.2-32 19.7-43.4-13.9-13.6-54.3-9.7-73.6-7.2zM377 105L279 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-74.1 255.3c4.1-2.7-2.5-11.9-42.8-9 37.1 15.8 42.8 9 42.8 9z"/></svg>',
                                                                str_contains($mimeType, 'word') || str_contains($mimeType, 'doc') => '<svg class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 384 512"><path d="M224 136V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zm57.1 120H208c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h73.1c6.6 0 12 5.4 12 12v8c0 6.6-5.4 12-12 12zm0 64H208c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h73.1c6.6 0 12 5.4 12 12v8c0 6.6-5.4 12-12 12zm0 64H208c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h73.1c6.6 0 12 5.4 12 12v8c0 6.6-5.4 12-12 12zm76.4-309.1L279.1 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9z"/></svg>',
                                                                default => null
                                                            };
                                                        @endphp

                                                        @if(str_contains($mimeType, 'image'))
                                                            <!-- Image Preview -->
                                                            <div class="relative h-20 w-20 mr-3 rounded-lg overflow-hidden">
                                                                <img src="{{ $file->temporaryUrl() }}"
                                                                    class="h-full w-full object-cover" 
                                                                    alt="Preview">
                                                            </div>
                                                        @else
                                                            <!-- Document Icon -->
                                                            <div class="flex items-center justify-center h-20 w-20 mr-3 bg-gray-200 rounded-lg">
                                                                @if($fileIcon)
                                                                    {!! $fileIcon !!}
                                                                @else
                                                                    <span class="text-sm font-medium text-gray-600">
                                                                        {{ $extension }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endif

                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                                {{ $file->getClientOriginalName() }}
                                                            </p>
                                                            <p class="text-sm text-gray-500">
                                                                {{ number_format($file->getSize() / 1024 / 1024, 2) }} MB
                                                            </p>
                                                        </div>

                                                        <!-- Remove Button -->
                                                        <button type="button" 
                                                            wire:click="removeFile({{ $index }})"
                                                            class="ml-2 flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors duration-150">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            <!-- Existing Attachments Section -->
                            @if ($isEditing && count($existing_attachments) > 0)
                                <div class="mt-6 space-y-4" x-data="{ 
                                        showPreview: false,
                                        previewUrl: '',
                                        previewType: '',
                                        previewTitle: '',
                                        openPreview(url, type, title) {
                                        console.log(url);
                                            this.previewUrl = url;
                                            this.previewType = type;
                                            this.previewTitle = title;
                                            this.showPreview = true;
                                        }
                                        }">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-900">Existing Attachments</h4>
                                        <span class="text-sm text-gray-500">{{ count($existing_attachments) }} files</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 gap-2">
                                        @foreach ($existing_attachments as $attachment)
                                            @php
                                                $extension = strtoupper(pathinfo($attachment->original_file_name, PATHINFO_EXTENSION));
                                                $mimeType = Storage::disk('public')->mimeType($attachment->file_path);
                                                $fileUrl = "/public".Storage::url($attachment->file_path);
                                                $fileIcon = match(true) {
                                                    str_contains($mimeType, 'pdf') => 'pdf',
                                                    str_contains($mimeType, 'word') || str_contains($mimeType, 'doc') => 'word',
                                                    str_contains($mimeType, 'sheet') || str_contains($mimeType, 'excel') => 'excel',
                                                    default => 'file'
                                                };
                                            @endphp
                                            <div class="relative group">
                                                <div class="flex items-center p-4 bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors duration-150">
                                                    <!-- File Preview/Icon -->
                                                    <div class="relative h-20 w-20 mr-3 rounded-lg overflow-hidden cursor-pointer"
                                                        @click="openPreview('{{ $fileUrl }}', '{{ str_contains($mimeType, 'image') ? 'image' : (str_contains($mimeType, 'pdf') ? 'pdf' : $fileIcon) }}', '{{ $attachment->original_file_name }}')">
                                                        @if(str_contains($mimeType, 'image'))
                                                            <img src="{{ $fileUrl }}" 
                                                                class="h-full w-full object-cover" 
                                                                alt="{{ $attachment->original_file_name }}">
                                                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-25 transition-opacity"></div>
                                                        @else
                                                            <div class="h-full w-full flex items-center justify-center bg-gray-200 group-hover:bg-gray-300 transition-colors">
                                                                @switch($fileIcon)
                                                                    @case('pdf')
                                                                        <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 384 512">
                                                                            <path d="M181.9 256.1c-5-16-4.9-46.9-2-46.9 8.4 0 7.6 36.9 2 46.9zm-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7 18.3-7 39-17.2 62.9-21.9-12.7-9.6-24.9-23.4-34.5-40.8zM86.1 428.1c0 .8 13.2-5.4 34.9-40.2-6.7 6.3-29.1 24.5-34.9 40.2zM248 160h136v328c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V24C0 10.7 10.7 0 24 0h200v136c0 13.2 10.8 24 24 24zm-8 171.8c-20-12.2-33.3-29-42.7-53.8 4.5-18.5 11.6-46.6 6.2-64.2-4.7-29.4-42.4-26.5-47.8-6.8-5 18.3-.4 44.1 8.1 77-11.6 27.6-28.7 64.6-40.8 85.8-.1 0-.1.1-.2.1-27.1 13.9-73.6 44.5-54.5 68 5.6 6.9 16 10 21.5 10 17.9 0 35.7-18 61.1-61.8 25.8-8.5 54.1-19.1 79-23.2 21.7 11.8 47.1 19.5 64 19.5 29.2 0 31.2-32 19.7-43.4-13.9-13.6-54.3-9.7-73.6-7.2zM377 105L279 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9z"/>
                                                                        </svg>
                                                                        @break
                                                                    @case('word')
                                                                        <svg class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 384 512">
                                                                            <path d="M224 136V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zm57.1 120H208c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h73.1c6.6 0 12 5.4 12 12v8c0 6.6-5.4 12-12 12zm0 64H208c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h73.1c6.6 0 12 5.4 12 12v8c0 6.6-5.4 12-12 12zm0 64H208c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h73.1c6.6 0 12 5.4 12 12v8c0 6.6-5.4 12-12 12z"/>
                                                                        </svg>
                                                                        @break
                                                                    @default
                                                                        <span class="text-sm font-medium text-gray-600">{{ $extension }}</span>
                                                                @endswitch
                                                            </div>
                                                        @endif
                                                        <div class="absolute bottom-0 inset-x-0 bg-black bg-opacity-50 text-white text-xs py-1 text-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                            Preview
                                                        </div>
                                                    </div>

                                                    <!-- File Info -->
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate" title="{{ $attachment->original_file_name }}">
                                                            {{ $attachment->original_file_name }}
                                                        </p>
                                                        <div class="mt-1 flex items-center space-x-4">
                                                            <p class="text-sm text-gray-500">
                                                                {{ number_format($attachment->file_size / 1024 / 1024, 2) }} MB
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Action Buttons -->
                                                    <div class="flex items-center space-x-2">
                                                        <button type="button" 
                                                            wire:click="downloadAttachment('{{ $attachment->uuid }}')"
                                                            class="p-1 text-gray-400 hover:text-gray-600 transition-colors duration-150"
                                                            title="Download">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                            </svg>
                                                        </button>

                                                        <button type="button" 
                                                            wire:click="deleteAttachment('{{ $attachment->uuid }}')"
                                                            class="p-1 text-gray-400 hover:text-red-500 transition-colors duration-150"
                                                            title="Delete">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Preview Modal -->
                                    <div x-show="showPreview" 
                                        x-cloak 
                                        class="fixed inset-0 z-50 overflow-y-auto"
                                        @click.self="showPreview = false"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0">
                                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                            </div>

                                            <div class="inline-block align-bottom bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-5xl sm:w-full"
                                                @click.away="showPreview = false">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start">
                                                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                            <div class="flex justify-between items-center mb-4">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="previewTitle"></h3>
                                                                <a @click="showPreview = false" class="text-gray-400 hover:text-gray-500">
                                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                            
                                                            <!-- Preview Content -->
                                                            <div class="mt-2">
                                                                <template x-if="previewType === 'image'">
                                                                    <img :src="previewUrl" class="max-w-full max-h-[70vh] mx-auto object-contain" :alt="previewTitle">
                                                                </template>
                                                                
                                                                <template x-if="previewType === 'pdf'">
                                                                    <iframe :src="previewUrl" class="w-full h-[70vh]" frameborder="0"></iframe>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif     
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                        <button type="submit"
                            class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                            <span class="indicator-label" wire:loading.remove wire:target="saveDocument">
                                {{ $isEditing ? 'Update Document' : 'Create Document' }}
                            </span>
                            <span class="indicator-progress" wire:loading wire:target="saveDocument">
                                <svg class="w-5 h-5 mr-3 ml-3 text-white animate-spin"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>