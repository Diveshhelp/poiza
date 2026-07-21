<x-guest-layout>
    
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Registration Form Side -->
        <div class="w-full md:w-1/2 p-6 md:p-10 flex justify-center rounded-xl shadow-lg relative overflow-hidden">
            <!-- Background image with placeholder since external images aren't allowed -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('/hand-using-laptop-computer-with-virtual-screen-document-online-approve-paperless-quality-assurance-erp-management-concept.jpg'); filter: blur(1px);"></div>
            <div class="w-full max-w-md space-y-8 relative z-10 bg-white dark:bg-gray-900 p-8 rounded-xl shadow-lg backdrop-blur-sm bg-opacity-90 dark:bg-opacity-90">
                <div class="text-center">
                    <x-authentication-card-logo class="mx-auto h-16 w-auto transform hover:scale-105 transition-transform duration-300" />
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                        Create your account
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Start managing your documents efficiently
                    </p>
                </div>
                
                <x-validation-errors class="mb-4" />
                
                <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6" 
                    x-data="{ loading: false }" 
                    @submit="loading = true">    
                    @csrf
                    <div class="rounded-md shadow-sm space-y-5">

                    @if(isset($_GET['code']) && $_GET['code']!="")
                        <div class="group">
                            <x-label for="name" value="{{ __('Referral Email') }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-primary" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-input id="referral_email" readonly class="block mt-1 w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out" type="text" name="referral_email" value="{{ base64_decode($_GET['code']) }}" required autofocus autocomplete="referral_email" placeholder="email@mail.com" />
                            </div>
                        </div>
                    @endif
                        <div class="group">
                            <x-label for="name" value="{{ __('Company / Your Name') }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-primary" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-input id="name" class="block mt-1 w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
                            </div>
                        </div>
                        
                        <div class="group">
                            <x-label for="email" value="{{ __('Work Email') }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-primary" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <x-input id="email" class="block mt-1 w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@company.com" />
                            </div>
                        </div>
                        <div class="group">
                            <x-label for="name" value="{{ __('Mobile Number') }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <!-- Option 4: Mobile Phone with Signal -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-primary" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-input id="mobile" class="block mt-1 w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out" type="number" name="mobile" :value="old('mobile')" required autofocus autocomplete="mobile" placeholder="9876543210" />
                            </div>
                        </div>
                        <div class="group">
                            <x-label for="password" value="{{ __('Security Code') }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-primary" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-input id="password" class="block mt-1 w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                            </div>
                        </div>
                        
                        <div class="group">
                            <x-label for="password_confirmation" value="{{ __('Confirm Security Code') }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-primary" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-input id="password_confirmation" class="block mt-1 w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white transition duration-150 ease-in-out" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                            </div>
                        </div>

                        <!-- Legal disclaimer checkbox -->
                        <div class="group">
                            <x-label for="legal_disclaimer">
                                <div class="flex items-center">
                                    <x-checkbox name="legal_disclaimer" id="legal_disclaimer" required class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" />
                                    <div class="ms-3 text-sm text-gray-600 dark:text-gray-400">
                                        {!! __('I acknowledge that while Docmey implements reasonable security measures, no system is completely secure, and I agree to limit any claims against the service provider in the event of a data breach.') !!}
                                    </div>
                                </div>
                            </x-label>
                            @error('legal_disclaimer')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
    
                    </div>
                    
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <x-label for="terms">
                                <div class="flex items-center">
                                    <x-checkbox name="terms" id="terms" required class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" />
                                    <div class="ms-3 text-sm text-gray-600 dark:text-gray-400">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-primary hover:text-primary-dark rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">'.__('Terms of Service').'</a>',
                                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-primary hover:text-primary-dark rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">'.__('Privacy Policy').'</a>',
                                        ]) !!}
                                    </div>
                                </div>
                            </x-label>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <a href="{{ route('login.by.code.form') }}" class="font-medium text-blue-600 hover:text-blue-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-150 ease-in-out">
                                {{ __('Already registered?') }}
                            </a>
                        </div>
                        
                        <button 
            type="submit" 
            x-bind:disabled="loading"
            class="group relative flex justify-center py-2.5 px-6 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 ease-in-out transform hover:scale-105 shadow-md">
            <span x-show="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
            <span x-bind:class="loading ? 'opacity-0' : ''">{{ __('Register') }}</span>
        </button>
                    </div>
                </form>
                <script>
    // If there are validation errors, the page will reload and this will execute
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form.__x && {{ $errors->any() ? 'true' : 'false' }}) {
            form.__x.$data.loading = false;
        }
    });
