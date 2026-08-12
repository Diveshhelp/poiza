<!-- Desktop Menu (Hidden on Mobile) -->
<div class="hidden md:block">
<div class="space-y-1">
        @php
            $initialLoadData = App\Models\TeamSettings::where("team_id",Auth::user()->currentTeam->id)->first()->app_name ?? '';
        @endphp

            <div class="mb-5">
                <div class="inline-flex items-center">
                <span class="text-2xl font-extrabold text-indigo-600 tracking-tight">
                        @if($initialLoadData)
                            <span class="flex items-center">
                                <span class="text-indigo-800">{{ substr($initialLoadData, 0, 1) }}</span>{{ substr($initialLoadData, 1) }}
                            </span>
                        @else
                            <span>{{env('APP_NAME')}}</span>
                        @endif
                    </span>
                </div>
            </div>
          
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
                            <!-- Dashboard SVG icon (unchanged) -->
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M6.75 7.5V6c0-.828.672-1.5 1.5-1.5h7.5c.828 0 1.5.672 1.5 1.5v1.5m-10.5 0h10.5m-10.5 0A2.25 2.25 0 004.5 9.75v7.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-7.5A2.25 2.25 0 0017.25 7.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                        d="M9.75 11.25v3.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                        d="M12.75 11.25v3.75" />
                                </svg>
                                <span>Products</span>
                            </a>
                        </li>
                         <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('customers') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M6.75 7.5V6c0-.828.672-1.5 1.5-1.5h7.5c.828 0 1.5.672 1.5 1.5v1.5m-10.5 0h10.5m-10.5 0A2.25 2.25 0 004.5 9.75v7.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-7.5A2.25 2.25 0 0017.25 7.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                        d="M9.75 11.25v3.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                        d="M12.75 11.25v3.75" />
                                </svg>
                                <span>Customers</span>
                            </a>
                        </li>
                         <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('orders') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M6.75 7.5V6c0-.828.672-1.5 1.5-1.5h7.5c.828 0 1.5.672 1.5 1.5v1.5m-10.5 0h10.5m-10.5 0A2.25 2.25 0 004.5 9.75v7.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-7.5A2.25 2.25 0 0017.25 7.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                        d="M9.75 11.25v3.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                        d="M12.75 11.25v3.75" />
                                </svg>
                                <span>Orders</span>
                            </a>
                        </li>
                        
                        <!-- <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('document-collections') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M6.75 7.5V6c0-.828.672-1.5 1.5-1.5h7.5c.828 0 1.5.672 1.5 1.5v1.5m-10.5 0h10.5m-10.5 0A2.25 2.25 0 004.5 9.75v7.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-7.5A2.25 2.25 0 0017.25 7.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                        d="M9.75 11.25v3.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                        d="M12.75 11.25v3.75" />
                                </svg>
                                <span>Documents</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('doc-categories') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M3.75 21h16.5M4.5 3h15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M5.25 3v18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-cyan-500"
                                        d="M18.75 3v18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                        d="M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M13.5 6.75H15m-1.5 3H15m-1.5 3H15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                        d="M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                                <span>Doc Categories</span>
                            </a>
                        </li>
                       -->
                        <!-- <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('task-collections') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M15 9h3.75M15 12h3.75M15 15h3.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                        d="M10.5 9.375a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M11.794 15.711a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                </svg>
                                <span>Tasks</span>
                            </a>
                        </li>

                      
                       
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('departments') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-indigo-500"
                                        d="M3.75 21h16.5M4.5 3h15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-teal-500"
                                        d="M5.25 3v18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-teal-500"
                                        d="M18.75 3v18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M13.5 6.75H15m-1.5 3H15m-1.5 3H15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                                <span>Departments</span>
                            </a>
                        </li>
                  -->
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('user-content') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span>Employee</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('my-settings') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M3.75 21h16.5M4.5 3h15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M5.25 3v18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-cyan-500"
                                        d="M18.75 3v18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                        d="M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M13.5 6.75H15m-1.5 3H15m-1.5 3H15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                        d="M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                                <span>Expenses</span>
                            </a>
                        </li>
                        <!-- <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('resume') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span>Resume</span>
                            </a>
                        </li> -->
                        <!-- <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('dropbox') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" width="24" height="24" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#8E44AD"/>
                                    <path d="M12 7a5 5 0 1 0 5 5" stroke="#F1C40F" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="12" r="2" fill="#ECF0F1"/>
                                </svg>
                                <span>Dropbox Token</span>
                            </a>
                        </li> 
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('private-subscription') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" viewBox="0 0 24 24" width="24" height="24">
                                <rect x="2" y="6" width="20" height="12" rx="2" fill="#81C784"/>
                                <path d="M12 9v6m-2-4h4a1.5 1.5 0 0 1 0 3h-4a1.5 1.5 0 0 1 0-3z" stroke="#1B5E20" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="5" cy="12" r="1.5" fill="#66BB6A"/>
                                <circle cx="19" cy="12" r="1.5" fill="#66BB6A"/>
                                </svg>
                                <span>Subscriptions</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('group-email') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" viewBox="0 0 24 24" width="24" height="24">
                                <circle cx="6" cy="8" r="2" fill="#42A5F5"/>
                                <circle cx="12" cy="6" r="2" fill="#1E88E5"/>
                                <circle cx="18" cy="8" r="2" fill="#42A5F5"/>
                                <path d="M3 14c0-1.66 1.34-3 3-3s3 1.34 3 3" fill="none" stroke="#42A5F5" stroke-width="1.5"/>
                                <path d="M9 13c0-1.66 1.34-3 3-3s3 1.34 3 3" fill="none" stroke="#1E88E5" stroke-width="1.5"/>
                                <path d="M15 14c0-1.66 1.34-3 3-3s3 1.34 3 3" fill="none" stroke="#42A5F5" stroke-width="1.5"/>
                                <rect x="4" y="15" width="16" height="6" rx="1" fill="#90CAF9"/>
                                <path d="M4 15l8 4 8-4" stroke="#1E88E5" stroke-width="1.5" fill="none"/>
                                </svg>
                                <span>Group Mail</span>
                            </a>
                        </li>-->
                        
                @endif
            </ul>
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
                        <!-- Dashboard SVG icon (unchanged) -->
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
                                href="{{ route('document-collections') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3 relative">
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M6.75 7.5V6c0-.828.672-1.5 1.5-1.5h7.5c.828 0 1.5.672 1.5 1.5v1.5m-10.5 0h10.5m-10.5 0A2.25 2.25 0 004.5 9.75v7.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-7.5A2.25 2.25 0 0017.25 7.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                        d="M9.75 11.25v3.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                        d="M12.75 11.25v3.75" />
                                </svg>
                                <span>Doc Master</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('doc-categories') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M3.75 21h16.5M4.5 3h15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M5.25 3v18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-cyan-500"
                                        d="M18.75 3v18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                        d="M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M13.5 6.75H15m-1.5 3H15m-1.5 3H15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                        d="M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                                <span>Doc Categories</span>
                            </a>
                        </li>
                       
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('task-collections') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M15 9h3.75M15 12h3.75M15 15h3.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                        d="M10.5 9.375a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M11.794 15.711a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                </svg>
                                <span>Tasks</span>
                            </a>
                        </li>

                       
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('departments') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-indigo-500"
                                        d="M3.75 21h16.5M4.5 3h15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-teal-500"
                                        d="M5.25 3v18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-teal-500"
                                        d="M18.75 3v18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M13.5 6.75H15m-1.5 3H15m-1.5 3H15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                                <span>Departments</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('user-content') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span>User</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('my-settings') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
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
               
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('dropbox') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" width="24" height="24" viewBox="0 0 24 24">
                                    <!-- SVG paths (unchanged) -->
                                    <circle cx="12" cy="12" r="10" fill="#8E44AD"/>
                                    <path d="M12 7a5 5 0 1 0 5 5" stroke="#F1C40F" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="12" r="2" fill="#ECF0F1"/>
                                </svg>
                                <span>Dropbox Token</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('private-subscription') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" viewBox="0 0 24 24" width="24" height="24">
                                <!-- SVG paths (unchanged) -->
                                <rect x="2" y="6" width="20" height="12" rx="2" fill="#81C784"/>
                                <path d="M12 9v6m-2-4h4a1.5 1.5 0 0 1 0 3h-4a1.5 1.5 0 0 1 0-3z" stroke="#1B5E20" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="5" cy="12" r="1.5" fill="#66BB6A"/>
                                <circle cx="19" cy="12" r="1.5" fill="#66BB6A"/>
                                </svg>
                                <span>Subscriptions</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('group-email') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" viewBox="0 0 24 24" width="24" height="24">
                                <!-- SVG paths (unchanged) -->
                                <circle cx="6" cy="8" r="2" fill="#42A5F5"/>
                                <circle cx="12" cy="6" r="2" fill="#1E88E5"/>
                                <circle cx="18" cy="8" r="2" fill="#42A5F5"/>
                                <path d="M3 14c0-1.66 1.34-3 3-3s3 1.34 3 3" fill="none" stroke="#42A5F5" stroke-width="1.5"/>
                                <path d="M9 13c0-1.66 1.34-3 3-3s3 1.34 3 3" fill="none" stroke="#1E88E5" stroke-width="1.5"/>
                                <path d="M15 14c0-1.66 1.34-3 3-3s3 1.34 3 3" fill="none" stroke="#42A5F5" stroke-width="1.5"/>
                                <rect x="4" y="15" width="16" height="6" rx="1" fill="#90CAF9"/>
                                <path d="M4 15l8 4 8-4" stroke="#1E88E5" stroke-width="1.5" fill="none"/>
                                </svg>
                                <span>Group Mail</span>
                            </a>
                        </li>
                @endif
            </ul>
            </div>
        </div>
    </li>
</div>