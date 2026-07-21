<div class="py-0" >
    <div class="max-w-8xl mx-auto sm:px-2 lg:px-2" >
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-medium">{{ $moduleTitle }}</h2>
            
            <div class="flex space-x-2 items-center">
                <input 
                    wire:model.live.debounce.300ms="searchTerm" 
                    type="text" 
                    placeholder="Search..." 
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm px-2 py-1 text-sm w-48"
                >
                <div x-data="{ showOptions: false }" class="relative">
                    <button 
                        @click="showOptions = !showOptions" 
                        class="p-1 rounded border border-gray-300 hover:bg-gray-100"
                        title="Display options"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </button>
                    <div 
                        x-show="showOptions" 
                        @click.away="showOptions = false" 
                        x-cloak
                        class="absolute right-0 z-10 mt-1 bg-white border border-gray-200 rounded shadow-lg p-2 text-sm w-40"
                    >
                        <div class="space-y-1">
                            <label class="flex items-center">
                                <input type="checkbox" checked class="form-checkbox h-3 w-3">
                                <span class="ml-1">File Name</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" checked class="form-checkbox h-3 w-3">
                                <span class="ml-1">Path</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" checked class="form-checkbox h-3 w-3">
                                <span class="ml-1">Type</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" checked class="form-checkbox h-3 w-3">
                                <span class="ml-1">Size</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" checked class="form-checkbox h-3 w-3">
                                <span class="ml-1">IP Address</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" checked class="form-checkbox h-3 w-3">
                                <span class="ml-1">Deleted At</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-xs">
                <thead>
                    <tr>
                        <th class="px-2 py-2 bg-gray-50 text-left font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('item_name')">
                            Name
                            @if ($sortField === 'item_name')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-2 py-2 bg-gray-50 text-left font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('item_name')">
                            Deleted By
                        </th>
                        <th class="px-2 py-2 bg-gray-50 text-left font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('item_path')">
                            Path
                            @if ($sortField === 'item_path')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-2 py-2 bg-gray-50 text-left font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('item_type')">
                            Type
                            @if ($sortField === 'item_type')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-2 py-2 bg-gray-50 text-left font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('item_size')">
                            Size
                            @if ($sortField === 'item_size')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-2 py-2 bg-gray-50 text-left font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                            Deleted
                            @if ($sortField === 'created_at')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-2 py-2 bg-gray-50 text-right font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" x-data="{ expanded: false }">
                    @forelse($dropboxItems as $item)
                        <tr  class="hover:bg-gray-50">
                            <td class="px-2 py-2 whitespace-nowrap">
                                <span title="{{ $item->item_name ?? 'N/A' }}">
                                    {{ Str::limit($item->item_name ?? 'N/A', 20) }}
                                </span>
                            </td>
                            <td class="px-2 py-2">
                                <div class="text-gray-900 truncate max-w-[100px]" >
                                    {{ $item->user->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-2 py-2 ">
                                <div class="text-gray-900 truncate max-w-[200px]" title="{{ $item->item_path ?? 'N/A' }}">
                                    {{ $item->item_path ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap max-w-[50px]">
                                {{ $item->item_type ?? 'N/A' }}
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap max-w-[50px]">
                                @if($item->item_size)
                                    {{ formatFileSize($item->item_size) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap max-w-[50px]">
                                {{ $item->created_at ? $item->created_at->format('y-m-d H:i') : '-' }}
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap text-right space-x-1 max-w-[100px]" >
                                @if(is_array($item->metadata) && !empty($item->metadata))
                                    <div x-data="{ open: false }" class="inline-block">
                                        <button 
                                            @click="open = true" 
                                            class="text-blue-600 hover:text-blue-800"
                                            title="View Metadata"
                                        >
                                            <svg class="h-4 w-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                            </svg>
                                        </button>
                                        
                                        <!-- Modal Backdrop -->
                                        <div 
                                            x-show="open" 
                                            x-cloak
                                            @click="open = false"
                                            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
                                        >
                                            <!-- Modal Content -->
                                            <div 
                                                @click.stop
                                                class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-5xl sm:w-full"
                                            >
                                                <div class="px-4 py-3 border-b border-gray-200">
                                                    <div class="flex items-center justify-between">
                                                        <h3 class="text-sm font-medium text-gray-900">
                                                            Metadata for {{ Str::limit($item->item_name, 30) }}
                                                        </h3>
                                                        <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                                                            <span class="sr-only">Close</span>
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="px-4 py-3 max-h-96 overflow-y-auto">
                                                    <pre class="text-xs text-left bg-gray-50 p-3 rounded">{{ json_encode($item->metadata, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                                <div class="px-4 py-2 bg-gray-50 text-right">
                                                    <button @click="open = false" class="px-3 py-1 bg-gray-300 text-gray-800 text-xs rounded hover:bg-gray-400">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr x-show="expanded" x-cloak class="bg-gray-50">
                            <td colspan="6" class="px-3 py-2 text-xs">
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <span class="font-medium">IP Address:</span> 
                                        {{ $item->ip_address ?? 'N/A' }}
                                    </div>
                                    <div class="col-span-2">
                                        <span class="font-medium">Full Path:</span> 
                                        {{ $item->item_path ?? 'N/A' }}
                                    </div>
                                    <div class="col-span-3">
                                        <span class="font-medium">Additional Info:</span>
                                        @if(is_array($item->metadata) && !empty($item->metadata))
                                            @if(isset($item->metadata['description']))
                                                {{ $item->metadata['description'] }}
                                            @else
                                                <span class="text-gray-500">No additional info available</span>
                                            @endif
                                        @else
                                            <span class="text-gray-500">No additional info available</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-2 py-4 text-center text-sm">
                                No deletion records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $dropboxItems->links() }}
        </div>
    </div>
        
    @php
    function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024; $i++) {
            $size /= 1024;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }
    @endphp

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>
</div>