</script>
            </div>
        </div>
        <!-- Feature Showcase Side -->
        <div class="w-full md:w-1/2 bg-gradient-to-br from-primary to-primary-dark dark:from-secondary dark:to-secondary-dark p-6 md:p-10 flex items-center justify-center">
            <div class="w-full max-w-lg text-white" x-data="{ currentFeature: 1 }">
                <h2 class="text-3xl font-bold mb-6">{{ env('APP_NAME') }} : <small>Document Management System</small></h2>
                
                <!-- Feature Tabs -->
                <div class="flex space-x-1 mb-6 border-b border-white/20">
                    <button 
                        @click="currentFeature = 1" 
                        :class="{ 'border-b-2 border-white font-bold': currentFeature === 1 }"
                        class="px-4 py-2 text-sm">
                        Organize
                    </button>
                    <button 
                        @click="currentFeature = 2" 
                        :class="{ 'border-b-2 border-white font-bold': currentFeature === 2 }"
                        class="px-4 py-2 text-sm">
                        Collaborate
                    </button>
                    <button 
                        @click="currentFeature = 3" 
                        :class="{ 'border-b-2 border-white font-bold': currentFeature === 3 }"
                        class="px-4 py-2 text-sm">
                        Secure
                    </button>
                    <button 
                        @click="currentFeature = 4" 
                        :class="{ 'border-b-2 border-white font-bold': currentFeature === 4 }"
                        class="px-4 py-2 text-sm">
                        Analyze
                    </button>
                </div>
                
                <!-- Feature Content -->
                <div class="relative overflow-hidden rounded-lg shadow-xl bg-white/10 p-6" style="height: 250px;">
                    <!-- Feature 1: Organize -->
                    <div x-show="currentFeature === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="flex items-start space-x-4">
                            <div class="bg-white/20 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">Intelligent Organization</h3>
                                <p class="mb-4">Our AI-powered system automatically categorizes and tags your documents for easy retrieval.</p>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Smart folders with custom rules</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Powerful search with filters</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Automated document processing</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Feature 2: Collaborate -->
                    <div x-show="currentFeature === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0" >
                        <div class="flex items-start space-x-4">
                            <div class="bg-white/20 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">Seamless Collaboration</h3>
                                <p class="mb-4">Work together with your team in real-time, no matter where they are located.</p>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Real-time document editing</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Version control and history</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Customizable workflows & approvals</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Feature 3: Secure -->
                    <div x-show="currentFeature === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0" >
                        <div class="flex items-start space-x-4">
                            <div class="bg-white/20 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">Enterprise-Grade Security</h3>
                                <p class="mb-4">Your documents are protected with the highest level of security and compliance standards.</p>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>End-to-end encryption</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Granular access controls</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Compliance with GDPR, HIPAA, SOC2</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Feature 4: Analyze -->
                    <div x-show="currentFeature === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0" >
                        <div class="flex items-start space-x-4">
                            <div class="bg-white/20 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">Data Analytics & Insights</h3>
                                <p class="mb-4">Extract valuable insights from your documents with advanced analytics.</p>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Document usage analytics</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Content extraction and summarization</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-white mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Custom dashboards and reports</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Auto Rotation -->
                <div class="mt-6">
                    <div class="w-full bg-white/10 h-1 rounded-full overflow-hidden">
                        <div 
                            x-data="{ width: '25%' }"
                            x-init="
                                $watch('currentFeature', feature => {
                                    width = (feature * 25) + '%';
                                });
                                
                                setInterval(() => {
                                    currentFeature = currentFeature === 4 ? 1 : currentFeature + 1;
                                }, 5000);
                            "
                            :style="`width: ${width}`"
                            class="h-full bg-white transition-all duration-500"></div>
                    </div>
                </div>

                <div class="mt-6 mx-auto max-w-xl">
                <!-- Introduction Text -->
                <div class="text-center mb-8">
                    <h2 class="text-white text-2xl font-bold mb-3">Start Your Journey With Us</h2>
                    <p class="text-white/80 text-sm">Experience our platform with a risk-free trial. No credit card required.</p>
                </div>
                
                <div class="card-container">
                    <div class="group relative rounded-2xl bg-white/10 p-5 shadow-xl ring-1 ring-white/20 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-2xl w-full text-white">
                        <!-- Plan Name -->
                        <h3 class="text-xl font-bold mb-2 text-white">
                            Trial
                        </h3>

                        <!-- Price -->
                        <div class="flex items-baseline mb-3">
                            <span class="text-3xl font-extrabold text-white">
                                0
                            </span>
                            <span class="ml-1 text-base font-medium text-white">INR</span>
                            <span class="ml-2 text-xs font-medium text-white">/month</span>
                        </div>

                        <!-- Description -->
                        <p class="text-sm text-white mb-4">
                            Trial purpose for single user
                        </p>

                        <!-- Feature List -->
                        <ul class="space-y-2 mb-4 text-sm">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-white">1 Single user</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-white">2 GB storage &amp; Auto Backup</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-white">1 Year Trial</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-white flex-shrink-0 mr-2 mt-0.5 opacity-40" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-white opacity-70">DB &amp; File Backup On Google Drive</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-white flex-shrink-0 mr-2 mt-0.5 opacity-40" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-white opacity-70">No Support</span>
                            </li>
                        </ul>
                        
                        <!-- Call to Action Button -->
                        <button class="w-full py-2 px-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg shadow-md hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition-all duration-300">
                            Start Free Trial
                        </button>
                    </div>
                    
                    <!-- Additional Text -->
                    <div class="mt-4 text-center">
                        <p class="text-white/70 text-xs">Try our full-featured plan for 1 year. No credit card required. Upgrade anytime.</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <style>
            @keyframes scroll {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(-50%);
                }
            }
            
            .logo-slider {
                overflow: hidden;
                padding: 60px 0;
                white-space: nowrap;
                position: relative;
            }
            
            .logo-slider:before,
            .logo-slider:after {
                position: absolute;
                top: 0;
                width: 250px;
                height: 100%;
                content: "";
                z-index: 2;
            }
            
            .logo-slider:before {
                left: 0;
                background: linear-gradient(to left, rgba(255, 255, 255, 0), white);
            }
            
            .logo-slider:after {
                right: 0;
                background: linear-gradient(to right, rgba(255, 255, 255, 0), white);
            }
            
            .logo-slide-track {
                animation: scroll 30s linear infinite;
                display: flex;
                width: calc(250px * 14); /* Adjust based on your logo count and size */
            }
            
            .logo-slide {
                width: 250px;
                padding: 0 40px;
            }
            
            /* Pause animation on hover */
            .logo-slider:hover .logo-slide-track {
                animation-play-state: paused;
            }
            
    </style>
    <div class="container mx-auto px-4 py-8 trusted-container">
        <!-- Client Section with Enhanced Tailwind Styling -->
