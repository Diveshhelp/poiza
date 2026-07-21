<div>
    <div  class="mx-auto py-2 sm:px-6 lg:px-8" >
        <!-- Header Section -->
        <div class="flex flex-wrap items-center justify-between mb-6" >
            <div class="min-w-0 flex-1 pr-2">
                <h3 class="text-base sm:text-lg font-medium text-gray-900">{{ $moduleTitle }}</h3>
                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600 leading-tight sm:leading-relaxed">
                    Manage sub-documents and their attachments
                </p>
            </div>
           
        <!-- Right side actions group -->
        <div class="flex items-center gap-3">
            <!-- Search Input -->
            <div class="relative w-64">
                <input type="text" id="filterDocTitle" wire:model.live.debounce.300ms="filterDocTitle" 
                    class="h-10 w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm pl-3 pr-8" 
                    placeholder="Search your doc...">
                <div wire:loading wire:target="filterDocTitle" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <!-- Expiring Filter Toggle -->
            <button title="Filter by expire date in 30 days from now" wire:click="toggleExpiringFilter" wire:loading.attr="disabled" type="button" 
                class="h-10 flex items-center justify-center px-4 text-white text-sm font-medium transition-colors duration-200 rounded-lg shadow-sm shrink-0
                {{ $showExpiringOnly ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-500 hover:bg-blue-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                @if($expiringCount > 0 && $showExpiringOnly)
                    Clear Filter
                    <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $expiringCount }}
                    </span>
                @else
                    Expiring
                @endif
            </button>

            <!-- Selection Actions (conditionally shown) -->
            @if(count($alreadySelectedDocs) > 0)
                <!-- Clear Selection Button -->
                <button wire:click="clearSelection" wire:loading.attr="disabled" type="button" class="h-10 flex items-center justify-center px-4 text-white text-sm font-medium bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200 rounded-lg shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear
                    <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ count($alreadySelectedDocs) }}
                    </span>
                </button>
                
                <!-- Send Document Button -->
                <a href="{{ route('document-sender') }}" class="h-10 flex items-center justify-center px-4 text-white text-sm font-medium bg-primary hover:bg-secondary transition-colors duration-200 rounded-lg shadow-sm">
                    Send Doc
                </a>
            @endif

            <!-- Add Sub Doc Button -->
            <button type="button" @click="$dispatch('open-modal')" 
                class="h-10 flex items-center justify-center px-4 text-white text-sm font-medium bg-primary hover:bg-secondary transition-colors duration-200 rounded-lg shadow-sm shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Sub Doc
            </button>

            <!-- Email Selected Button -->
            <div x-data="{ selectedCount() { return $wire.masterSelection.length; } }" class="shrink-0">
                <button wire:loading.class="opacity-50 cursor-wait" wire:click="openBulkSendAttachmentsModal()" 
                    x-bind:disabled="selectedCount() === 0" 
                    x-bind:class="selectedCount() > 0 ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-300 cursor-not-allowed'" 
                    class="h-10 flex items-center justify-center px-4 text-white text-sm font-medium transition-colors duration-200 rounded-lg shadow-sm whitespace-nowrap">
                    Email (<span x-text="selectedCount()"></span>)
                    <span class="inline-flex" wire:loading wire:target="openBulkSendAttachmentsModal">
                        <svg class="animate-spin ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>

            <!-- Back Button -->
            <a href="{{ route('document-collections') }}" class="h-10 flex items-center justify-center px-4 text-white text-sm font-medium bg-gray-600 hover:bg-gray-700 transition-colors duration-200 rounded-lg shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"></path>
                </svg>
            </a>
        </div>
        </div>
        <!-- Documents Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="hidden md:table min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left">
                        <input 
                            type="checkbox" 
                            wire:model="selectallbutton"
                            wire:click="selectAllRecords()"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            title="Select All">
                        </th>
                        <th wire:click="sortBy('doc_title')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Title
                            @if ($sortField === 'doc_title')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('doc_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Doc Number @if ($sortField === 'doc_number')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif</th>
                        <th wire:click="sortBy('doc_update_date')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">Doc Date  @if ($sortField === 'doc_update_date')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doc Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attachments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($documents as $document)
                        <tr>
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

                                <input   wire:loading.remove wire:target="selectAllRecords"
                                    wire:change="toggleSelection({{ $document->id }}, $event.target.checked)"
                                    value='{{ $document->id }}'
                                    type="checkbox" 
                                    wire:model="masterSelection"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                             </td>
                            <td class="px-6 py-4">
                              
                                <div class="text-sm font-medium text-gray-900">{{ $document->doc_title }}</div>
                            </td>
                            <td class="px-6 py-4 ">
                                <div class="text-sm text-gray-900">{{ $document->doc_number }}</div>
                            </td>
                            <td class="px-6 py-4 ">
                                @if($document->doc_update_date!=null)
                                    <div class="text-sm text-gray-900">{{ date('d-m-Y',strtotime($document->doc_update_date)) }}</div>
                                @endif
                            </td>

                            <td class="px-6 py-4 ">
                                <div class="text-sm text-gray-900">{{ $document->doc_year }}</div>
                            </td>
                            <td class="px-4 py-3 " >
                                <div class="text-sm text-gray-900">
                                    {{ $doc_validity_list[$document->doc_validity] ?? 'Unknown' }}
                                </div>
                                <div class="text-sm text-gray-900">
                                @if($document->doc_renewal_dt!=NULL)
                                    Renew:{{ date('d-m-Y',strtotime($document->doc_renewal_dt)) }}
                                    
                                    <?php
                                        $expireDate = \Carbon\Carbon::parse($document->doc_renewal_dt);
                                        $today = \Carbon\Carbon::today();
                                        $daysRemaining = $today->diffInDays($expireDate, false);
                                        
                                        $textClass = 'text-gray-600';
                                        if ($daysRemaining <= 0) {
                                            $textClass = 'text-red-600';
                                        } else if ($daysRemaining <= 7) {
                                            $textClass = 'text-red-500';
                                        } else if ($daysRemaining <= 30) {
                                            $textClass = 'text-orange-500';
                                        }
                                    ?>
                                    
                                    <div class="text-xs mt-1 {{ $textClass }}">
                                        @if($daysRemaining > 0)
                                            {{ $daysRemaining }} days remaining
                                        @elseif($daysRemaining == 0)
                                            Expires today!
                                        @else
                                            Expired {{ abs($daysRemaining) }} days ago
                                        @endif
                                    </div>
                                @endif
                                </div>
                            </td>
                            
                            {{-- Attachments File --}}
                            <td class="px-6 py-4 ">
                                
                                <div x-data="{ 
                                    showModal: false,
                                    currentFile: null,
                                    previewFile(file) {
                                        this.currentFile = file;
                                        this.showModal = true;
                                    },
                                    selectedFiles: [],
                                    isSelected(uuid) {
                                    },
                                    toggleFile(uuid) {
                                       
                                    }
                                }">
                                    <div class="flex flex-col space-y-2">
                                        @forelse($document->attachments as $attachment)
                                          <!-- File Attachment Component -->
                                            <div class="flex items-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-all duration-200 group shadow-sm hover:shadow">
                                            <!-- File Info -->
                                            <div class="flex-grow min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate pl-2" title="{{ $attachment->original_file_name }}">
                                                {{ $attachment->original_file_name }}
                                                </p>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex-shrink-0 ml-2">
                                                <div class="flex space-x-2">
                                                <!-- Preview Button -->
                                                <button 
                                                    @click="previewFile({
                                                    name: '{{ $attachment->original_file_name }}',
                                                    url: '/public{{ Storage::url($attachment->file_path) }}'
                                                    })" 
                                                    class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                
                                                <!-- Download Button -->
                                                <a 
                                                    href="/public{{ Storage::url($attachment->file_path) }}"
                                                    download="{{ $attachment->original_file_name }}"
                                                    class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                </a>
                                                </div>
                                            </div>
                                            </div>
                                        @empty
                                            <span class="text-xs text-gray-500">No attachments</span>
                                        @endforelse
                                    </div>

                                    <!-- Modal -->
                                    <template x-if="showModal">
                                        <div class="fixed inset-0 z-50 overflow-y-auto" 
                                            @click.self="showModal = false"
                                            @keydown.escape.window="showModal = false">
                                            <div class="flex items-center justify-center min-h-screen p-4">
                                                <div class="fixed inset-0 transition-opacity">
                                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                </div>

                                                <div class="relative bg-white rounded-lg max-w-3xl w-full mx-auto shadow-xl">
                                                    <!-- Modal Header -->
                                                    <div class="flex items-center justify-between px-4 py-3 border-b">
                                                        <h3 class="text-lg font-medium text-gray-900" x-text="currentFile?.name"></h3>
                                                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="p-4">
                                                        <div class="aspect-w-16 aspect-h-9">
                                                            <iframe :src="currentFile?.url" 
                                                                class="w-full h-full"
                                                                frameborder="0">
                                                            </iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </td>
                                                        
                            <td class="px-2 py-1 text-right">
                                <div class="grid grid-cols-2 gap-1">
                                    <!-- Row 1: Email & History -->
                                    <div wire:click="openSendAttachmentsModal('{{ $document->uuid }}')" title="Send Email" class="p-1 bg-gray-100 rounded hover:bg-blue-100 cursor-pointer group">
                                    <svg class="w-3.5 h-3.5 text-gray-500 group-hover:text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    </div>
                                    <div wire:click="viewSentEmails('{{ $document->uuid }}')" title="View Email History" class="p-1 bg-gray-100 rounded hover:bg-indigo-100 cursor-pointer group">
                                    <svg class="w-3.5 h-3.5 text-gray-500 group-hover:text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    </div>
                                    
                                    <!-- Row 2: Edit & Delete -->
                                    <button
                                    wire:key="edit-{{ $document->uuid }}"
                                    wire:click="setValidaity('{{ $document->doc_validity}}')"
                                    x-data="{ attachments: JSON.parse('{{ addslashes(json_encode($document->attachments->map(function($attachment) { return ['uuid' => $attachment->uuid, 'original_file_name' => $attachment->original_file_name, 'file_size' => $attachment->file_size, 'file_path' => $attachment->file_path]; }))) }}') }"
                                    x-on:click="$dispatch('edit-modal', {title: '{{ addslashes($document->doc_title) }}', doc_number: '{{ addslashes($document->doc_number) }}', doc_update_date: '{{ $document->doc_update_date }}', validity: '{{ addslashes($document->doc_validity) }}', doc_info: '{{ addslashes($document->doc_info) }}', doc_year: '{{ addslashes($document->doc_year) }}', uuid: '{{ $document->uuid }}', doc_expire_date: '{{ $document->doc_expire_date }}', doc_renewal_dt: '{{ $document->doc_renewal_dt }}',doc_validity: '{{ $document->doc_validity}}', attachments: attachments})" 
                                    class="p-1 bg-gray-100 rounded hover:bg-blue-100 cursor-pointer group"
                                    wire:loading.class="opacity-50 cursor-wait"
                                    wire:target="updateSubDocument">
                                    <svg class="w-3.5 h-3.5 text-gray-500 group-hover:text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    </button>
                                    <button
                                    wire:click="deleteDocument('{{ $document->uuid }}')"
                                    wire:loading.attr="disabled"
                                    wire:confirm="Are you sure you want to delete this sub document?"
                                    class="p-1 bg-gray-100 rounded hover:bg-blue-100 cursor-pointer group">
                                    <svg class="w-3.5 h-3.5 text-gray-500 group-hover:text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    </button>
                                </div>
                                </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4  text-sm text-gray-500 text-center">
                                No sub documents found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
        </div>
        <!-- Pagination -->
        <div class="mt-4">
            {{ $documents->links() }}
        </div>

        <div x-data="subDocumentModal()"
                x-init="
                    window.addEventListener('open-modal', () => {
                        show = true;
                        isEditing = false;
                        resetForm();
                    });
                    window.addEventListener('edit-modal', (event) => {
                        show = true;
                        isEditing = true;
                        formData = {
                            title: event.detail.title,
                            doc_number: event.detail.doc_number,
                            doc_update_date: event.detail.doc_update_date,
                            doc_expire_date: event.detail.doc_expire_date,
                            validity: event.detail.validity,
                            doc_info: event.detail.doc_info,
                            doc_year: event.detail.doc_year,
                            uuid: event.detail.uuid,
                            doc_validity:event.detail.doc_validity,
                            doc_renewal_dt:event.detail.doc_renewal_dt
                            
                        };
                        // Set existing files
                        existingFiles = event.detail.attachments || [];
                    });
                "
                x-show="show"
                class="fixed inset-0 z-50 overflow-y-auto"
                x-cloak>    
                
                <!-- Modal Backdrop -->
                <div class="fixed inset-0 transition-opacity" x-show="show">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- Modal Content -->
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="relative bg-white rounded-lg max-w-4xl w-full mx-auto shadow-xl" 
                        @click.away="closeModal()">
                        
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b">
                                                       
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">
                                {{ $parentDocument->doc_categories->category_title }} > <span class="font-medium text-gray-700">{{ $parentDocument->doc_title }}</span>
                                </h3>
                            </div>
                            <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            
                        </div>

                        <!-- Modal Body -->
                        <div class="px-6 py-4">
                            <form @submit.prevent="submitForm();" >
                                <!-- existing form fields here -->
                                <div class="space-y-6">
                                    <!-- Three column layout with improved spacing -->
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4" x-data="{
                                            validityValue: @entangle('doc_validity'),
                                            updateRenewalDate() {
                                                if (this.validityValue == 1) {
                                                    $wire.doc_renewal_dt = 'NO';
                                                } else {
                                                    $wire.doc_renewal_dt = null;
                                                }
                                            }
                                        }">
                                        <div>
                                            <label for="title" class="block text-sm font-medium text-gray-700">Document Title <span class="text-red-500">*</span></label>
                                            <input type="text" id="title" x-model="formData.title"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required>
                                        </div>

                                        <div>
                                            <label for="doc_number" class="block text-sm font-medium text-gray-700">Document Number </label>
                                            <input type="text" id="doc_number" x-model="formData.doc_number"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                >
                                        </div>
                                        <!-- Doc Validity -->
                                        <div>
                                            <label class="block font-medium text-sm text-gray-700" for="doc_validity">
                                            Doc Validity <span class="text-red-500">*</span>
                                            </label>
                                            <select
                                            x-model="formData.validity"
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
                                            <input type="date"
                                                value="NO"
                                                disabled
                                                class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 bg-gray-100 sm:text-sm sm:leading-6">
                                            </template>
                                            <template x-if="validityValue != 1">
                                            <input type="date"
                                             x-model="formData.doc_renewal_dt"
                                                placeholder="DD-MM-YYYY"
                                                wire:model="doc_renewal_dt"
                                                class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </template>
                                            @error('doc_renewal_dt')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                   

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <!-- Issue Date -->
                                        <div>
                                            <label for="doc_update_date" class="block text-sm font-medium text-gray-700">Issue Date </label>
                                            <input type="date" 
                                                id="doc_update_date" 
                                                x-model="formData.doc_update_date"
                                                placeholder="DD-MM-YYYY"
                                                pattern="\d{2}-\d{2}-\d{4}"
                                                maxlength="10"
                                                @input="$el.value = $el.value.replace(/[^0-9-]/g, '').replace(/(.*-.*-.*)-/, '$1')"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                                >
                                        </div>


                                        <!-- Document Year -->
                                        <div>
                                            <label for="doc_year" class="block text-sm font-medium text-gray-700">Document Year </label>
                                            <input type="text" 
                                                id="doc_year" 
                                                x-model="formData.doc_year"
                                                placeholder="YYYY-YYYY"
                                                pattern="\d{4}-\d{4}"
                                                maxlength="9"
                                                @input="$el.value = $el.value.replace(/[^0-9-]/g, '').replace(/(....)-?(....)?/, '$1-$2')"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                >
                                        </div>

                                        <!-- Document Info -->
                                        <div>
                                            <label for="doc_info" class="block text-sm font-medium text-gray-700">Document Info </label>
                                            <textarea id="doc_info" 
                                                x-model="formData.doc_info" 
                                                rows="1"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                 
                                                placeholder="Enter additional information..."></textarea>
                                        </div>
                                    </div>

                                    <!-- File Upload Section -->
                                    <div class="pt-4 border-t border-gray-200">

                                            <!-- Improved File Upload Component -->
                                            <div class="space-y-4" x-data="{
                                                filesAdded: false,
                                                files: [],
                                                handleFileSelect(event) {
                                                    this.filesAdded = true;
                                                    this.files = Array.from(event.target.files);
                                                },
                                                resetFiles() {
                                                    this.filesAdded = false;
                                                    this.files = [];
                                                    document.getElementById('file-upload').value = '';
                                                }
                                            }" @document-updated.window="resetFiles()">
                                                <div>
                                                    <!-- Upload Container -->
                                                    
                                                    <div class="mt-2 flex items-center justify-between transition-colors">
                                                        <div class="flex-1">
                                                            <label class="text-sm font-medium text-gray-700 mb-2">Upload Documents</label>
                                                            <div class="mt-1 flex items-center px-4 py-3 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                                                                <div class="flex items-center space-x-2">
                                                                    <label for="file-upload" class="cursor-pointer bg-white font-medium text-sm text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                                        <span>Upload files</span>
                                                                        <input id="file-upload" 
                                                                            type="file" 
                                                                            multiple 
                                                                            class="sr-only"
                                                                            wire:model="files"
                                                                            @change="handleFileSelect($event)">
                                                                    </label>
                                                                    <span class="text-xs text-gray-500">(PNG, JPG, GIF, PDF, DOC, DOCX , XLS, XLSX)</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div x-show="files.length" class="text-sm text-gray-500">
                                                        <span x-text="files.length + ' file(s) selected'"></span>
                                                    </div>
                                                </div>

                                                <!-- File Preview Area with reset functionality -->
                                                <div x-show="filesAdded && files.length > 0" class="mt-4">
                                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Selected files:</h4>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                                        <template x-for="(file, index) in files" :key="index">
                                                            <div class="relative flex flex-col border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                                                <!-- File Preview -->
                                                                <div class="h-32 bg-gray-50 flex items-center justify-center">
                                                                    <template x-if="file.type.includes('image/')">
                                                                        <img :src="URL.createObjectURL(file)" class="h-full w-full object-contain" />
                                                                    </template>
                                                                    <template x-if="!file.type.includes('image/')">
                                                                        <div class="p-4">
                                                                            <!-- File Type Icons -->
                                                                            <template x-if="file.type.includes('pdf')">
                                                                                <svg class="h-12 w-12 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                                </svg>
                                                                            </template>
                                                                            <!-- Add other file type icons here -->
                                                                        </div>
                                                                    </template>
                                                                </div>
                                            
                                                                <!-- File Info -->
                                                                <div class="p-3">
                                                                    <div class="truncate text-sm font-medium text-gray-900" x-text="file.name"></div>
                                                                    <div class="text-xs text-gray-500 mt-1" x-text="(file.size/1024).toFixed(1) + ' KB'"></div>
                                                                </div>
                                            
                                                                <!-- Remove Button -->
                                                                <button @click="files.splice(index, 1); if(files.length === 0) filesAdded = false" 
                                                                    type="button"
                                                                    class="absolute top-2 right-2 p-1 bg-white rounded-full shadow hover:bg-red-50">
                                                                    <svg class="h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>

                                                <!-- Existing Files Section -->
                                                <div x-show="isEditing && existingFiles.length > 0" class="mt-2">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-xs font-medium text-gray-600">Files</span>
                                                        <span class="text-xs text-gray-400" x-text="existingFiles.length + ' items'"></span>
                                                    </div>
                                                    
                                                    <!-- Grid layout with 2 files per row -->
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <template x-for="file in existingFiles" :key="file.uuid">
                                                            <div class="flex items-center bg-white rounded border border-gray-200 px-2 py-1.5">
                                                                <!-- File icon/thumbnail -->
                                                                <div class="h-8 w-8 flex-shrink-0 flex items-center justify-center mr-2">
                                                                    <template x-if="file.original_file_name.match(/\.(jpg|jpeg|png|gif)$/i)">
                                                                        <img :src="'/storage/' + file.file_path" class="h-8 w-8 object-cover rounded" />
                                                                    </template>
                                                                    <template x-if="!file.original_file_name.match(/\.(jpg|jpeg|png|gif)$/i)">
                                                                        <template x-if="file.original_file_name.endsWith('.pdf')">
                                                                            <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                            </svg>
                                                                        </template>
                                                                    </template>
                                                                </div>
                                                                
                                                                <!-- File details -->
                                                                <div class="flex-grow min-w-0">
                                                                    <div class="truncate text-xs font-medium text-gray-700" x-text="file.original_file_name"></div>
                                                                </div>
                                                                
                                                                <!-- Delete button -->
                                                                <button @click="removeExistingFile(file.uuid)"
                                                                    type="button" 
                                                                    class="ml-2 p-1 text-gray-400 hover:text-red-500">
                                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                    
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                                    
                                                <!-- Upload Progress (when files are being uploaded) -->
                                                <div wire:loading wire:target="files" class="mt-2">
                                                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                                        <div class="flex">
                                                            <svg class="animate-spin h-5 w-5 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            <div>
                                                                <p class="text-sm font-medium text-blue-800">Uploading files...</p>
                                                                <p class="mt-1 text-sm text-blue-600">Please wait while your files are being uploaded.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" @click="closeModal()"
                                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                        <span x-text="isEditing ? 'Update Document' : 'Add Document'"></span>
                                        
                                        <span class="indicator-progress" wire:loading wire:target="updateSubDocument,createSubDocument">
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
        </div>
     


        <!-- Add mobile view table -->
        <div class="md:hidden space-y-4">
            @forelse ($documents as $document)
                <div class="bg-white shadow rounded-lg mb-4 p-4">
                    <!-- Document Title -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ $document->doc_title }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Doc #: {{ $document->doc_number }}</p>
                        </div>
                        <!-- Actions Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-2 hover:bg-gray-50 rounded-full">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                            <div x-show="open" 
                                @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                                <div class="py-1 divide-y divide-gray-100">
                              
                                        <button  wire:click="openSendAttachmentsModal('{{ $document->uuid }}')"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                        <span>Sent Email</span>
                                    </button>
                                        <button wire:click="viewSentEmails('{{ $document->uuid }}')"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                        
                                        <span>View History</span>
                                    </button>
                                    <button 
                                        @click="$dispatch('edit-modal', {
                                            title: '{{ $document->doc_title }}',
                                            doc_number: '{{ $document->doc_number }}',
                                            doc_update_date: '{{ $document->doc_update_date }}',
                                            doc_expire_date: '{{ $document->doc_expire_date }}',
                                            'doc_renewal_dt':'{{ $document->doc_renewal_dt}}',
                                            validity: '{{ $document->doc_validity }}',
                                            doc_info: '{{ $document->doc_info }}',
                                            doc_year: '{{ $document->doc_year }}',
                                            uuid: '{{ $document->uuid }}',
                                            attachments: {{ json_encode($document->attachments->map(function($attachment) {
                                                return [
                                                    'uuid' => $attachment->uuid,
                                                    'original_file_name' => $attachment->original_file_name,
                                                    'file_size' => $attachment->file_size,
                                                    'file_path' => $attachment->file_path
                                                ];
                                            })) }}
                                        })"
                                        class="p-1.5 text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                        <div class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                           
                                            <span class="font-medium">Edit Document</span>
                                        </div>
                                    </button>
                                    <button wire:click="deleteDocument('{{ $document->uuid }}')"
                                            wire:confirm="Are you sure you want to delete this sub document?"
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition duration-150">
                                        
                                        <span>Delete Document</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Details -->
                    <div class="space-y-2 mb-4">
                        <div class="text-xs text-gray-500">
                            <span class="font-medium">Validity:</span>
                            <span>{{ $doc_validity_list[$document->doc_validity] ?? 'Unknown' }}</span>
                        </div>
                    </div>

                    <!-- Attachments Section -->
                    <div x-data="{ 
                        showMobileModal: false,
                        currentMobileFile: null,
                        previewMobileFile(file) {
                            this.currentMobileFile = file;
                            this.showMobileModal = true;
                        }
                    }">
                        <div class="border-t pt-3">
                            <h4 class="text-xs font-medium text-gray-500 mb-2">Attachments</h4>
                            @forelse($document->attachments as $attachment)
                                <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                                    <span class="text-xs text-gray-600 truncate flex-1 pr-2">
                                        {{ $attachment->original_file_name }}
                                    </span>
                                    <div class="flex items-center space-x-3">
                                        <!-- Preview Button -->
                                        <button @click="previewMobileFile({
                                            name: '{{ $attachment->original_file_name }}',
                                            url: '{{ Storage::url($attachment->file_path) }}'
                                        })" class="p-2 text-gray-400 hover:text-indigo-600">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <!-- Download Button -->
                                        <a href="{{ Storage::url($attachment->file_path) }}"
                                        download="{{ $attachment->original_file_name }}"
                                        class="p-2 text-gray-400 hover:text-indigo-600">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-500 py-2">No attachments</p>
                            @endforelse
                        </div>

                        <!-- Mobile Preview Modal -->
                        <template x-if="showMobileModal">
                            <div class="fixed inset-0 z-50 overflow-y-auto" 
                                @click.self="showMobileModal = false"
                                @keydown.escape.window="showMobileModal = false">
                                <div class="flex items-center justify-center min-h-screen px-4">
                                    <div class="fixed inset-0 transition-opacity">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                    </div>

                                    <div class="relative bg-white rounded-lg w-full max-w-lg mx-auto shadow-xl">
                                        <!-- Modal Header -->
                                        <div class="flex items-center justify-between px-4 py-3 border-b">
                                            <h3 class="text-lg font-medium text-gray-900 truncate" x-text="currentMobileFile?.name"></h3>
                                            <button @click="showMobileModal = false" class="text-gray-400 hover:text-gray-500">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="p-4">
                                            <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg overflow-hidden">
                                                <iframe :src="currentMobileFile?.url" 
                                                    class="w-full h-full"
                                                    frameborder="0">
                                                </iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-sm text-gray-500">No sub documents found</p>
                </div>
            @endforelse

        <!-- Mobile Pagination -->
        <div class="mt-4 px-4">
            {{ $documents->links() }}
        </div>   
    </div>

    @include('livewire.Document.sub-document-email')
    @include('livewire.Document.sub-document-bulk-email')
    @include('livewire.Document.document-email-history')
    <script>
    function subDocumentModal() {
        return {
            show: false,
            isEditing: false,
            existingFiles: [],
            editingId: null,
            parentDocTitle: '{{ $parentDocument->doc_title }}',
            formData: {
                title: '',
                doc_number: '',
                doc_update_date: '',
                validity: '1',
                doc_info: '',
                uuid: '',
                doc_year: '',
                files: [],
                doc_expire_date:'',
                doc_validity:'',
                doc_renewal_dt:''
            },

            init() {
                this.$wire = window.Livewire.find(this.$root.getAttribute('wire:id'));
                window.addEventListener('open-modal', () => {
                    this.openModal();
                });

                window.addEventListener('document-updated', () => {
                    this.show = false;
                    this.$wire.resetPage();
                });
                
            //     window.addEventListener('edit-modal', (event) => {
            //     this.show = true;
            //     this.isEditing = true;
            //     this.formData = {
            //         title: event.detail.title,
            //         doc_number: event.detail.doc_number,
            //         doc_update_date: event.detail.doc_update_date || '',
            //         validity: event.detail.validity,
            //         doc_info: event.detail.doc_info,
            //         doc_year: event.detail.doc_year,
            //         uuid: event.detail.uuid
            //     };
            //     // Set existing files
            //     this.existingFiles = event.detail.attachments || [];
            //     console.log('Existing files:', this.existingFiles); // Debug log
            // });
            window.addEventListener('edit-modal', (event) => {
                this.formData = {
                    title: event.detail.title,
                    doc_number: event.detail.doc_number,
                    doc_update_date: event.detail.doc_update_date || '',
                    validity: event.detail.validity,
                    doc_info: event.detail.doc_info,
                    doc_year: event.detail.doc_year,
                    uuid: event.detail.uuid,
                    doc_expire_date:event.detail.doc_expire_date,
                    doc_validity:event.detail.doc_validity,
                    doc_renewal_dt:event.detail.doc_renewal_dt
                };
                // Set existing files
                this.existingFiles = event.detail.attachments;
                console.log('Existing files:', this.existingFiles); // Debug log
            });
            },

            validateDocYear(value) {
                const pattern = /^\d{4}-\d{4}$/;
                if (!pattern.test(value)) {
                    return false;
                }
                const [startYear, endYear] = value.split('-').map(Number);
                return startYear <= endYear && startYear >= 1900 && endYear <= 2100;
            },
            
            openModal() {
                this.show = true;
                this.isEditing = false;
                this.resetForm();
            },
            
            openEditModal(document) {
                alert(document)
                this.isEditing = true;
                this.editingId = document.id;
                this.formData = {
                    title: document.doc_title,
                    doc_number: document.doc_number,
                    doc_update_date: document.doc_update_date,
                    validity: document.doc_validity.toString(),
                    doc_year: document.doc_year,
                    uuid: document.uuid,
                    files: [],
                    doc_info: document.doc_info,
                    doc_expire_date:document.doc_expire_date,
                    doc_validity:document.doc_validity,
                    doc_renewal_dt:document.doc_renewal_dt
                };
                this.show = true;
            },
            
            closeModal() {
                this.show = false;
                this.resetForm();
                this.$wire.resetPage();
                this.$wire.dispatch('close-subdoc-modal');
            },

            removeExistingFile(uuid) {
                if (confirm('Are you sure you want to remove this file?')) {
                    this.$wire.removeAttachment(uuid).then(() => {
                        this.existingFiles = this.existingFiles.filter(f => f.uuid !== uuid);
                    });
                }
            },
            
            resetForm() {
                this.formData = {
                    title: '',
                    doc_number: '',
                    doc_update_date: '',
                    validity: '1',
                    doc_info: '',
                    doc_year: '',
                    uuid: '',
                    files: [],
                    doc_expire_date:'',
                    doc_validity:'',
                    doc_renewal_dt:''
                };
                this.existingFiles = [];
                this.isEditing = false;
                this.editingId = null;
            },
            
            async submitForm() {
                try {
                    const formData = new FormData();
                    Object.keys(this.formData).forEach(key => {
                        formData.append(key, this.formData[key]);
                    });
                    
                    // files to formData
                    if (this.$refs.fileInput && this.$refs.fileInput.files) {
                        Array.from(this.$refs.fileInput.files).forEach((file, index) => {
                            formData.append(`files[${index}]`, file);
                        });
                    }

                    // Validate document year
                    if (this.formData.doc_year && !this.validateDocYear(this.formData.doc_year)) {
                        alert('Please enter a valid document year in YYYY-YYYY format');
                        return;
                    }
                    
                    let result;
                    if (this.isEditing) {
                        result = await this.$wire.updateSubDocument(this.formData.uuid, this.formData);
                    } else {
                        result = await this.$wire.createSubDocument(this.formData);
                    }
                
                    this.closeModal();
                    // await this.$wire.$refresh();
                    await this.$wire.resetPage();
                } catch (error) {
                    console.error('Error submitting form:', error);
                }
                
            }
            
        }
    }
    
    </script>

    <!-- Add mobile-specific styles -->
    <style>
        @media (max-width: 768px) {
            .aspect-w-16 {
                height: 60vh;
                min-height: 300px;
            }
            
            .max-w-3xl {
                max-width: 95vw;
            }
        }
    </style>

    <style>
        .aspect-w-16 {
            position: relative;
            height: calc(100vh - 250px);
            min-height: 600px;
        }
    
        .aspect-w-16 > * {
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    
        /* Improve iframe display */
        iframe {
            background: white;
            border: none;
        }
    
        /* Ensure modal has enough space */
        .max-w-3xl {
            max-width: 80vw;
        }
    </style>
</div>
</div>