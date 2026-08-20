<!-- Desktop Menu (Hidden on Mobile) -->
<div class="hidden md:block">
    <div class="space-y-1">
        <!-- Prominent Brand Logo Header -->
        <div class="mb-6 px-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="p-2 bg-white dark:bg-slate-800 rounded-xl shadow-md border border-indigo-100 dark:border-indigo-900/50 group-hover:border-indigo-500 transition duration-200 flex items-center justify-center">
                    <img src="{{ asset('logo-.png') }}" alt="Poiza Interior Touch Logo" class="h-9 w-auto object-contain max-w-[140px]" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-600 to-purple-600 text-white items-center justify-center font-black text-sm">P</div>
                </div>
            </a>
        </div>

        <div x-data="{ 
            openGroups: {
                dashboard: true,
                documents: false,
                mydrive: false,
                tasks: false,
                user: false,
                admin: false,
                owner: false,
                rajconsoft:false
            },
            toggleGroup(group) {
                this.openGroups[group] = !this.openGroups[group];
            }
        }">
            <ul class="space-y-1">
                <!-- Dashboard Section (single item, no grouping) -->
                    <li class="border-b border-gray-100 dark:border-gray-800 py-1">
                        <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                            href="{{ route('dashboard') }}">
                            <!-- Dashboard SVG icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                class="size-5 mr-3 relative">
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                    d="M3.75 5.25A2.25 2.25 0 016 3h12a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0118 21H6a2.25 2.25 0 01-2.25-2.25V5.25z" />
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                    d="M7.5 7.5h3v4.5h-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                    d="M10.5 7.5h6v2.25h-6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-yellow-500"
                                    d="M7.5 15.75l2.25-2.25 2.25 2.25 3-3 1.5 1.5" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('product') }}">
                                <!-- Products SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M20.25 7.5l-.625 10.632A2.25 2.25 0 0117.384 20.25H6.616a2.25 2.25 0 01-2.241-2.118L3.75 7.5M10.5 11.25h3M3.75 7.5h16.5M9 3.75h6" />
                                </svg>
                                <span>Products</span>
                            </a>
                        </li>
                       <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('customers') }}">
                                <!-- Customers SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                <span>Customers</span>
                            </a>
                        </li>
                       <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('orders') }}">
                                <!-- Orders SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                </svg>
                                <span>Orders</span>
                            </a>
                        </li>
                        
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('user-content') }}">
                                <!-- Employee SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z" />
                                </svg>
                                <span>Employee</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('my-settings') }}">
                                <!-- Settings SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-cyan-500"
                                        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <span>Settings</span>
                            </a>
                        </li>
                       
                <!-- Owner Setup Section (conditional) -->
                @if(auth()->user()->hasOwnerAccess())
                        <li><hr></li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('expenses.index') }}">
                                <!-- Expenses SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Expenses</span>
                            </a>
                        </li>
                @endif
            </ul>
        </div>
    </div>
</div>

<!-- Mobile Menu -->
<div class="md:hidden" x-data="{ activeSection: documents }">
    <li class="list-none">
        <!-- Documents Section (Mobile) -->
        <div class="border-b border-zinc-200 dark:border-zinc-700">
            <div 
                 x-transition:enter="transition-all ease-out duration-200" 
                 x-transition:enter-start="opacity-0 max-h-0" 
                 x-transition:enter-end="opacity-100 max-h-40"
                 x-transition:leave="transition-all ease-in duration-150" 
                 x-transition:leave-start="opacity-100 max-h-40" 
                 x-transition:leave-end="opacity-0 max-h-0"
                 class="overflow-hidden">
              <ul class="space-y-1">
                <!-- Dashboard Section (single item, no grouping) -->
                   <li class="border-b border-gray-100 dark:border-gray-800 py-1">
                        <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                            href="{{ route('dashboard') }}">
                            <!-- Dashboard SVG icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                class="size-5 mr-3 relative">
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                    d="M3.75 5.25A2.25 2.25 0 016 3h12a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0118 21H6a2.25 2.25 0 01-2.25-2.25V5.25z" />
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                    d="M7.5 7.5h3v4.5h-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                    d="M10.5 7.5h6v2.25h-6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-yellow-500"
                                    d="M7.5 15.75l2.25-2.25 2.25 2.25 3-3 1.5 1.5" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('product') }}">
                                <!-- Products SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M20.25 7.5l-.625 10.632A2.25 2.25 0 0117.384 20.25H6.616a2.25 2.25 0 01-2.241-2.118L3.75 7.5M10.5 11.25h3M3.75 7.5h16.5M9 3.75h6" />
                                </svg>
                                <span>Products</span>
                            </a>
                        </li>
                       <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('customers') }}">
                                <!-- Customers SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                <span>Customers</span>
                            </a>
                        </li>
                       <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('orders') }}">
                                <!-- Orders SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                </svg>
                                <span>Orders</span>
                            </a>
                        </li>
                        
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('user-content') }}">
                                <!-- Employee SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z" />
                                </svg>
                                <span>Employee</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('my-settings') }}">
                                <!-- Settings SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-cyan-500"
                                        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <span>Settings</span>
                            </a>
                        </li>
                       
                <!-- Owner Setup Section (conditional) -->
                @if(auth()->user()->hasOwnerAccess())
                        <li><hr></li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('expenses.index') }}">
                                <!-- Expenses SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Expenses</span>
                            </a>
                        </li>
                @endif
            </ul>
        </div>
    </div>
</div>