<div class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-gray-50 to-white">
    <!-- Section Header with Decorative Lines -->
    <div class="flex items-center justify-center mb-8">
        <div class="h-1 w-16 bg-indigo-600 rounded-full mr-4 transform transition-all duration-300 hover:w-20"></div>
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">Our Trusted Clients</h2>
        <div class="h-1 w-16 bg-indigo-600 rounded-full ml-4 transform transition-all duration-300 hover:w-20"></div>
    </div>
   
    <!-- Subheading with Badge -->
    <div class="text-center mb-12 max-w-3xl mx-auto">
        <p class="text-gray-600 text-lg mb-4 font-medium">Join over <span class="text-indigo-600 font-bold">32+</span> companies already growing with us</p>
        <div class="inline-flex items-center justify-center space-x-2 p-1 rounded-full bg-white shadow-sm border border-gray-100">
            <div class="px-3 py-1 text-xs font-bold rounded-full bg-indigo-100 text-indigo-800">NEW</div>
            <p class="text-gray-700 text-sm pr-3">Sign up for a trial and get your logo featured below!</p>
        </div>
    </div>

    <!-- Client Logo Grid (Example - You can add your own logos) -->
         <!-- Logo Slider -->
         <div class="logo-slider">
            <div class="logo-slide-track">
                <!-- First set of logos -->
                 @php
                 $logos=App\Models\ClientLogos::get();
                 @endphp
                 @foreach($logos as $k=>$v)
                    <div class="logo-slide">
                        <img src="{{ $v->logo_url }}" alt="Client Logo" class="h-16 mx-auto">
                    </div>
                @endforeach
              
                <div class="logo-slide">
                    <img src="{{ asset('front/your-logos.png') }}" alt="Your Logo" class="h-16 mx-auto">
                </div>
                <div class="logo-slide">
                    <img src="{{ asset('front/your-logos.png') }}" alt="Your Logo" class="h-16 mx-auto">
                </div>
                <div class="logo-slide">
                    <img src="{{ asset('front/your-logos.png') }}" alt="Your Logo" class="h-16 mx-auto">
                </div>
                
                <!-- Duplicate logos for seamless scrolling -->
                @foreach($logos as $k=>$v)
                    <div class="logo-slide">
                        <img src="{{ $v->logo_url }}" alt="Client Logo" class="h-16 mx-auto">
                    </div>
                @endforeach
              
                <div class="logo-slide">
                    <img src="{{ asset('front/your-logos.png') }}" alt="Your Logo" class="h-16 mx-auto">
                </div>
                <div class="logo-slide">
                    <img src="{{ asset('front/your-logos.png') }}" alt="Your Logo" class="h-16 mx-auto">
                </div>
                <div class="logo-slide">
                    <img src="{{ asset('front/your-logos.png') }}" alt="Your Logo" class="h-16 mx-auto">
                </div>
            </div>
        </div>
    </div>
        
        
   
    </div>
    <!-- Statistics and Trial Section -->
    <div class="bg-gray-100 dark:bg-gray-800 py-10 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="mb-12 text-center">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-4">Powering Document Management Worldwide</h3>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Join thousands of businesses that trust our platform for their document management needs.</p>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-6 text-center transform transition-all duration-300 hover:scale-105">
                    <div class="text-3xl font-bold text-primary dark:text-secondary mb-2">1,247+</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300 uppercase tracking-wide">Documents Managed</div>
                </div>
                
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-6 text-center transform transition-all duration-300 hover:scale-105">
                    <div class="text-3xl font-bold text-primary dark:text-secondary mb-2">32+</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300 uppercase tracking-wide">Active Users</div>
                </div>
                
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-6 text-center transform transition-all duration-300 hover:scale-105">
                    <div class="text-3xl font-bold text-primary dark:text-secondary mb-2">42.12GB</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300 uppercase tracking-wide">Storage Used</div>
                </div>
            </div>
            
            <!-- Free Trial CTA -->
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8 mb-12" x-data="{ showForm: false }" id="free-trial">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div class="mb-6 md:mb-0 md:mr-8">
                        <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Not ready to commit?</h4>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Try our 1-year free trial with all features included. No credit card required.</p>
                        <button 
                            @click="showForm = !showForm" 
                            class="px-6 py-2 bg-primary hover:bg-primary-dark text-white font-semibold rounded transition duration-150 ease-in-out"
                            x-text="showForm ? 'Hide Form' : 'Start Free Trial'"
                        ></button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex flex-col items-center">
                            <div class="text-3xl font-bold text-primary dark:text-secondary">1</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Year</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-3xl font-bold text-primary dark:text-secondary">∞</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Features</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-3xl font-bold text-primary dark:text-secondary">$0</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Cost</div>
                        </div>
                    </div>
                </div>
                
                <!-- Trial Form (Only shows when button is clicked) -->
                <div x-show="showForm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="mt-8 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="trial_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                                <input id="trial_name" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white" required>
                            </div>
                            <div>
                                <label for="trial_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Work Email</label>
                                <input id="trial_email" type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
                                <input id="company" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white" required>
                            </div>
                            <div>
                                <label for="employees" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Size</label>
                                <select id="employees" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white" required>
                                    <option value="">Select size</option>
                                    <option value="1-10">1-10 employees</option>
                                    <option value="11-50">11-50 employees</option>
                                    <option value="51-200">51-200 employees</option>
                                    <option value="201-500">201-500 employees</option>
                                    <option value="501+">501+ employees</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center mb-4">
                            <input id="terms" type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary" required>
                            <label for="terms" class="ml-2 block text-sm text-gray-600 dark:text-gray-300">
                                I agree to the <a href="#" class="text-primary hover:text-primary-dark dark:text-secondary dark:hover:text-secondary-light">Terms of Service</a> and <a href="#" class="text-primary hover:text-primary-dark dark:text-secondary dark:hover:text-secondary-light">Privacy Policy</a>
                            </label>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-primary hover:bg-primary-dark text-white font-semibold rounded transition duration-150 ease-in-out">Start My Free Trial</button>
                    </form>
                </div>
            </div>
            @php $plans=config('plans'); @endphp
            <!-- Pricing Tiers Snapshot -->
            <div class="mb-12">
                <h4 class="text-xl font-bold text-center text-gray-800 dark:text-white mb-6">Choose The Right Plan After Your Trial</h4>
                <div class="mt-10 space-y-12">
                    <div class="grid grid-cols-1 gap-y-8 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 xl:gap-x-8">
                        @foreach ($plans as $keyPlan => $myplans)
                                <div class="group relative rounded-2xl bg-white dark:bg-gray-700 p-6 shadow-xl ring-1 ring-gray-900/10 dark:ring-gray-600 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-2xl
                                       ">

                                    <!-- Popular Tag -->
                                    @if($myplans['popular'] ?? false)
                                        <div class="absolute -top-4 inset-x-0 flex justify-center">
                                            <span
                                                class="inline-flex items-center px-4 py-1 rounded-full text-xs font-medium bg-primary text-white shadow-lg">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg>
                                                MOST POPULAR
                                            </span>
                                        </div>
                                    @endif

                                    <div class="{{ isset($myplans['popular']) && $myplans['popular'] ? 'pt-4' : '' }}">
                                        <!-- Plan Name -->
                                        <h3
                                            class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                                            {{ $myplans['name'] }}
                                        </h3>

                                        <!-- Price -->
                                        <div class="flex items-baseline mb-6">
                                            <span
                                                class="text-4xl font-extrabold  text-gray-900 dark:text-white">
                                                {{ $myplans['price'] }}
                                            </span>
                                            <span class="ml-1 text-xl font-medium text-gray-500 dark:text-gray-300">INR</span>
                                            <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">/month</span>
                                        </div>

                                        <!-- Description -->
                                        <p class="text-gray-600 dark:text-gray-300 mb-8">
                                            {{ $myplans['lines'] }}
                                        </p>

                                        <!-- Feature List -->
                                        <ul class="space-y-4 mb-10">
                                            @foreach ($myplans['features'] as $feature)
                                                @if(is_array($feature) && isset($feature['included']))
                                                    <li class="flex items-start">
                                                        @if($feature['included'])
                                                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mr-3 mt-0.5"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="text-gray-700 dark:text-gray-300">{{ $feature['text'] }}</span>
                                                        @else
                                                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mr-3 mt-0.5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="text-gray-400">{{ $feature['text'] }}</span>
                                                        @endif
                                                    </li>
                                                @else
                                                    <li class="flex items-start">
                                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mr-3 mt-0.5"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>

                                       
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
                    