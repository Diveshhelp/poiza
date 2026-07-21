<x-guest-layout>
    <!-- Full screen background image -->
    <div class="min-h-screen bg-cover bg-center bg-no-repeat fixed inset-0" style="background-image: url('modern-minimalist-office-black-white_23-2151777550.jpg')">
        <div class="min-h-screen flex flex-col items-center justify-center bg-white/70 dark:bg-slate-800/70 p-4">
            <div class="p-6 overflow-hidden rounded-xl bg-white dark:bg-slate-800 shadow-xl border border-indigo-100 dark:border-indigo-900/50">
                <!-- Simple gradient accent bar -->
                <div class="h-2 w-full bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                
                <div class="px-8 py-8">
                    <div class="flex items-center mb-6">
                        <!-- Lock icon -->
                        <div class="mr-4 bg-indigo-100 dark:bg-indigo-900 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Security Verification
                            </h3>
                            <p class="text-sm text-indigo-600 dark:text-indigo-400">Complete verification to continue</p>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-6 dark:text-gray-300 bg-indigo-50 dark:bg-indigo-900/30 p-3 rounded-lg border-l-4 border-indigo-500 dark:border-indigo-400">
                        For your security, please enter your high security code.
                    </p>
                
                    <x-validation-errors class="mb-4 rounded-lg bg-red-50 p-3 text-red-600 dark:bg-red-900/30 dark:text-red-400" />
                    
                    @session('status')
                    <div class="mb-4 font-medium text-sm bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 p-3 rounded-lg border-l-4 border-green-500">
                        {{ $value }}
                    </div>
                    @endsession
                    
                    <form method="POST" action="{{ route('login.by.code') }}" x-data="{ loading: false }" @submit="loading = true">
                        @csrf

                        <div class="mt-4">
                            <x-label for="email" value="{{ __('Email') }}" class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300" />
                            <x-input 
                                id="email" 
                                class="block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xl tracking-widest text-center font-mono dark:bg-slate-700 dark:border-slate-600 dark:text-white" 
                                type="text" 
                                name="email" 
                                required 
                                value="{{ $email }}"
                                autofocus 
                                placeholder="your@email.com"
                            />
                        </div>
                        
                        <div class="mt-4">
                            <x-label for="security_code" value="{{ __('Your Security Code') }}" class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300" />
                            <x-input 
                                id="security_code" 
                                class="block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xl tracking-widest text-center font-mono dark:bg-slate-700 dark:border-slate-600 dark:text-white" 
                                type="password" 
                                name="security_code" 
                                required 
                                autofocus 
                                placeholder="• • • • • • • • •"
                                maxlength="100"
                                autocomplete="off"
                            />
                        </div>
                        
                        <div class="mt-8">
                            <button 
                                type="submit" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center"
                                :class="{ 'opacity-75 cursor-wait': loading }"
                                :disabled="loading"
                            >
                                <!-- Loading spinner (shows when loading is true) -->
                                <template x-if="loading">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                
                                <!-- Check icon (shows when not loading) -->
                                <template x-if="!loading">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </template>
                                
                                <span x-text="loading ? 'Verifying...' : '{{ __('Verify & Continue') }}'"></span>
                            </button>
                            <div class="text-sm mt-3">
                            <a href="forgot-password" class="font-medium text-blue-600 hover:text-blue-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-150 ease-in-out">
                                {{ __('Forgot password ?') }}
                            </a>
                        </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>