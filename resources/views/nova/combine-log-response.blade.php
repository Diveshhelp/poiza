<div class="p-4 space-y-4">
    @foreach($response as $key=>$value)
    <div x-data="{ isOpen: false }" class="w-full max-w-full">
    <div class="border border-gray-500 rounded-t-lg cursor-pointer" @click="isOpen = !isOpen">
        <div class="flex items-center justify-between p-4 bg-gray-50">
            <div class="flex items-center space-x-2">
                    <h3 class="text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-900 p-2">{{$value->slug}} @if($value->language_id!="") - <b>{{$language[$value->language_id]}} </b> @endif</h3>
                </div>
                
                <div class="relative w-5 h-5">
                    <!-- Expand Icon -->
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
                    <!-- Collapse Icon -->
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

        <!-- Content Section -->
        <div 
            x-show="isOpen" 
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 transform -translate-y-2" 
            x-transition:enter-end="opacity-100 transform translate-y-0" 
            x-transition:leave="transition ease-in duration-200" 
            x-transition:leave-start="opacity-100 transform translate-y-0" 
            x-transition:leave-end="opacity-0 transform -translate-y-2" 
            class="border-x border-b border-gray-300 rounded-b-lg bg-white"
        >
            <div class="p-4 space-y-6">
                <!-- Prompt Section -->
                @if(!empty($value->prompt))
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-gray-900">Prompt:</h3> 
                        <div class="text-sm">
                            @php
                                $jsonDecoded = json_decode($value->prompt);
                                $isJson = (json_last_error() === JSON_ERROR_NONE);
                            @endphp
                            @if($isJson)
                                <pre class="whitespace-pre-wrap break-words bg-gray-50 p-4 rounded-lg overflow-x-auto max-h-96 font-mono text-gray-700">{{ stripslashes(json_encode($jsonDecoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}</pre>
                            @else
                                <pre class="whitespace-pre-wrap break-words bg-gray-50 p-4 rounded-lg overflow-x-auto max-h-96 font-mono text-gray-700">{{ $value->prompt }}</pre>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Response Section -->
                @if(!empty($value->response))
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-gray-900">Response:</h3> 
                        <div class="text-sm">
                            @php
                                $jsonDecoded = json_decode($value->response);
                                $isJson = (json_last_error() === JSON_ERROR_NONE);
                            @endphp
                            @if($isJson)
                                <pre class="whitespace-pre-wrap break-words bg-gray-50 p-4 rounded-lg overflow-x-auto max-h-96 font-mono text-gray-700">{{ stripslashes(json_encode($jsonDecoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}</pre>
                            @else
                                <pre class="whitespace-pre-wrap break-words bg-gray-50 p-4 rounded-lg overflow-x-auto max-h-96 font-mono text-gray-700">{{ $value->response }}</pre>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Error Section -->
                @if(!empty($value->error_log))
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-gray-900">Error:</h3> 
                        <div class="text-sm">
                            @php
                                $jsonDecoded = json_decode($value->error_log);
                                $isJson = (json_last_error() === JSON_ERROR_NONE);
                            @endphp
                            @if($isJson)
                                <pre class="whitespace-pre-wrap break-words bg-gray-50 p-4 rounded-lg overflow-x-auto max-h-96 font-mono text-red-600">{{ stripslashes(json_encode($jsonDecoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}</pre>
                            @else
                                <pre class="whitespace-pre-wrap break-words bg-gray-50 p-4 rounded-lg overflow-x-auto max-h-96 font-mono text-red-600">{{ $value->error_log }}</pre>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>