<!-- resources/views/livewire/user-unsubscribe.blade.php -->
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <div class="text-center">
            @if ($status === 'processing')
                <div class="flex justify-center">
                    <svg class="animate-spin h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <p class="mt-4 text-gray-600">Processing your request...</p>
                
            @elseif ($status === 'success')
                <svg class="h-16 w-16 text-green-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                
                <h2 class="mt-4 text-2xl font-bold text-gray-800">Unsubscribed Successfully</h2>
                
                <p class="mt-4 text-gray-600">
                    {{ $message }}
                </p>
                
                @if ($resubscribeStatus === 'success')
                    <div class="mt-4 p-4 bg-green-50 rounded-md text-green-700">
                        {{ $resubscribeMessage }}
                    </div>
                @elseif ($resubscribeStatus === 'error')
                    <div class="mt-4 p-4 bg-red-50 rounded-md text-red-700">
                        {{ $resubscribeMessage }}
                    </div>
                @else
                    <div class="mt-8 p-4 bg-gray-50 rounded-md">
                        <p class="text-gray-700 mb-4">Changed your mind?</p>
                        
                        <button wire:click="resubscribe" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span wire:loading.remove wire:target="resubscribe">Resubscribe</span>
                            <span wire:loading wire:target="resubscribe" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                @endif
                
            @elseif ($status === 'error')
                <svg class="h-16 w-16 text-red-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                
                <h2 class="mt-4 text-2xl font-bold text-gray-800">Unsubscribe Error</h2>
                
                <p class="mt-4 text-gray-600">
                    {{ $message }}
                </p>
                
                <div class="mt-8 p-4 bg-gray-50 rounded-md">
                    <p class="text-gray-700">Need help?</p>
                    <p class="mt-2 text-gray-600">
                        Please contact our customer support team at:
                        <a href="mailto:support@example.com" class="text-blue-600 hover:underline">
                            support@example.com
                        </a>
                    </p>
                </div>
            @endif
            
            <div class="mt-6">
                <a href="{{ url('/') }}" class="text-blue-600 hover:underline">
                    Return to Website
                </a>
            </div>
        </div>
    </div>
</div>
