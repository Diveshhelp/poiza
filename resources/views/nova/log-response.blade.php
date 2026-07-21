<div x-data="{ isOpen: false }" class="w-full max-w-full">
    <div class="border border-gray-500 rounded-t-lg cursor-pointer" @click="isOpen = !isOpen">
        <div class="flex items-center justify-between p-4 bg-gray-50">
            <div class="flex items-center space-x-2">
                @php
                    $jsonDecoded = json_decode($response);
                    $isJson = (json_last_error() === JSON_ERROR_NONE);
                @endphp
                @if(isset($type))
                    <h3>{{$type}}</h3>
                @endif
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                    {{ $isJson ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $isJson ? 'JSON Data' : 'Non-JSON Data' }}
                </span>
            </div>
            
            <div class="relative w-5 h-5">
                <svg 
                    class="absolute inset-0 w-5 h-5 transition-opacity duration-200 text-gray-500"
                    :class="{ 'opacity-0': isOpen }"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <svg 
                    class="absolute inset-0 w-5 h-5 transition-opacity duration-200 text-gray-500"
                    :class="{ 'opacity-0': !isOpen }"
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
            </div>
        </div>
    </div>

    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 transform -translate-y-2" 
         x-transition:enter-end="opacity-100 transform translate-y-0" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100 transform translate-y-0" 
         x-transition:leave-end="opacity-0 transform -translate-y-2" 
         class="border-x border-b border-gray-200 rounded-b-lg">
        
        <div class="space-y-2 p-4">
            @if(!empty($response))
                <div class="text-sm font-medium">
                    @if($isJson)
                        {{-- JSON Data Display --}}
                        <pre class="whitespace-pre-wrap break-words bg-gray-100 p-4 rounded-lg overflow-x-auto max-h-96">{{ stripslashes(json_encode($jsonDecoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}</pre>
                    @else
                        {{-- Raw Data Display --}}
                        <pre class="whitespace-pre-wrap break-words bg-gray-50 p-4 rounded-lg overflow-x-auto max-h-96">{{ $response }}</pre>
                    @endif
                </div>
            @else
                <div class="text-sm font-medium text-red-500">
                    {{ DEFAULT_WARNING_RESPONSE }}
                </div>
            @endif
        </div>
    </div>
</div>