<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-2xl p-12 transform hover:scale-105 transition-transform duration-300">
            <div class="text-center space-y-6">
                <div class="animate-bounce">
                    <h1 class="text-8xl font-bold bg-gradient-to-r from-red-500 to-red-700 bg-clip-text text-transparent">403</h1>
                </div>
                
                <div class="space-y-4">
                    <h2 class="text-3xl font-bold text-gray-800">Access Denied</h2>
                    <div class="space-y-2">
                        <p class="text-lg text-gray-600">Sorry, you don't have permission to access this page.</p>
                        <p class="text-md text-gray-500">This section is restricted to administrators only.</p>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-block px-6 py-3 text-white bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-primary rounded-lg shadow-lg transform hover:-translate-y-1 transition-all duration-300 font-semibold">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Return to Dashboard</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>