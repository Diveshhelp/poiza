<div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        {{-- Flash Messages --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Document Selection Header --}}
        <div class="flex flex-wrap items-center justify-between">
                <div class="min-w-0 flex-1 pr-2">
                    <h3 class="text-lg font-medium text-gray-900"> Documents Send or Download
                    </h3>
                    <p
                        class="mt-1 md:mt-2 text-xs md:text-sm text-gray-600 leading-relaxed line-clamp-1 md:line-clamp-none">
                    </p>
                </div>
                <div class="mb-2 flex shrink-0 md:ml-4 md:mt-0">
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

        {{-- Documents Table --}}
        <div class="overflow-x-auto mt-4">
 <!-- document-selections.blade.php -->

<div x-data="{ 
    showPreview: false, 
    previewUrl: '', 
    previewType: '', 
    previewName: '',
    isImage: false
}">
    <!-- Main Document Table -->
    <table class="min-w-full divide-y divide-gray-200 text-xs">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Title</th>
                <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Added</th>
                <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attachments</th>
                <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($docSelections as $document)
                <tr class="@if($document->parent_id=='') bg-blue-50 hover:bg-blue-100 font-medium @else bg-gray-50 hover:bg-gray-100 @endif">
                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                        @if($document->parent_id=="")
                            <span class="font-medium text-blue-600">Master Doc</span>
                        @else
                            <span class="text-gray-600">Sub Doc</span>
                        @endif
                    </td>
                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                        <div>{{ $document->ownership->owner_title ?? $document->parent->ownership->owner_title }}</div>
                    </td>
                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                        @if($document->parent_id=="")
                            <span class="font-medium">{{ $document->doc_title }}</span>
                        @else
                            {{ $document->doc_title ?? 'Sub Doc' }}
                            <div class="text-gray-400 text-xs italic">Parent: {{ $document->parent->doc_title ?? 'Unknown' }}</div>
                        @endif
                    </td>
                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                        {{ $document->category->category_title ?? $document->parent->category->category_title }}
                    </td>
                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                        {{ $document->created_at->format('M d, Y') }}
                    </td>
                    
                    <!-- Preview Column -->
                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                        @if($document->attachments->count() > 0)
                            <div class="flex items-center space-x-1">
                                @foreach($document->attachments as $attachment)
                                    @php
                                        $extension = strtolower(pathinfo($attachment->original_file_name, PATHINFO_EXTENSION));
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp']);
                                    @endphp
                                    
                                    <!-- Preview button with modal trigger -->
                                    <button type="button" 
                                            class="p-1 hover:bg-gray-100 rounded-full"
                                            title="Preview {{ $attachment->original_file_name }}"
                                            @click="
                                                showPreview = true; 
                                                previewUrl = '{{ "/public".Storage::url($attachment->file_path) }}'; 
                                                previewName = '{{ $attachment->original_file_name }}';
                                                previewType = '{{ $extension }}';
                                                isImage = {{ $isImage ? 'true' : 'false' }};
                                            ">
                                        @switch($extension)
                                            @case('pdf')
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                @break
                                            @case('doc')
                                            @case('docx')
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                @break
                                            @case('xls')
                                            @case('xlsx')
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                @break
                                            @case('jpg')
                                            @case('jpeg')
                                            @case('png')
                                            @case('gif')
                                            @case('svg')
                                            @case('webp')
                                            @case('bmp')
                                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                @break
                                            @case('mp4')
                                            @case('webm')
                                            @case('mov')
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                @break
                                            @default
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                        @endswitch
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    
                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                        <button class="text-blue-600 hover:text-blue-900 text-xs"
                                type="button"
                                @if($document->attachments->count() > 0)
                                    x-data="{}"
                                    x-on:click="$dispatch('toggle-attachments-{{ $document->id }}')"
                                @else
                                    disabled
                                    class="text-gray-400"
                                @endif>
                            {{ $document->attachments->count() }} Attachment(s)
                        </button>
                    </td>
                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                        @php
                        $id=App\Models\DocSelections::where('user_id', $user_id)
                                ->where('team_id', $team_id)
                                ->where('doc_status', 0)
                                ->where('document_id', $document->id)
                                ->first()
                                ->toArray();
                        @endphp
                    
                        <button wire:confirm="Are you sure you want to delete this?" wire:click="deleteSelected('{{  $id['id'] }}')" class="flex items-center px-2 py-1 text-xs text-red-600 hover:bg-red-50 group">
                            <svg class="mr-1 h-3 w-3 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </td>
                </tr>
                
                {{-- Attachments Rows --}}
                @if($document->attachments->count() > 0)
                    <tr x-data="{ open: false }" 
                        x-on:toggle-attachments-{{ $document->id }}.window="open = !open" 
                        x-show="open" 
                        x-cloak 
                        class="border-b border-gray-100">
                        <td colspan="8" class="px-2 py-1">
                            <div class="bg-white p-2 rounded shadow-sm">
                                <h4 class="font-medium text-gray-700 text-xs mb-1">Attachments for {{ $document->doc_title }}</h4>
                                <table class="min-w-full divide-y divide-gray-200 text-xs">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                            <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($document->attachments as $attachment)
                                            @php
                                                $extension = strtolower(pathinfo($attachment->original_file_name, PATHINFO_EXTENSION));
                                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp']);
                                            @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-2 py-1 text-xs">
                                                    <!-- File type badge -->
                                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp']))
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">IMG</span>
                                                    @elseif($extension === 'pdf')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">PDF</span>
                                                    @elseif(in_array($extension, ['doc', 'docx']))
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">DOC</span>
                                                    @elseif(in_array($extension, ['xls', 'xlsx']))
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">XLS</span>
                                                    @elseif(in_array($extension, ['mp4', 'webm', 'mov']))
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">VIDEO</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ strtoupper($extension) }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-2 py-1 text-xs">{{ $attachment->original_file_name }}</td>
                                                <td class="px-2 py-1 text-xs">{{ $attachment->file_size ? round($attachment->file_size / 1024, 2) . ' KB' : 'Unknown' }}</td>
                                                <td class="px-2 py-1 text-xs">{{ $attachment->created_at->format('M d, Y') }}</td>
                                                <td class="px-2 py-1 text-xs">
                                                    <div class="flex space-x-2">
                                                        <!-- Preview button with modal trigger -->
                                                        <button type="button"
                                                                class="inline-flex items-center px-2 py-1 text-xs text-blue-600 hover:bg-blue-50 rounded"
                                                                @click="
                                                                    showPreview = true; 
                                                                    previewUrl = '{{ "/public".Storage::url($attachment->file_path) }}'; 
                                                                    previewName = '{{ $attachment->original_file_name }}';
                                                                    previewType = '{{ $extension }}';
                                                                    isImage = {{ $isImage ? 'true' : 'false' }};
                                                                ">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                            Preview
                                                        </button>
                                                        
                                                        <a href="{{ "/public".Storage::url($attachment->file_path) }}" 
                                                        download="{{ $attachment->original_file_name }}"
                                                        class="inline-flex items-center px-2 py-1 text-xs text-green-600 hover:bg-green-50 rounded">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                            </svg>
                                                            Download
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="8" class="px-2 py-1 whitespace-nowrap text-center text-gray-500 text-xs">
                        No documents selected yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Preview Modal (Alpine.js powered) -->
    <div x-show="showPreview" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showPreview" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 aria-hidden="true"
                 @click="showPreview = false"></div>

            <!-- Modal panel -->
            <div x-show="showPreview" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                
                <!-- Header -->
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title" x-text="previewName"></h3>
                    <button @click="showPreview = false" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Content -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-center">
                        <!-- Image Preview -->
                        <template x-if="isImage">
                            <img :src="previewUrl" :alt="previewName" class="max-w-full max-h-[500px] object-contain">
                        </template>
                        
                        <!-- PDF Preview -->
                        <template x-if="previewType === 'pdf'">
                            <div class="w-full h-[500px]">
                                <object :data="previewUrl" type="application/pdf" class="w-full h-full">
                                    <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-lg">
                                        <svg class="w-16 h-16 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm font-medium text-gray-800">PDF Preview Not Available</p>
                                        <a :href="previewUrl" target="_blank" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                            Open PDF
                                        </a>
                                    </div>
                                </object>
                            </div>
                        </template>
                        
                        <!-- Video Preview -->
                        <template x-if="['mp4', 'webm', 'mov'].includes(previewType)">
                            <video controls class="max-w-full max-h-[500px]">
                                <source :src="previewUrl" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </template>
                        
                        <!-- Other File Types -->
                        <template x-if="!isImage && previewType !== 'pdf' && !['mp4', 'webm', 'mov'].includes(previewType)">
                            <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-lg">
                                <!-- Icon based on file type -->
                                <template x-if="['doc', 'docx'].includes(previewType)">
                                    <svg class="w-16 h-16 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </template>
                                <template x-if="['xls', 'xlsx'].includes(previewType)">
                                    <svg class="w-16 h-16 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </template>
                                <template x-if="!['doc', 'docx', 'xls', 'xlsx'].includes(previewType)">
                                    <svg class="w-16 h-16 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </template>
                                
                                <p class="mt-2 text-sm font-medium text-gray-800" x-text="previewType.toUpperCase() + ' File'"></p>
                                <p class="mt-1 text-xs text-gray-500">Preview not available for this file type</p>
                                <a :href="previewUrl" :download="previewName" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                    Download
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="showPreview = false" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                    <a :href="previewUrl" :download="previewName" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Download
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-6 flex justify-end space-x-4">
          
            <button wire:click="downloadAllAttachments" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                Download Zip
            </button>
            <button wire:click="showEmailModal" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                Send Via Email
            </button>
        </div>
    </div>

    {{-- Email Modal --}}
    @if($showEmailForm)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg overflow-scroll shadow-xl transform transition-all sm:max-w-lg sm:w-full max-h-[90vh] flex flex-col">
            <!-- Header - Fixed height -->
            <div class="bg-white px-4 pt-5 pb-2 sm:p-6 sm:pb-2">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Send Documents via Email
                        </h3>
                    </div>
                </div>
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
            <!-- Content - Scrollable area -->
            <div class="flex-1 px-4 sm:px-6 overflow-y-auto">
                <form wire:submit.prevent="sendDocumentsViaEmail">
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Select Attachments</label>
                        </div>
                        <div class="border border-gray-200 rounded-md max-h-48 overflow-y-auto">
                            <ul class="divide-y divide-gray-200">
                                @forelse($bulk_document_attachments as $index => $attachment)
                                <li class="p-2 hover:bg-gray-50">
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <div class="flex-1 min-w-0">
                                            <span class="text-sm text-gray-700 truncate block">{{ $attachment->original_file_name }}</span>
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
                    <div class="text-xs text-gray-500 mb-2">
                        All shown attachments will be sent to the email address above.
                    </div>
                </form>
            </div>
            
            <!-- Footer - Fixed height -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse mt-2 border-t border-gray-200">

                
                <button wire:click="sendBulkAttachments" type="button"  class="pr-2 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0"
                wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="sendBulkAttachments">Send Attachments</span>
                    <span wire:loading wire:target="sendBulkAttachments">Sending...</span>
                </button>

                <button 
                    type="button" 
                  wire:click="closeEmailModal"
                    class="pr-2 mr-2 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
    @endif
</div>