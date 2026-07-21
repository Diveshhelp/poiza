<footer class="mt-4 pt-20 pr-6 lg:pl-[260px] pl-6 text-left bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800">
    <div class="mx-auto w-full max-w-7xl">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-10">
            <!-- Company Info Column -->
            <div class="flex flex-col">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-100">{{ env('APP_NAME') }}</h3>
                </div>
                <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-4 leading-relaxed">
                    Empowering businesses with intelligent solutions for seamless communication and efficient workflows.
                </p>
                <div class="mt-auto">
                    <p class="text-xs text-zinc-500 dark:text-zinc-500">© Copyright {{date('Y')}}. All rights reserved.</p>
                </div>
            </div>

            <!-- Quick Links Column -->
            <div class="flex flex-col">
                <h4 class="font-semibold text-sm text-zinc-800 dark:text-zinc-200 mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                    </svg>
                    Quick Links
                </h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-xs flex items-center text-zinc-600 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact-us') }}" class="text-xs flex items-center text-zinc-600 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            Help Center
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-xs flex items-center text-zinc-600 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            Documentation
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Legal & Connect Column -->
            <div class="flex flex-col">
                <h4 class="font-semibold text-sm text-zinc-800 dark:text-zinc-200 mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Legal
                </h4>
                <ul class="space-y-3 mb-6">
                    <li>
                        <a href="https://deltantec.com/privacy-policy.html" target="_blank" class="text-xs flex items-center text-zinc-600 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Privacy Policy
                        </a>
                    </li>
                    <li>
                        <a href="https://deltantec.com/terms-conditions.html" target="_blank" class="text-xs flex items-center text-zinc-600 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.5 4a1 1 0 100-2h-4a1 1 0 100 2h4zm4-4a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.5 4a1 1 0 100-2h-2a1 1 0 100 2h2z" clip-rule="evenodd" />
                            </svg>
                            Terms of Service
                        </a>
                    </li>
                </ul>
                
                <h4 class="font-semibold text-sm text-zinc-800 dark:text-zinc-200 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                    </svg>
                    Connect With Us
                </h4>
                <div class="flex space-x-3">
                    <a href="https://www.linkedin.com/company/deltan-technologies-india" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white dark:bg-slate-700 shadow-sm hover:shadow-md transition-shadow border border-zinc-100 dark:border-zinc-700 group">
                        <span class="sr-only">LinkedIn</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-indigo-500 dark:text-indigo-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors" viewBox="0 0 16 16">
                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                        </svg>
                    </a>
                    <a href="#" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white dark:bg-slate-700 shadow-sm hover:shadow-md transition-shadow border border-zinc-100 dark:border-zinc-700 group">
                        <span class="sr-only">Twitter</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-indigo-500 dark:text-indigo-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors" viewBox="0 0 16 16">
                            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom Border -->
        <div class="border-t border-zinc-200 dark:border-zinc-800 py-6 flex flex-col sm:flex-row justify-between items-center">
            <p class="text-2xs text-zinc-500 dark:text-zinc-500"><a target="_blank" href="https://deltantec.com">Deltan Technologies.</a></p>
            <p class="text-2xs text-zinc-500 dark:text-zinc-500 mt-2 sm:mt-0">Made with ❤️ for our valued customers</p>
        </div>
    </div>
</footer>