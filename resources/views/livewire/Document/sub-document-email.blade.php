<div
    x-data="{ 
        open: false,
        selectAll: false,
        toggleAll() {
            this.selectAll = !this.selectAll;
            
            this.$wire.toggleAllAttachments(this.selectAll);
        }
    }"
    x-show="open"
    x-init="
        window.addEventListener('open-email-modal', () => { open = true });
        window.addEventListener('close-email-modal', () => { open = false });
    "
    @keydown.escape.window="open = false"
    class="pt-20 overflow-y-auto fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    style="backdrop-filter: blur(2px); display: none;"
>
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        @click.away="open = false"
        class="bg-white rounded-lg shadow-xl border border-gray-200 w-full max-w-xl m-4"
    >
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-lg flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Send Attachments: {{ $selectedDocumentTitle }}
            </h3>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 p-4 rounded-lg mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
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
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="sendAttachments">
            <div class="p-6">
                <!-- Attachment Selection -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-700">Select Attachments</label>
                        <button 
                            type="button" 
                            @click="toggleAll()"
                            class="text-xs text-blue-600 hover:text-blue-800"
                        >
                            <span x-text="selectAll ? 'Deselect All' : 'Select All'"></span>
                        </button>
                    </div>
                    
                    <div class="border border-gray-200 rounded-md max-h-60 overflow-y-auto">
                        <ul class="divide-y divide-gray-200">
                            @if($document_attachments!=[])
                            @forelse($document_attachments as $index => $attachment)
                            <li class="p-2 hover:bg-gray-50">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="attachment_{{ $attachment->uuid }}"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        wire:model="selectedAttachments"
                                        value="{{ $attachment->uuid }}"
                                    >
                                    <div class="flex-1 min-w-0">
                                        <span class="text-sm text-gray-700 truncate block">{{ $attachment->original_file_name }}</span>
                                        <span class="text-xs text-gray-500">{{ number_format($attachment->file_size / 1024, 2) }} KB</span>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @if(Str::endsWith(strtolower($attachment->original_file_name), ['.jpg', '.jpeg', '.png', '.gif']))
                                            <span class="text-xs text-blue-500">Image</span>
                                        @elseif(Str::endsWith(strtolower($attachment->original_file_name), ['.pdf']))
                                            <span class="text-xs text-red-500">PDF</span>
                                        @elseif(Str::endsWith(strtolower($attachment->original_file_name), ['.docx', '.doc']))
                                            <span class="text-xs text-indigo-500">Document</span>
                                        @else
                                            <span class="text-xs text-gray-500">File</span>
                                        @endif
                                    </div>
                                </label>
                            </li>
                           
                            @empty
                            <li class="p-3 text-sm text-gray-500 text-center">No attachments available</li>
                            @endforelse
                            @endif
                        </ul>
                    </div>
                    @error('selectedAttachments') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="emailAddress" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="emailAddress" wire:model.defer="emailAddress" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="recipient@example.com" required>
                    @error('emailAddress') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email Subject -->
                <div class="mb-4">
                    <label for="emailSubject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" id="emailSubject" wire:model.defer="emailSubject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Document Attachments" required>
                    @error('emailSubject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email Message -->
                <div class="mb-2">
                    <label for="emailMessage" class="block text-sm font-medium text-gray-700 mb-1">Message (Optional)</label>
                    <textarea id="emailMessage" wire:model.defer="emailMessage" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Additional message..."></textarea>
                    @error('emailMessage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Info text -->
                <div class="text-xs text-gray-500">
                    Selected attachments will be sent to the email address above.
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-lg border-t border-gray-200 flex justify-end space-x-3">
                <button 
                    type="button" 
                    @click="open = false" 
                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0"
                >
                    Cancel
                </button>
                <button type="submit" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0"
                wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="sendAttachments">Send Attachments</span>
                    <span wire:loading wire:target="sendAttachments">Sending...</span>
                </button>
            </div>
        </form>
    </div>
</div>