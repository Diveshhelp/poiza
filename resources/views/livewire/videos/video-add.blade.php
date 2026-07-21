<div>
    <div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8" wire:key="photo-manager-module-{{ time() }}">
        <div>
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $isUpdate ? 'Edit Video' : 'Add New Video' }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        {{ $isUpdate ? 'Update Your video' : 'Add Your video' }}
                    </p>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('my-videos') }}"
                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mr-3 size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        Back to Video
                    </a>
                </div>
            </div>

            <div class="mt-5 md:col-span-2">
              <div>
                <form wire:submit="savePhoto">
                    @include('livewire.common.messages')
                        <div class="mt-2 px-3 py-3 bg-white sm:p-4 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            <div class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <label for="album_title" class="block font-medium text-sm text-gray-700">
                                        Album Title <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" autofocus wire:model="album_title" id="album_title"
                                            placeholder="Enter album title"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('title') ring-red-300 @enderror">
                                    </div>
                                    @error('album_title')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Priority -->
                                <div class="sm:col-span-1">
                                    <label class="block font-medium text-sm text-gray-700" for="priority">Type</label>
                                    <select wire:model="type"
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="low">Public</option>
                                        <option value="medium">Private</option>
                                        <option value="high">Confidential</option>
                                    </select>
                                    <div class="text-red-500">
                                        @error('priority')
                                            <span class="text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- File Upload Section -->
                                <div class="sm:col-span-2">
                                    <label for="mediaFiles" class="block text-sm font-medium text-gray-700 mb-1">
                                        Upload Attachments
                                    </label>

                                    <div class="mt-1 flex justify-center px-4 pt-3 pb-3 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>

                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="mediaFiles"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload files</span>
                                                    <input id="mediaFiles" type="file" wire:model="mediaFiles" accept="video/*" class="sr-only" multiple>
                                                </label>
                                            </div>

                                            <p class="text-xs text-gray-500">Any video file!</p>
                                        </div>
                                    </div>

                                    <!-- Loading State -->
                                    <div wire:loading wire:target="mediaFiles" class="mt-1">
                                        <div class="inline-flex items-center text-xs text-blue-600">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Processing files...
                                        </div>
                                    </div>

                                    <!-- Error Messages -->
                                      @error('mediaFiles')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror

                                    <!-- Files Preview Section -->
                                    @if ($mediaFiles)
                                        <div class="mt-2 space-y-2">
                                            <div class="text-xs font-medium text-gray-700">Selected files:</div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                                @foreach ($mediaFiles as $index => $file)
                                                    <div class="relative group">
                                                        <div class="flex items-center p-2 bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors duration-150">
                                                            @if (str_contains($file->getMimeType(), 'image'))
                                                                <!-- Image Preview -->
                                                                <div class="relative h-12 w-12 mr-2 rounded-lg overflow-hidden">
                                                                    <img src="{{ $file->temporaryUrl() }}" class="h-full w-full object-cover" alt="Preview">
                                                                </div>
                                                            @else
                                                                <!-- Document Icon -->
                                                                <div class="flex items-center justify-center h-12 w-12 mr-2 bg-gray-200 rounded-lg">
                                                                    <span class="text-xs font-medium text-gray-600">
                                                                        {{ strtoupper($file->getClientOriginalExtension()) }}
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-xs font-medium text-gray-900 truncate">
                                                                    {{ $file->getClientOriginalName() }}
                                                                </p>
                                                                <p class="text-xs text-gray-500">
                                                                    {{ number_format($file->getSize() / 1024 / 1024, 2) }} MB
                                                                </p>
                                                            </div>

                                                            <!-- Remove Button -->
                                                            <button type="button" wire:click="removeFile({{ $index }})"
                                                                class="ml-1 flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors duration-150">
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if ($isUpdate && count($existingMediaFiles) > 0)
                                        <div class="mt-2 space-y-2">
                                            <div class="text-xs font-medium text-gray-700">Existing files:</div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                                @foreach ($existingMediaFiles as $file)
                                                    <div class="relative group">
                                                        <div class="flex items-center p-2 bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors duration-150">
                                                            <div class="flex items-center justify-center h-12 w-12 mr-2 bg-gray-200 rounded-lg">
                                                                <span class="text-xs font-medium text-gray-600">
                                                                    {{ strtoupper($file->file_type) }}
                                                                </span>
                                                            </div>

                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-xs font-medium text-gray-900 truncate">
                                                                    {{ $file->file_name }}
                                                                </p>
                                                                <p class="text-xs text-gray-500">
                                                                    {{ number_format($file->file_size / 1024 / 1024, 2) }} MB
                                                                </p>
                                                            </div>

                                                            <button type="button" wire:click="removeExistingFile({{ $file->id }})"
                                                                class="ml-1 flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors duration-150">
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                            </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end px-3 py-2 bg-gray-50 text-end sm:px-4 shadow sm:rounded-bl-md sm:rounded-br-md">
                            <button type="submit"
                                class="px-3 py-1.5 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                <span class="indicator-label" wire:loading.remove wire:target="savePhoto">
                                    {{ $isUpdate ? 'Update Videos' : 'Save Videos' }}
                                </span>
                                <span class="indicator-progress" wire:loading wire:target="savePhoto">
                                    <svg class="w-4 h-4 mr-2 ml-2 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
