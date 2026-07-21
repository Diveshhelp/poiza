<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-2xl p-12 transform hover:scale-105 transition-transform duration-300">
            <div class="text-center space-y-6">
                <div class="animate-pulse">
                <h1 class="text-8xl font-bold bg-gradient-to-r from-red-500 to-red-700 bg-clip-text text-transparent">402</h1>
                </div>
               
                <div class="space-y-4">
                    <h2 class="text-3xl font-bold text-gray-800">Subscription Expired</h2>
                    <div class="space-y-2">
                        <p class="text-lg text-gray-600">{{ $message ?? 'Your subscription has been expired.' }}</p>
                        <p class="text-md text-gray-500">Please renew your subscription to regain access.</p>
                    </div>
                </div>

                <div class="p-4 bg-amber-50 rounded-lg border border-amber-200 my-2">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-amber-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-700">Access to this feature requires an active subscription plan.</p>
                    </div>
                </div>
                
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('upgrade-account') }}"
                       class="w-full sm:w-auto inline-block px-6 py-3 text-white bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-primary rounded-lg shadow-lg transform hover:-translate-y-1 transition-all duration-300 font-semibold">
                        <div class="flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Renew Subscription</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('document-collections') }}"
                       class="w-full sm:w-auto inline-block px-6 py-3 text-gray-700 border border-gray-300 hover:bg-gray-50 rounded-lg shadow-sm transform hover:-translate-y-1 transition-all duration-300 font-semibold">
                        <div class="flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Return Back</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>