<div>
    <div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-2" wire:key="todo-manager-module-{{ time() }}">
        <div>
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-900">{{$moduleTitle}} {{ $isEditing ? 'Edit' : 'Collection' }}</h3>
                        <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                            {{ $isEditing ? 'Edit your leave application and manage approvals' : 'Manage all leave applications and approvals' }}
                        </p>
                    </div>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('leave-collections') }}"
                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mr-3 size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        Back to Leave
                    </a>
                </div>
            </div>


            <div class="mt-5 md:col-span-2">
                <form wire:submit="saveLeave">
                    <div class="mt-3 px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
                            <!-- Start Date -->
                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="start_date">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model.live="start_date"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="end_date">
                                    End Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model.live="end_date"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2 w-full">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-6 md:gap-x-4 items-start md:items-center">
                <!-- Total Days -->
                <div class="w-full">
                    <label class="block font-medium text-sm text-gray-700" for="total_days">
                        Total Days
                    </label>
                    <input type="text" wire:model="total_days" readonly
                        class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-400 sm:text-sm sm:leading-6 {{ $is_full_day === 'no' ? 'bg-gray-100' : '' }}">
                    @error('total_days')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Full Day -->
                <div class="flex flex-col md:flex-row md:items-center">
                    <label class="block font-medium text-sm text-gray-700 mb-2 md:mb-0">
                        Leave Type <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-4 mt-2 md:mt-0 md:ml-2">
                        <div class="flex items-center">
                            <input id="is_full_day_yes" wire:model="is_full_day" type="radio"
                                value="yes"
                                class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-600">
                            <label for="is_full_day_yes"
                                class="ml-2 block text-sm font-medium leading-6 text-gray-900">
                                Full Day
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="is_full_day_no" wire:model="is_full_day" type="radio"
                                value="no"
                                class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-600">
                            <label for="is_full_day_no"
                                class="ml-2 block text-sm font-medium leading-6 text-gray-900">
                                Half Day
                            </label>
                        </div>
                    </div>
                    @error('is_full_day')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Leave Half (conditionally shown) -->
                <div x-data="{}" x-show="$wire.is_full_day === 'no'" class="w-full">
                    <label class="block font-medium text-sm text-gray-700" for="leave_half">
                        Select Half <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="leave_half"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="">Select Half</option>
                        <option value="first_half">First Half</option>
                        <option value="second_half">Second Half</option>
                    </select>
                    @error('leave_half')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        


                            <!-- Reason -->
                            <div class="sm:col-span-2">
                                <label class="block font-medium text-sm text-gray-700" for="reason">
                                    Reason <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="reason" rows="4"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    placeholder="Please provide a reason for your leave request"></textarea>
                                @error('reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="temp_files" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Attachments
                                </label>

                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200">
                                    <div class="space-y-2 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="temp_files"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload files</span>
                                                <input id="temp_files" type="file" wire:model="temp_files"
                                                    class="sr-only" multiple>
                                            </label>
                                            <p class="pl-1"></p>
                                        </div>

                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, GIF, PDF, DOC, DOCX
                                        </p>
                                    </div>
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

                                <!-- Files Preview Section -->
                                @if ($temp_files && count($temp_files) > 0)
                                    <div class="mt-4 space-y-4">
                                        <div class="text-sm font-medium text-gray-700">Selected files:</div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach ($temp_files as $index => $file)
                                                <div class="relative group">
                                                    <div
                                                        class="flex items-center p-4 bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors duration-150">
                                                        @php
                                                            $mimeType = $file->getMimeType();
                                                            $extension = strtoupper($file->getClientOriginalExtension());
                                                        @endphp

                                                        @if (str_contains($mimeType, 'image'))
                                                            <!-- Image Preview -->
                                                            <div class="relative h-20 w-20 mr-3 rounded-lg overflow-hidden">
                                                                <img src="{{ $file->temporaryUrl() }}"
                                                                    class="h-full w-full object-cover" alt="Preview">
                                                            </div>
                                                        @else
                                                            <!-- Document Icon -->
                                                            <div
                                                                class="flex items-center justify-center h-20 w-20 mr-3 bg-gray-100 rounded-lg">
                                                                <svg class="h-8 w-8 text-red-500" fill="currentColor"
                                                                    viewBox="0 0 384 512">
                                                                    <path
                                                                        d="M181.9 256.1c-5-16-4.9-46.9-2-46.9 8.4 0 7.6 36.9 2 46.9zm-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7 18.3-7 39-17.2 62.9-21.9-12.7-9.6-24.9-23.4-34.5-40.8zM86.1 428.1c0 .8 13.2-5.4 34.9-40.2-6.7 6.3-29.1 24.5-34.9 40.2zM248 160h136v328c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V24C0 10.7 10.7 0 24 0h200v136c0 13.2 10.8 24 24 24zm-8 171.8c-20-12.2-33.3-29-42.7-53.8 4.5-18.5 11.6-46.6 6.2-64.2-4.7-29.4-42.4-26.5-47.8-6.8-5 18.3-.4 44.1 8.1 77-11.6 27.6-28.7 64.6-40.8 85.8-.1 0-.1.1-.2.1-27.1 13.9-73.6 44.5-54.5 68 5.6 6.9 16 10 21.5 10 17.9 0 35.7-18 61.1-61.8 25.8-8.5 54.1-19.1 79-23.2 21.7 11.8 47.1 19.5 64 19.5 29.2 0 31.2-32 19.7-43.4-13.9-13.6-54.3-9.7-73.6-7.2zM377 105L279 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9z" />
                                                                </svg>
                                                            </div>
                                                        @endif

                                                        <!-- File Info -->
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                                {{ $file->getClientOriginalName() }}
                                                            </p>
                                                            <p class="text-xs text-gray-500">
                                                                {{ number_format($file->getSize() / 1024 / 1024, 2) }} MB
                                                            </p>
                                                        </div>

                                                        <!-- Remove Button -->
                                                        <div class="ml-2 flex-shrink-0">
                                                            <button type="button" wire:click="removeFile({{ $index }})"
                                                                class="text-gray-400 hover:text-red-500 transition-colors duration-150">
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                        @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- Existing Attachments Section (when editing) -->
                    @if ($isEditing && count($existing_attachments) > 0)
                    <div class="mt-4 space-y-4">
                        <div class="text-sm font-medium text-gray-700">Existing attachments:</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($existing_attachments as $attachment)
                                <div class="relative group">
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors duration-150">
                                        @php
                                            $extension = strtoupper(pathinfo($attachment->original_file_name, PATHINFO_EXTENSION));
                                            $isImage = in_array($extension, ['JPG', 'JPEG', 'PNG', 'GIF']);
                                        @endphp

                                        @if ($isImage)
                                            <!-- Image Preview -->
                                            <div class="relative h-20 w-20 mr-3 rounded-lg overflow-hidden">
                                                <a href="#" wire:click.prevent="openPreview({{ $attachment->id }})" class="block h-full w-full">
                                                    <img src="{{ Storage::url($attachment->file_path) }}" class="h-full w-full object-cover" alt="Preview">
                                                </a>
                                            </div>
                                        @else
                                            <!-- Document Icon -->
                                            <div class="flex items-center justify-center h-20 w-20 mr-3 bg-gray-100 rounded-lg">
                                                <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 384 512">
                                                    <path d="M181.9 256.1c-5-16-4.9-46.9-2-46.9 8.4 0 7.6 36.9 2 46.9zm-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7 18.3-7 39-17.2 62.9-21.9-12.7-9.6-24.9-23.4-34.5-40.8zM86.1 428.1c0 .8 13.2-5.4 34.9-40.2-6.7 6.3-29.1 24.5-34.9 40.2zM248 160h136v328c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V24C0 10.7 10.7 0 24 0h200v136c0 13.2 10.8 24 24 24zm-8 171.8c-20-12.2-33.3-29-42.7-53.8 4.5-18.5 11.6-46.6 6.2-64.2-4.7-29.4-42.4-26.5-47.8-6.8-5 18.3-.4 44.1 8.1 77-11.6 27.6-28.7 64.6-40.8 85.8-.1 0-.1.1-.2.1-27.1 13.9-73.6 44.5-54.5 68 5.6 6.9 16 10 21.5 10 17.9 0 35.7-18 61.1-61.8 25.8-8.5 54.1-19.1 79-23.2 21.7 11.8 47.1 19.5 64 19.5 29.2 0 31.2-32 19.7-43.4-13.9-13.6-54.3-9.7-73.6-7.2zM377 105L279 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9z" />
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- File Info -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $attachment->original_file_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ number_format($attachment->file_size / 1024 / 1024, 2) }} MB
                                            </p>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="ml-2 flex-shrink-0 flex space-x-2">
                                            <button type="button" wire:click="deleteAttachment('{{ $attachment->uuid }}')"
                                                class="text-gray-400 hover:text-red-500 transition-colors duration-150" title="Delete">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    </div>

                    <div
                        class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                        <button type="submit"
                            class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                            <div wire:loading wire:target="saveLeave" class="mr-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                            {{ $isEditing ? 'Update Leave Request' : 'Submit Leave Request' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>