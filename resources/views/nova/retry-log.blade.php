<div x-data="{ isOpen: true }" class="w-full max-w-full">
    <div class="border border-gray-500 rounded-t-lg cursor-pointer" @click="isOpen = !isOpen">
        <div class="flex items-center justify-between p-4 bg-gray-50">
            <div class="flex items-center space-x-2">
                    <h3>Error & Retry Log</h3>
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
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-4"
    class="bg-white shadow-lg rounded-lg p-6">
    
    <div class="space-y-6">
        
        @if(count($response)>0)
            <div class="space-y-6">
                @foreach($response as $key=>$error)
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        {{-- Header Section --}}
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                
                                <span class="text-sm text-gray-500">
                                    Attempt #{{ $error['retry_count'] }} </span>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- Info Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="flex flex-col space-y-1">
                                    <span class="text-gray-500">Response ID</span>
                                    <span class="font-medium">{{ $error['product_ai_content_response_id'] }}</span>
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <span class="text-gray-500">Created At</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($error['created_at'])->format('Y-m-d H:i:s') }}</span>
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <span class="text-gray-500">Slug</span>
                                    <span class="font-medium">{{ $error['slug'] }}</span>
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <span class="text-gray-500">Updated At</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($error['updated_at'])->format('Y-m-d H:i:s') }}</span>
                                </div>
                            </div>

                            {{-- Error Message --}}
                            <div class="space-y-2">
                                <h4 class="font-medium text-gray-700">Error Message</h4>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <pre class="text-red-700 text-sm whitespace-pre-wrap">{{ $error['error_message'] }}</pre>
                                </div>
                            </div>

                            {{-- Response Data --}}
                            <div class="space-y-2">
                                <h4 class="font-medium text-gray-700">Response Data</h4>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <pre class="text-gray-600 text-sm whitespace-pre-wrap">{{ $error['response_data'] }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Divider between errors --}}
                    @if(!$loop->last)
                        <div class="border-t border-gray-200"></div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No Errors Found</h3>
                <p class="text-gray-500">There are currently no error logs to display.</p>
            </div>
        @endif
    </div>
</div>
</div>