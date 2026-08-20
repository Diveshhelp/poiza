<x-guest-layout>
    <!-- Full screen background image with dark overlay -->
    <div class="min-h-screen bg-cover bg-center bg-no-repeat fixed inset-0 flex items-center justify-center p-3 sm:p-4 overflow-y-auto" style="background-image: url('modern-minimalist-office-black-white_23-2151777550.jpg')">
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>

        <!-- Main Card Container: compact, centered, and fully fit for mobile/desktop screens -->
        <div class="relative z-10 w-full max-w-sm sm:max-w-md my-auto overflow-hidden rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl shadow-2xl border border-indigo-100/50 dark:border-slate-800">
            <!-- Simple gradient accent bar -->
            <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
            
            <div class="p-6 sm:p-8">
                
                <!-- Logo & Brand Header Area (Clean, Prominent & Perfectly Scaled) -->
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="mb-3 inline-flex items-center justify-center p-3 rounded-2xl bg-white dark:bg-slate-800 shadow-md border border-gray-100 dark:border-slate-700">
                        <img src="{{ asset('logo-.png') }}" alt="Diora Enterprise Logo" class="w-20 sm:w-24 h-auto object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div style="display: none;" class="w-12 h-12 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white items-center justify-center text-xl font-black tracking-tighter">
                            D
                        </div>
                    </div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        By Artistic Metal
                    </h2>
                    <p class="text-[11px] uppercase tracking-widest text-indigo-600 dark:text-indigo-400 font-semibold mt-0.5">
                        Security Verification Portal
                    </p>
                </div>

                <!-- Security Info Notice -->
                <div class="flex items-start gap-2.5 mb-5 text-xs text-gray-600 dark:text-gray-300 bg-indigo-50/80 dark:bg-indigo-950/40 p-3 rounded-xl border border-indigo-100 dark:border-indigo-900/50">
                    <div class="p-1 bg-indigo-500 text-white rounded-lg shrink-0 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <p class="leading-relaxed">
                        For security compliance, please enter your credentials and assigned security code to proceed.
                    </p>
                </div>
            
                <x-validation-errors class="mb-4 rounded-xl bg-red-50 p-2.5 text-red-600 dark:bg-red-900/30 dark:text-red-400 text-xs" />
                
                @session('status')
                <div class="mb-4 font-medium text-xs bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 p-2.5 rounded-xl border border-emerald-200">
                    {{ $value }}
                </div>
                @endsession
                
                <form method="POST" action="{{ route('login.by.code') }}" x-data="{ loading: false }" @submit="loading = true" class="space-y-3.5">
                    @csrf

                    <div>
                        <x-label for="email" value="{{ __('Email Address') }}" class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1 dark:text-gray-300" />
                        <x-input 
                            id="email" 
                            class="block w-full rounded-xl border-gray-300 bg-gray-50/50 focus:bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm font-medium dark:bg-slate-800 dark:border-slate-700 dark:text-white py-2.5" 
                            type="email" 
                            name="email" 
                            required 
                            value="{{ $email }}"
                            autofocus 
                            placeholder="name@company.com"
                        />
                    </div>
                    
                    <div>
                        <x-label for="security_code" value="{{ __('Security Code') }}" class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1 dark:text-gray-300" />
                        <x-input 
                            id="security_code" 
                            class="block w-full rounded-xl border-gray-300 bg-gray-50/50 focus:bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base tracking-widest text-center font-mono dark:bg-slate-800 dark:border-slate-700 dark:text-white py-2.5" 
                            type="password" 
                            name="security_code" 
                            required 
                            placeholder="••••••••••••"
                            maxlength="100"
                            autocomplete="off"
                        />
                    </div>
                    
                    <div class="pt-1">
                        <button 
                            type="submit" 
                            class="w-full px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2"
                            :class="{ 'opacity-75 cursor-wait': loading }"
                            :disabled="loading"
                        >
                            <!-- Loading spinner -->
                            <template x-if="loading">
                                <svg class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            
                            <!-- Check icon -->
                            <template x-if="!loading">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </template>
                            
                            <span x-text="loading ? 'Verifying...' : '{{ __('Verify & Continue') }}'"></span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between pt-1 text-[11px]">
                        <a href="forgot-password" class="font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-150 ease-in-out">
                            {{ __('Forgot password?') }}
                        </a>
                        <span class="text-gray-400 dark:text-slate-500">256-bit Secure SSL</span>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</x-guest-layout>