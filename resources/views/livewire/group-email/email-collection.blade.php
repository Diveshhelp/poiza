<div>
    <div class="mx-auto py-5 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-medium text-gray-900">Customer Email Manager</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Send formatted emails to your customers
                </p>
            </div>
        </div>

        <!-- Alerts -->
        @if (session()->has('message'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Tabs -->
        <div class="mt-4">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button wire:click="setActiveTab('compose')" class="py-2 px-4 text-sm font-medium {{ $activeTab === 'compose' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Compose Email
                    </button>
                    <button wire:click="setActiveTab('customers')" class="ml-8 py-2 px-4 text-sm font-medium {{ $activeTab === 'customers' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Select Customers
                    </button>
                    <button wire:click="setActiveTab('logs')" class="ml-8 py-2 px-4 text-sm font-medium {{ $activeTab === 'logs' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Email Logs
                    </button>
                </nav>
            </div>

            <!-- Compose Email Tab -->
            <div class="{{ $activeTab === 'compose' ? '' : 'hidden' }} mt-4 bg-white shadow rounded-lg p-6">
                <form wire:submit.prevent="sendEmails">
                    <!-- Two-column layout: Editor and Preview -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left column: Email Editor -->
                        <div class="space-y-4">
                            <div>
                                <label for="selectedTemplate" class="block text-sm font-medium text-gray-700">Email Template</label>
                                <select wire:model.live="selectedTemplate" id="selectedTemplate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Select a template</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                                <input type="text" wire:model.live="subject" id="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('subject') border-red-300 @enderror" placeholder="Email subject">
                                @error('subject') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="emailContent" class="block text-sm font-medium text-gray-700">Email Content</label>
                                <div class="mt-1">
                                    <div wire:ignore>
                                        <textarea id="emailContent" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('emailContent') border-red-300 @enderror" rows="16">{{ $emailContent }}</textarea>
                                    </div>
                                    @error('emailContent') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <p class="mt-2 text-xs text-gray-500">You can use placeholders like {name}, {email}, {id} in your email.</p>
                            </div>
                        </div>
                        
                        <!-- Right column: Email Preview -->
                            <div class="sticky top-4">
                                <div class="border border-gray-300 rounded-lg bg-gray-50 h-full">
                                    <div class="p-3 border-b border-gray-200 bg-gray-100">
                                        <h3 class="text-sm font-medium text-gray-700">Email Preview</h3>
                                    </div>
                                    
                                    <div class="rounded-md border border-gray-200 m-3 bg-white overflow-hidden">
                                        <!-- Email Header -->
                                        <div class="border-b border-gray-200 px-4 py-3">
                                            <div class="mb-1">
                                                <span class="text-xs font-medium text-gray-500">From:</span>
                                                <span class="text-xs text-gray-800 ml-1">{{ auth()->user()->name }} &lt;{{ auth()->user()->email }}&gt;</span>
                                            </div>
                                            <div class="mb-1">
                                                <span class="text-xs font-medium text-gray-500">To:</span>
                                                <span class="text-xs text-gray-800 ml-1">[Recipient]</span>
                                            </div>
                                            <div>
                                                <span class="text-xs font-medium text-gray-500">Subject:</span>
                                                <span class="text-xs font-medium text-gray-800 ml-1">{{ $subject ?: 'No subject' }}</span>
                                            </div>
                                        </div>
                                        
                                     <!-- Email Preview Section -->
                                    <div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
                                        <!-- Preview Header -->
                                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 flex justify-between items-center">
                                            <h3 class="text-sm font-medium text-gray-700">Email Preview</h3>
                                        </div>
                                        
                                        <!-- Email Body Preview -->
                                        <div class="p-4 text-sm text-gray-800 overflow-y-auto bg-gray-50" style="max-height: 500px;">
                                            <!-- Email Template -->
                                            <div class="bg-white rounded-lg shadow-sm mx-auto" style="max-width: 550px;">
                                                <!-- Email Header -->
                                                <div style="text-align: center; padding: 20px; border-bottom: 1px solid #eee;">
                                                    <img src="{{ asset('DOCMEY_LOGO.png') }}" alt="Docmey Logo" 
                                                        style="max-width: 120px; margin: 0 auto 15px auto; display: block;">
                                                    <h2 style="color: #0F8389; margin: 0; font-size: 18px;">{{ $subject }}</h2>
                                                </div>
                                                
                                                <!-- Email Content Area -->
                                                <div style="padding: 20px;">
                                                    <div wire:ignore id="previewContent" class="prose prose-sm max-w-none">
                                                        {!! $emailContent ? nl2br(e($emailContent)) : '<span class="text-gray-400 italic">Email body preview will appear here as you type...</span>' !!}
                                                    </div>
                                                </div>
                                                
                                                <!-- Email Footer -->
                                                <div style="font-size: 12px; color: #777; text-align: center; margin-top: 20px; padding: 15px; border-top: 1px solid #eee; background-color: #f9f9f9;">
                                                    <p style="margin-bottom: 10px;">© {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.</p>
                                                    <div style="margin-bottom: 10px;">
                                                        <a href="tel:+919429895795" style="color: #0F8389; text-decoration: none;">{{ env('MOBILE') }}</a> |
                                                        <a href="mailto:{{ env('ADMIN_EMAIL') }}" style="color: #0F8389; text-decoration: none;">{{ env('ADMIN_EMAIL') }}</a> |
                                                        <a href="mailto:{{ env('SUPPORT_EMAIL') }}" style="color: #0F8389; text-decoration: none;">{{ env('SUPPORT_EMAIL') }}</a>
                                                    </div>
                                                    <p style="margin-bottom: 10px; font-size: 11px;">
                                                        You are receiving this email because you are a customer of Docmey.
                                                        If you prefer not to receive these emails, you can
                                                        <a href="#" style="color: #0F8389; text-decoration: none;">unsubscribe</a>.
                                                    </p>
                                                    <p style="margin-bottom: 0; font-size: 11px;">
                                                        {{ env('APP_NAME') }}, {{ env('ADDRESS') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Preview Actions -->
                                        <div class="border-t border-gray-200 bg-gray-50 px-4 py-3 flex justify-between">
                                            <div class="text-xs text-gray-500">
                                                <span class="font-medium">Note:</span> This is a preview. Actual email may appear differently in some email clients.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-3 pb-3 text-xs text-gray-500">
                                        <p class="mb-1">Placeholders will be replaced with recipient data when sent.</p>
                                        <p>Example: <span class="font-medium">[Recipient]</span> → user@email.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons (below the two columns) -->
                    <div class="pt-6 flex justify-between">
                        <button type="button" wire:click="setActiveTab('customers')" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Select Recipients
                        </button>
                        
                        <div class="flex space-x-2">
                            <button type="button" wire:click="openTemplateModal" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                Save as Template
                            </button>
                            
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <span wire:loading.remove wire:target="sendEmails">Send Emails</span>
                                <span wire:loading wire:target="sendEmails" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Customers Tab -->
            <div class="{{ $activeTab === 'customers' ? '' : 'hidden' }} mt-4 bg-white shadow rounded-lg p-6">
                <!-- Customer Filters -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Customers</label>
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <input type="text" wire:model.live="filterEmail" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Filter by email">
                        </div>
                        <button wire:click="resetFilters" class="px-3 py-1 text-xs text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Customer Selection -->
                <div class="mt-4">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" wire:model.live="selectAll" id="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="selectAll" class="ml-2 text-sm font-medium text-gray-700">Select All Customers</label>
                    </div>

                    <div class="border border-gray-200 rounded-md overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-tight">Select</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-tight">Name</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-tight">Email</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($customers as $customer)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-3 py-2">
                                            <input type="checkbox" wire:model.live="selectedCustomers" value="{{ $customer->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        </td>
                                        <td class="px-3 py-2 text-xs text-gray-900">
                                            {{ $customer->name }}
                                        </td>
                                        <td class="px-3 py-2 text-xs text-gray-900">
                                            {{ $customer->email }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-2 text-center text-xs text-gray-500">
                                            No customers found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                    
                    <div class="mt-4 flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Selected {{ count($selectedCustomers) }} customer(s)
                        </div>
                        <button 
                            wire:click="setActiveTab('compose')"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            Continue to Compose
                        </button>
                    </div>
                </div>
            </div>

            <!-- Email Logs Tab -->
            <div class="{{ $activeTab === 'logs' ? '' : 'hidden' }} mt-4">
                <!-- Filters -->
                <div x-data="{ isFilterOpen: false }" class="mb-4">
                    <!-- Filter Toggle Button -->
                    <button @click="isFilterOpen = !isFilterOpen" type="button"
                        class="w-full bg-white shadow rounded-lg px-4 py-3 text-left text-sm font-medium text-gray-700 flex justify-between items-center">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filters
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-300"
                            :class="isFilterOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Filter Content -->
                    <div x-show="isFilterOpen" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4" class="mt-2 bg-white shadow rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" wire:model.live="filterStartDate"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" wire:model.live="filterEndDate"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select wire:model.live="filterStatus"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">All</option>
                                    <option value="queued">Queued</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button wire:click="resetFilters"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                                Reset Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Email Logs Table -->
                <div class="bg-white shadow overflow-hidden rounded-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-tight">Date</th>
                                <th scope="col"
                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-tight">Subject</th>
                                <th scope="col"
                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-tight">Recipients</th>
                                <th scope="col"
                                    class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-tight">Status</th>
                                <th scope="col"
                                    class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-tight">Success/Failure</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($emailLogs as $log)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                        {{ $log->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-900 truncate max-w-xs">
                                        {{ $log->subject }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                        {{ $log->recipients_count }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-medium rounded-full 
                                            {{ $log->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $log->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $log->status === 'queued' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $log->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-right text-xs">
                                        @if($log->status === 'completed')
                                            <span class="text-green-600">{{ $log->success_count }}</span>
                                            /
                                            <span class="text-red-600">{{ $log->failure_count }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-2 text-center text-xs text-gray-500">
                                        No email logs found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-3 py-2 border-t border-gray-200">
                        {{ $emailLogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center {{ $showTemplateModal ? '' : 'hidden' }}">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Save as Template</h3>
                <button wire:click="closeTemplateModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label for="templateName" class="block text-sm font-medium text-gray-700">Template Name</label>
                    <input type="text" wire:model="templateName" id="templateName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('templateName') border-red-300 @enderror" placeholder="Enter a name for this template">
                    @error('templateName') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                
                <div class="pt-4 flex justify-end space-x-3">
                    <button wire:click="closeTemplateModal" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Cancel
                    </button>
                    <button wire:click="saveAsTemplate" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span wire:loading.remove wire:target="saveAsTemplate">Save Template</span>
                        <span wire:loading wire:target="saveAsTemplate" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.tiny.cloud/1/2n7v9yzmady0hg09mxxa3ry7yxy0u32bnxygic4qrx9c06az/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
         // Function to update preview content
         function updatePreview(content) {
            const previewContent = document.getElementById('previewContent');
            if (previewContent) {
                // Replace placeholders with sample data for preview
                let previewText = content
                    .replace(/{name}/g, 'John Doe')
                    .replace(/{email}/g, 'john.doe@example.com')
                    .replace(/{id}/g, '12345');
                
                // For plain text, use nl2br equivalent
                if (!content.includes('<')) {
                    previewText = previewText.replace(/\n/g, '<br>');
                }
                
                previewContent.innerHTML = previewText || '<span class="text-gray-400">Email body preview will appear here...</span>';
            }
        }

        let editor;
        
        document.addEventListener('livewire:initialized', () => {
            // Initialize TinyMCE for rich text editing
            tinymce.init({
                selector: '#emailContent',
                plugins: [
                    "advlist",
                    "anchor",
                    "autolink",
                    "charmap",
                    "code",
                    "fullscreen",

                    "help",
                    "image",
                    "insertdatetime",
                    "link",
                    "lists",
                    "media",

                    "preview",
                    "searchreplace",
                    "table",
                    "visualblocks",
                ],

                toolbar:"code| undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",
                height: 900,
                setup: function (ed) {
                    editor = ed;
                    
                    // On editor initialization
                    editor.on('init', function () {
                        // Set editor content to Livewire model value
                        editor.setContent(@this.emailContent || '');
                    });
                    
                    // Update Livewire model on content change
                    editor.on('change', function () {
                        @this.set('emailContent', editor.getContent());
                        updatePreview(editor.getContent());
                    });
                    
                    // Make sure content is synced before form submission
                    editor.on('submit', function() {
                        @this.set('emailContent', editor.getContent());
                    });
                    
                    // Also update on blur (when editor loses focus)
                    editor.on('blur', function() {
                        @this.set('emailContent', editor.getContent());
                    });
                }
            });
            
            // Listen for refreshEditor event from Livewire component
            @this.on('refreshEditor', function() {
                if (editor && editor.initialized) {
                    console.log('Refreshing editor with content:', @this.emailContent);
                    editor.setContent(@this.emailContent || '');
                } else {
                    console.log('Editor not ready, will try again in 100ms');
                    // If editor isn't ready yet, try again after a short delay
                    setTimeout(() => {
                        if (tinymce.activeEditor) {
                            tinymce.activeEditor.setContent(@this.emailContent || '');
                            console.log('Editor refreshed after delay');
                        } else {
                            console.log('Editor still not available after delay');
                        }
                    }, 100);
                }
            });
            
            // Listen for syncEditor event from Livewire component
            @this.on('syncEditor', function() {
                if (tinymce.activeEditor) {
                    @this.set('emailContent', tinymce.activeEditor.getContent());
                }
            });
        });
    </script>
    <script>
    document.addEventListener('livewire:init', function () {
        // For TinyMCE (if you're using it)
        if (typeof tinymce !== 'undefined') {
            Livewire.hook('element.initialized', ({ component, element }) => {
                if (element.id === 'emailContent') {
                    tinymce.init({
                        selector: '#emailContent',
                        // Your TinyMCE config here
                        setup: function(editor) {
                            editor.on('change', function(e) {
                                // Update Livewire model
                                @this.set('emailContent', editor.getContent());
                                
                                // Update preview
                                updatePreview(editor.getContent());
                            });
                        }
                    });
                }
            });
        } else {
            // For regular textarea
            const emailContent = document.getElementById('emailContent');
            if (emailContent) {
                emailContent.addEventListener('input', function() {
                    // Update Livewire model
                    @this.set('emailContent', this.value);
                    
                    // Update preview
                    updatePreview(this.value);
                });
            }
        }
        
        // For subject field
        const subjectField = document.getElementById('subject');
        if (subjectField) {
            subjectField.addEventListener('input', function() {
               
            });
        }
       
    });
</script>
</div>