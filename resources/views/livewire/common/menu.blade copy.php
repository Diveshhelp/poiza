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
                
                @if(Auth::user()->current_team_id==env('RAJ_TEAM_ID'))
                <!-- Drive Group -->
                <li class="border-b border-gray-100 dark:border-gray-800 py-1">
                    <div @click="toggleGroup('rajconsoft')" 
                        class="flex items-center justify-between py-1 px-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white">
                        <div class="flex items-center">
                            <img src="https://docmey.com/public/raj-favicon.png" class="size-5 mr-3 relative" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            

                            <span class="font-medium">Raj Consultancy</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                            stroke="currentColor" class="size-4 transition-transform duration-200" 
                            :class="openGroups.rajconsoft ? 'transform rotate-180' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    
                    <ul x-show="openGroups.rajconsoft" x-collapse class="ml-6 mt-2 space-y-1">
                        @if(in_array(Auth::user()->user_role,[1,2]))
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('nature-of-work') }}">
                                <img src="https://docmey.com/public/raj-favicon.png" class="size-5 mr-3 relative" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            
                                <small>Nature Of Work</small>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('branches') }}">
                                <img src="https://docmey.com/public/raj-favicon.png" class="size-5 mr-3 relative" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            
                                <small>Branches</small>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('authority') }}">
                                <img src="https://docmey.com/public/raj-favicon.png" class="size-5 mr-3 relative" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            

                                <small>Authority</small>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('tickets') }}">
                                <img src="https://docmey.com/public/raj-favicon.png" class="size-5 mr-3 relative" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            

                                <small>Tickets</small>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('tasks') }}">
                                <img src="https://docmey.com/public/raj-favicon.png" class="size-5 mr-3 relative" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            

                                <small>Tasks</small>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('learning') }}">
                                <img src="https://docmey.com/public/raj-favicon.png" class="size-5 mr-3 relative" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            

                                <small>Learning</small>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                
                <!-- Drive Group -->
                <li class="border-b border-gray-100 dark:border-gray-800 py-1">
                    <div @click="toggleGroup('mydrive')" 
                        class="flex items-center justify-between py-1 px-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="size-5 mr-3">
                                <!-- Top Left Flap -->
                                <polygon fill="#0061FF" points="20,12 32,4 44,12 32,20" />

                                <!-- Top Right Flap -->
                                <polygon fill="#007BEF" points="44,12 56,20 44,28 32,20" />

                                <!-- Bottom Left Flap -->
                                <polygon fill="#339DFF" points="20,28 32,36 44,28 32,20" />

                                <!-- Bottom Right Flap -->
                                <polygon fill="#5ABEFF" points="8,20 20,12 32,20 20,28" />
                            </svg>

                            <span class="font-medium">My Drive</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                            stroke="currentColor" class="size-4 transition-transform duration-200" 
                            :class="openGroups.mydrive ? 'transform rotate-180' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    
                    <ul x-show="openGroups.mydrive" x-collapse class="ml-6 mt-2 space-y-1">
                    <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('my-documents') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="size-5 mr-3" stroke-width="1.5">
                                <!-- Drive base/folder -->
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M3 7.5A1.5 1.5 0 014.5 6h4.75L11 8h8.5A1.5 1.5 0 0121 9.5v8A1.5 1.5 0 0119.5 19h-15A1.5 1.5 0 013 17.5v-10z" />

                                <!-- Cloud symbol -->
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-gray-500"
                                        d="M16.5 14a3.5 3.5 0 00-6.908-1.2 2.25 2.25 0 00-.092 4.45h7a2.25 2.25 0 000-4.5z" />
                                </svg>

                                <span>My Drive</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('deleted-my-documents') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- Document outline -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-red-500"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    
                                    <!-- X mark across document -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M6 18L18 6M6 6l12 12" />
                                    
                                    <!-- Trash can handle (optional) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9" />
                                </svg>
                                <span>Deleted Drive Docs</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Documents Group -->
                <li class="border-b border-gray-100 dark:border-gray-800 py-1">
                    <div @click="toggleGroup('documents')" 
                        class="flex items-center justify-between py-1 px-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                class="size-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                    d="M6.75 7.5V6c0-.828.672-1.5 1.5-1.5h7.5c.828 0 1.5.672 1.5 1.5v1.5m-10.5 0h10.5m-10.5 0A2.25 2.25 0 004.5 9.75v7.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-7.5A2.25 2.25 0 0017.25 7.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                    d="M9.75 11.25v3.75" />
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                    d="M12.75 11.25v3.75" />
                            </svg>
                            <span class="font-medium">Documents</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                            stroke="currentColor" class="size-4 transition-transform duration-200" 
                            :class="openGroups.documents ? 'transform rotate-180' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    
                    <ul x-show="openGroups.documents" x-collapse class="ml-6 mt-2 space-y-1">
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
                                href="{{ route('deleted-documents') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-red-500"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                        d="M6 18L18 6M6 6l12 12" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9" />
                                </svg>
                                <span>Deleted Docs</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Task Group -->
                <li class="border-b border-gray-100 dark:border-gray-800 py-1">
                    <div @click="toggleGroup('tasks')" 
                        class="flex items-center justify-between py-1 px-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white">
                        <div class="flex items-center">
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
                            <span class="font-medium">Tasks & Planning</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                            stroke="currentColor" class="size-4 transition-transform duration-200" 
                            :class="openGroups.tasks ? 'transform rotate-180' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    
                    <ul x-show="openGroups.tasks" x-collapse class="ml-6 mt-2 space-y-1">
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
                                href="{{ route('todos-list') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-cyan-500"
                                        d="M4 6h16M4 6a2 2 0 012-2h12a2 2 0 012 2M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                        d="M7 8h.01M7 12h.01M7 16h.01" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                        d="M9 8h6m-6 4h6m-6 4h6" />
                                </svg>
                                <span>Todos</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('leave-collections') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-green-500"
                                        d="M4 6h16M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-red-500"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-indigo-500"
                                        d="M8 10h8m-8 4h8" />
                                </svg>
                                <span>Leave</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('calendar') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" width="24" height="24" viewBox="0 0 24 24">
                                    <!-- SVG paths (unchanged) -->
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" fill="#E0E0E0"/>
                                    <rect x="3" y="4" width="18" height="4" fill="#1976D2"/>
                                    <line x1="8" y1="2" x2="8" y2="6" stroke="#FFFFFF" stroke-width="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6" stroke="#FFFFFF" stroke-width="2"/>
                                    <line x1="3" y1="10" x2="21" y2="10" stroke="#BDBDBD" stroke-width="1"/>
                                    <circle cx="8" cy="14" r="1.5" fill="#1976D2"/>
                                    <circle cx="12" cy="14" r="1.5" fill="#1976D2"/>
                                    <circle cx="16" cy="14" r="1.5" fill="#1976D2"/>
                                    <circle cx="8" cy="18" r="1.5" fill="#1976D2"/>
                                    <circle cx="12" cy="18" r="1.5" fill="#1976D2"/>
                                    <circle cx="16" cy="18" r="1.5" fill="#1976D2"/>
                                </svg>
                                <span>Calendar</span>
                            </a>
                        </li>
                        @if(auth()->user()->hasAdminAccess())
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('graphs') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="size-5 mr-3">
                                    <!-- SVG paths (unchanged) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-red-500"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                        d="M9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" class="stroke-green-500"
                                        d="M16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                </svg>
                                <span>Graphs</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

                <!-- User Group -->
                <li class="border-b border-gray-100 dark:border-gray-800 py-1">
                    <div @click="toggleGroup('user')" 
                        class="flex items-center justify-between py-1 px-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                class="size-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500" 
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span class="font-medium">User Settings</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                            stroke="currentColor" class="size-4 transition-transform duration-200" 
                            :class="openGroups.user ? 'transform rotate-180' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    
                    <ul x-show="openGroups.user" x-collapse class="ml-6 mt-2 space-y-1">
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('expenses.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-6 h-6 mr-2">
                                    <!-- SVG paths (unchanged) -->
                                    <circle cx="32" cy="32" r="30" fill="#FEECEC" />
                                    <text x="32" y="40" text-anchor="middle" font-size="28" font-weight="bold" fill="#D32F2F" font-family="Arial">₹</text>
                                </svg>
                                <span>My Expenses</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('my-referral') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="none" class="size-6 mr-3" stroke-width="1.5">
                                    <!-- SVG paths (unchanged) -->
                                    <circle cx="8" cy="8" r="2.5" class="stroke-blue-500" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M4 20c0-2.5 2-4.5 4-4.5s4 2 4 4.5" class="stroke-blue-500" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="22" cy="8" r="2.5" class="stroke-indigo-500" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18 20c0-2.5 2-4.5 4-4.5s4 2 4 4.5" class="stroke-indigo-500" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 8h8m0 0l-2-2m2 2l-2 2" class="stroke-green-500" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10 10C13 13 19 13 22 10" stroke="#A7F3D0" stroke-dasharray="2 2" stroke-linecap="round"/>
                                </svg>
                                <span>Referral</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('my-password') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-6 h-6 mr-2" fill="none" stroke-width="2">
                                    <!-- SVG paths (unchanged) -->
                                    <circle cx="32" cy="32" r="30" fill="#E3F2FD"/>
                                    <rect x="20" y="28" width="24" height="20" rx="3" fill="#2196F3"/>
                                    <path d="M24 28v-4a8 8 0 0116 0v4" stroke="#fff" stroke-linecap="round" stroke-linejoin="round"/>
                                    <text x="32" y="44" text-anchor="middle" font-size="16" fill="#ffffff" font-weight="bold" font-family="Arial">*</text>
                                </svg>
                                <span>Password</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                href="{{ route('client-email') }}">
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
                                <span>Client Mail</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Admin Settings Section (conditional) -->
                @if(auth()->user()->hasAdminAccess())
                <li class="border-b border-gray-100 dark:border-gray-800 py-1">
                    <div @click="toggleGroup('admin')" 
                        class="flex items-center justify-between py-1 px-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                class="size-5 mr-3">
                                <!-- Outer gear -->
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-cyan-500"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                    
                                <!-- Center circle -->
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span class="font-medium">Admin Settings</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                            stroke="currentColor" class="size-4 transition-transform duration-200" 
                            :class="openGroups.admin ? 'transform rotate-180' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    
                    <ul x-show="openGroups.admin" x-collapse class="ml-6 mt-2 space-y-1">
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
                                href="{{ route('type-of-work') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" class="size-5 mr-3" height="24" viewBox="0 0 24 24">
                                    <!-- SVG paths (unchanged) -->
                                    <rect x="4" y="3" width="16" height="18" rx="2" ry="2" fill="#F5F5F5" stroke="#BDBDBD" stroke-width="1.5"/>
                                    <rect x="8" y="1" width="8" height="4" rx="1" ry="1" fill="#1976D2"/>
                                    <line x1="7" y1="8" x2="17" y2="8" stroke="#424242" stroke-width="1.5"/>
                                    <line x1="7" y1="12" x2="17" y2="12" stroke="#424242" stroke-width="1.5"/>
                                    <line x1="7" y1="16" x2="17" y2="16" stroke="#424242" stroke-width="1.5"/>
                                    <circle cx="5.5" cy="8" r="1" fill="#4CAF50"/>
                                    <circle cx="5.5" cy="12" r="1" fill="#FFC107"/>
                                    <circle cx="5.5" cy="16" r="1" fill="#F44336"/>
                                </svg>
                                <span>Type of Work</span>
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
                        <li x-data="{ showFileModal: false }">
                            <button 
                                class="w-full flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                                @click="showFileModal = true">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        class="size-5 mr-3">
                                        <!-- SVG paths (unchanged) -->
                                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                            d="M5.625 2.25c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9H5.625Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                            d="M13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9" />
                                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125" />
                                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                            d="M9 13.5h6" />
                                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-400"
                                            d="M9 16.5h4.5" />
                                    </svg>
                                <span>Files Download</span>
                            </button>
                        
                            <!-- File Modal (Keep as-is from original template) -->
                            <div x-show="showFileModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                <div class="flex items-center justify-center min-h-screen px-4">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showFileModal = false"></div>
                                    <div class="relative max-w-md w-full bg-white dark:bg-zinc-800 rounded-lg shadow-xl p-6">
                                        <h3 class="text-lg font-medium mb-3">Download Documents</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Download all documents as ZIP file?</p>
                                        <div x-data="{ cancelLoading: false, downloadLoading: false }" class="flex justify-end gap-3">
                                            <button 
                                                @click="cancelLoading = true; setTimeout(() => { showFileModal = false; cancelLoading = false; }, 800)" 
                                                class="px-3 py-0.5 text-sm bg-gray-200 dark:bg-gray-700 rounded-md relative"
                                                :class="{ 'cursor-wait': cancelLoading }">
                                                <span x-show="!cancelLoading">Cancel</span>
                                                <span x-show="cancelLoading" class="absolute inset-0 flex items-center justify-center">
                                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                            
                                            <button 
                                            
                                                @click="downloadLoading = true; setTimeout(() => { window.location.href='{{ route('download.documents') }}'; showFileModal = false; }, 1400)"
                                                class="px-3 py-0.5 text-sm bg-primary dark:bg-secondary text-white rounded-md relative"
                                                :class="{ 'cursor-wait': downloadLoading }">
                                                <span x-show="!downloadLoading">Download</span>
                                                <span x-show="downloadLoading" class="absolute inset-0 flex items-center justify-center">
                                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Owner Setup Section (conditional) -->
                @if(auth()->user()->hasOwnerAccess())
                <li class="border-b border-gray-100 dark:border-gray-800 py-1">
                    <div @click="toggleGroup('owner')" 
                        class="flex items-center justify-between py-1 px-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" width="24" height="24" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" fill="#8E44AD"/>
                                <path d="M12 7a5 5 0 1 0 5 5" stroke="#F1C40F" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="2" fill="#ECF0F1"/>
                            </svg>
                            <span class="font-medium">Owner Setup</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                            stroke="currentColor" class="size-4 transition-transform duration-200" 
                            :class="openGroups.owner ? 'transform rotate-180' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    
                    <ul x-show="openGroups.owner" x-collapse class="ml-6 mt-2 space-y-1">
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
                    </ul>
                </li>
                @endif
            </ul>
        </div>
</div>

<!-- Mobile Menu -->
<div class="md:hidden" x-data="{ activeSection: null }">
    <li class="list-none">
        <!-- Documents Section (Mobile) -->
        <div class="border-b border-zinc-200 dark:border-zinc-700">
            <button @click="activeSection = (activeSection === 'documents') ? null : 'documents'" 
                    class="w-full flex justify-between items-center py-3 px-4 text-zinc-900 dark:text-white">
                <span class="font-medium uppercase text-sm">Documents</span>
                <svg xmlns="http://www.w3.org/2000/svg" 
                     :class="{'rotate-180': activeSection === 'documents'}" 
                     class="h-5 w-5 transform transition-transform duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="activeSection === 'documents'" 
                 x-transition:enter="transition-all ease-out duration-200" 
                 x-transition:enter-start="opacity-0 max-h-0" 
                 x-transition:enter-end="opacity-100 max-h-40"
                 x-transition:leave="transition-all ease-in duration-150" 
                 x-transition:leave-start="opacity-100 max-h-40" 
                 x-transition:leave-end="opacity-0 max-h-0"
                 class="overflow-hidden">
                <ul class="py-2">
                    <!-- Doc Master -->
                    <li>
                        <a href="{{ route('document-collections') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="size-5 mr-3 relative">
                            
                            <!-- Main clipboard outline with gradient -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                d="M6.75 7.5V6c0-.828.672-1.5 1.5-1.5h7.5c.828 0 1.5.672 1.5 1.5v1.5m-10.5 0h10.5m-10.5 0A2.25 2.25 0 004.5 9.75v7.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-7.5A2.25 2.25 0 0017.25 7.5" />
                            
                            <!-- Left vertical checklist line with different color -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                d="M9.75 11.25v3.75" />
                            
                            <!-- Right vertical checklist line with different color -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                d="M12.75 11.25v3.75" />
                        </svg>
                        
                        <span>Doc Master</span>

                        </a>
                    </li>
                    
                    <!-- Doc Categories -->
                    <li>
                        <a href="{{ route('doc-categories') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="size-5 mr-3">
                            <!-- Horizontal lines at top and bottom -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                d="M3.75 21h16.5M4.5 3h15" />
                            
                            <!-- Left building outline -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                d="M5.25 3v18" />
                                
                            <!-- Right building outline -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-cyan-500"
                                d="M18.75 3v18" />
                                
                            <!-- Left building windows -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                d="M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5" />
                                
                            <!-- Right building windows -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                d="M13.5 6.75H15m-1.5 3H15m-1.5 3H15" />
                                
                            <!-- Door/entrance -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                d="M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                        <span>Doc Categories</span>

                        </a>
                    </li>

                    <!-- Doc Deleted -->
                    <li>
                        <a href="{{ route('deleted-documents') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="size-5 mr-3">
                            <!-- Document outline -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-red-500"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            
                            <!-- X mark across document -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                d="M6 18L18 6M6 6l12 12" />
                            
                            <!-- Trash can handle (optional) -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9" />
                        </svg>
                        <span>Deleted Docs</span>

                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-b border-zinc-200 dark:border-zinc-700">
            <button @click="activeSection = (activeSection === 'main') ? null : 'main'" 
                    class="w-full flex justify-between items-center py-3 px-4 text-zinc-900 dark:text-white">
                <span class="font-medium uppercase text-sm">Main Menu</span>
                <svg xmlns="http://www.w3.org/2000/svg" 
                     :class="{'rotate-180': activeSection === 'main'}" 
                     class="h-5 w-5 transform transition-transform duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="activeSection === 'main'" 
                 x-transition:enter="transition-all ease-out duration-200" 
                 x-transition:enter-start="opacity-0 max-h-0" 
                 x-transition:enter-end="opacity-100 max-h-96"
                 x-transition:leave="transition-all ease-in duration-150" 
                 x-transition:leave-start="opacity-100 max-h-96" 
                 x-transition:leave-end="opacity-0 max-h-0"
                 class="overflow-hidden">
                <ul class="py-2">
                    <!-- Tasks -->
                    <li>
                        <a href="{{ route('task-collections') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="size-5 mr-3">
                            <!-- Card outline -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                d="M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                            
                            <!-- List items -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                d="M15 9h3.75M15 12h3.75M15 15h3.75" />
                                
                            <!-- User circle -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                d="M10.5 9.375a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Z" />
                                
                            <!-- User body -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                d="M11.794 15.711a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                        </svg>
                        <span>Tasks</span>
                        </a>
                    </li>
                    
                    <!-- Todos -->
                    <li>
                        <a href="{{ route('todos-list') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="size-5 mr-3">
                            <!-- Outline -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-cyan-500"
                                d="M4 6h16M4 6a2 2 0 012-2h12a2 2 0 012 2M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6" />
                            
                            <!-- Bullet points -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-500"
                                d="M7 8h.01M7 12h.01M7 16h.01" />
                                
                            <!-- List lines -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                d="M9 8h6m-6 4h6m-6 4h6" />
                        </svg>
                        <span>Todos</span>

                        </a>
                    </li>
                    
                    <!-- Leave -->
                    <li>
                        <a href="{{ route('leave-collections') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="size-5 mr-3">
                            <!-- Box outline -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-green-500"
                                d="M4 6h16M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                            
                            <!-- Door/exit icon -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-red-500"
                                d="M17 16l4-4m0 0l-4-4m4 4H7" />
                                
                            <!-- Inner dividers -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-indigo-500"
                                d="M8 10h8m-8 4h8" />
                        </svg>
                        <span>Leave</span>

                        </a>
                    </li>
                    
                    <!-- Calendar -->
                    <li>
                        <a href="{{ route('calendar') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg"  class="size-5 mr-3" width="24" height="24" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" fill="#E0E0E0"/>
                                <rect x="3" y="4" width="18" height="4" fill="#1976D2"/>
                                <line x1="8" y1="2" x2="8" y2="6" stroke="#FFFFFF" stroke-width="2"/>
                                <line x1="16" y1="2" x2="16" y2="6" stroke="#FFFFFF" stroke-width="2"/>
                                <line x1="3" y1="10" x2="21" y2="10" stroke="#BDBDBD" stroke-width="1"/>
                                <circle cx="8" cy="14" r="1.5" fill="#1976D2"/>
                                <circle cx="12" cy="14" r="1.5" fill="#1976D2"/>
                                <circle cx="16" cy="14" r="1.5" fill="#1976D2"/>
                                <circle cx="8" cy="18" r="1.5" fill="#1976D2"/>
                                <circle cx="12" cy="18" r="1.5" fill="#1976D2"/>
                                <circle cx="16" cy="18" r="1.5" fill="#1976D2"/>
                                </svg>
                                <span>Calendar</span>
                        </a>
                    </li>
                    
                    @if(auth()->user()->hasAdminAccess())
                    <!-- Graphs - Admin only -->
                    <li>
                        <a href="{{ route('graphs') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="size-5 mr-3">
                            <!-- First bar -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-red-500"
                                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75Z" />
                                
                            <!-- Second bar -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                d="M9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625Z" />
                                
                            <!-- Third bar -->
                            <path stroke-linecap="round" stroke-linejoin="round" class="stroke-green-500"
                                d="M16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                        <span>Graphs</span>

                        </a>
                    </li>
                    @endif

                    <li>
                    <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                        href="{{ route('expenses.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-6 h-6 mr-2">
                        <!-- Soft circular background -->
                        <circle cx="32" cy="32" r="30" fill="#FEECEC" />

                        <!-- ₹ symbol -->
                        <text x="32" y="40" text-anchor="middle" font-size="28" font-weight="bold" fill="#D32F2F" font-family="Arial">₹</text>
                        </svg>


                        <span>My Expenses</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                        href="{{ route('my-referral') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="none" class="size-6 mr-3" stroke-width="1.5">
                        <!-- Referrer Head -->
                        <circle cx="8" cy="8" r="2.5" class="stroke-blue-500" stroke-linecap="round" stroke-linejoin="round"/>

                        <!-- Referrer Body -->
                        <path d="M4 20c0-2.5 2-4.5 4-4.5s4 2 4 4.5" class="stroke-blue-500" stroke-linecap="round" stroke-linejoin="round"/>

                        <!-- Referee Head -->
                        <circle cx="22" cy="8" r="2.5" class="stroke-indigo-500" stroke-linecap="round" stroke-linejoin="round"/>

                        <!-- Referee Body -->
                        <path d="M18 20c0-2.5 2-4.5 4-4.5s4 2 4 4.5" class="stroke-indigo-500" stroke-linecap="round" stroke-linejoin="round"/>

                        <!-- Referral Arrow -->
                        <path d="M12 8h8m0 0l-2-2m2 2l-2 2" class="stroke-green-500" stroke-linecap="round" stroke-linejoin="round"/>

                        <!-- Dotted Path (optional visual connection) -->
                        <path d="M10 10C13 13 19 13 22 10" stroke="#A7F3D0" stroke-dasharray="2 2" stroke-linecap="round"/>
                        </svg>

                        <span>Referral</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center py-0.5 px-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md text-gray-700 dark:text-zinc-400 dark:hover:text-white"
                        href="{{ route('my-password') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-6 h-6 mr-2" fill="none" stroke-width="2">
                        <!-- Outer circle (background) -->
                        <circle cx="32" cy="32" r="30" fill="#E3F2FD"/>

                        <!-- Lock body -->
                        <rect x="20" y="28" width="24" height="20" rx="3" fill="#2196F3"/>

                        <!-- Lock shackle -->
                        <path d="M24 28v-4a8 8 0 0116 0v4" stroke="#fff" stroke-linecap="round" stroke-linejoin="round"/>

                        <!-- Asterisk / password dot symbol -->
                        <text x="32" y="44" text-anchor="middle" font-size="16" fill="#ffffff" font-weight="bold" font-family="Arial">*</text>
                        </svg>
                        <span>Password</span>
                    </a>
                </li>
                </ul>
            </div>
        </div>
        
        @if(auth()->user()->hasAdminAccess())
        <!-- Settings Section (Mobile) -->
        <div class="border-b border-zinc-200 dark:border-zinc-700">
            <button @click="activeSection = (activeSection === 'settings') ? null : 'settings'" 
                    class="w-full flex justify-between items-center py-3 px-4 text-zinc-900 dark:text-white">
                <span class="font-medium uppercase text-sm">Settings</span>
                <svg xmlns="http://www.w3.org/2000/svg" 
                     :class="{'rotate-180': activeSection === 'settings'}" 
                     class="h-5 w-5 transform transition-transform duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="activeSection === 'settings'" 
                 x-transition:enter="transition-all ease-out duration-200" 
                 x-transition:enter-start="opacity-0 max-h-0" 
                 x-transition:enter-end="opacity-100 max-h-96"
                 x-transition:leave="transition-all ease-in duration-150" 
                 x-transition:leave-start="opacity-100 max-h-96" 
                 x-transition:leave-end="opacity-0 max-h-0"
                 class="overflow-hidden">
                <ul class="py-2">
                    <!-- Departments -->
                    <li>
                        <a href="{{ route('departments') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        class="size-5 mr-3">
                        <!-- Horizontal lines at top and bottom -->
                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-indigo-500"
                            d="M3.75 21h16.5M4.5 3h15" />
                        
                        <!-- Left building outline -->
                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-teal-500"
                            d="M5.25 3v18" />
                            
                        <!-- Right building outline -->
                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-teal-500"
                            d="M18.75 3v18" />
                            
                        <!-- Left building windows -->
                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                            d="M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5" />
                            
                        <!-- Right building windows -->
                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                            d="M13.5 6.75H15m-1.5 3H15m-1.5 3H15" />
                            
                        <!-- Door/entrance -->
                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                            d="M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    <span>Departments</span>
                        </a>
                    </li>
                    
                    <!-- Type of Work -->
                    <li>
                        <a href="{{ route('type-of-work') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" class="size-5 mr-3" height="24" viewBox="0 0 24 24">
                            <rect x="4" y="3" width="16" height="18" rx="2" ry="2" fill="#F5F5F5" stroke="#BDBDBD" stroke-width="1.5"/>
                            <rect x="8" y="1" width="8" height="4" rx="1" ry="1" fill="#1976D2"/>
                            <line x1="7" y1="8" x2="17" y2="8" stroke="#424242" stroke-width="1.5"/>
                            <line x1="7" y1="12" x2="17" y2="12" stroke="#424242" stroke-width="1.5"/>
                            <line x1="7" y1="16" x2="17" y2="16" stroke="#424242" stroke-width="1.5"/>
                            <circle cx="5.5" cy="8" r="1" fill="#4CAF50"/>
                            <circle cx="5.5" cy="12" r="1" fill="#FFC107"/>
                            <circle cx="5.5" cy="16" r="1" fill="#F44336"/>
                        </svg>
                    <span>Type of Work</span>

                        </a>
                    </li>
                    
                    <!-- User -->
                    <li>
                        <a href="{{ route('user-content') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        class="size-5 mr-3">
                        <!-- Head/avatar circle -->
                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                            
                        <!-- Body/user shape -->
                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                            d="M4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span>User</span>

                        </a>
                    </li>
                    
                    <!-- Settings -->
                    <li>
                        <a href="{{ route('my-settings') }}" 
                           class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        class="size-5 mr-3">
                        <!-- Outer gear -->
                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-cyan-500"
                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            
                        <!-- Center circle -->
                        <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span>Settings</span>

                        </a>
                    </li>
                </ul>
            </div>
        </div>
        

        <div class="border-b border-zinc-200 dark:border-zinc-700">
            <button @click="activeSection = (activeSection === 'download') ? null : 'download'" 
                    class="w-full flex justify-between items-center py-3 px-4 text-zinc-900 dark:text-white">
                <span class="font-medium uppercase text-sm">Download</span>
                <svg xmlns="http://www.w3.org/2000/svg" 
                     :class="{'rotate-180': activeSection === 'download'}" 
                     class="h-5 w-5 transform transition-transform duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="activeSection === 'download'" 
                 x-transition:enter="transition-all ease-out duration-200" 
                 x-transition:enter-start="opacity-0 max-h-0" 
                 x-transition:enter-end="opacity-100 max-h-40"
                 x-transition:leave="transition-all ease-in duration-150" 
                 x-transition:leave-start="opacity-100 max-h-40" 
                 x-transition:leave-end="opacity-0 max-h-0"
                 class="overflow-hidden">
                 <ul class="py-2">
                    <!-- File Download -->
                    <li x-data="{ showFileModal: false }">
                        <!-- File Button -->
                        <button @click="downloadLoading = true; window.location.href='{{ route('download.documents') }}'"
                                class="w-full flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                class="size-5 mr-3">
                                <!-- Main document body -->
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-500"
                                    d="M5.625 2.25c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9H5.625Z" />
                                
                                <!-- Folded corner -->
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-purple-500"
                                    d="M13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9" />
                                
                                <!-- Top elements -->
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-emerald-500"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125" />
                                
                                <!-- Header line -->
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-amber-500"
                                    d="M9 13.5h6" />
                                
                                <!-- Content lines (optional) -->
                                <path stroke-linecap="round" stroke-linejoin="round" class="stroke-pink-400"
                                    d="M9 16.5h4.5" />
                            </svg>
                            <span>Files Download</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        @endif
        @if(auth()->user()->hasOwnerAccess())
        <!-- Documents Section (Mobile) -->
        <div class="border-b border-zinc-200 dark:border-zinc-700">
            <button @click="activeSection = (activeSection === 'owns') ? null : 'owns'" 
                    class="w-full flex justify-between items-center py-3 px-4 text-zinc-900 dark:text-white">
                <span class="font-medium uppercase text-sm">Owners</span>
                <svg xmlns="http://www.w3.org/2000/svg" 
                    :class="{'rotate-180': activeSection === 'owns'}" 
                    class="h-5 w-5 transform transition-transform duration-200" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="activeSection === 'owns'" 
                x-transition:enter="transition-all ease-out duration-200" 
                x-transition:enter-start="opacity-0 max-h-0" 
                x-transition:enter-end="opacity-100 max-h-40"
                x-transition:leave="transition-all ease-in duration-150" 
                x-transition:leave-start="opacity-100 max-h-40" 
                x-transition:leave-end="opacity-0 max-h-0"
                class="overflow-hidden">
                
                    <ul class="py-2">
                        <li>
                            <a class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                href="{{ route('dropbox') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" width="24" height="24" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#8E44AD"/> <!-- Purple circle -->
                                    <path d="M12 7a5 5 0 1 0 5 5" stroke="#F1C40F" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="12" r="2" fill="#ECF0F1"/> <!-- Inner dot -->
                                </svg>

                                <span>Dropbox Token</span>
                            </a>
                        </li>
                        <LI>
                            <a class="flex items-center px-4 py-3 text-sm text-zinc-800 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                href="{{ route('private-subscription') }}">
                                
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" viewBox="0 0 24 24" width="24" height="24">
                                <!-- Bill background -->
                                <rect x="2" y="6" width="20" height="12" rx="2" fill="#81C784"/>
                                <!-- Dollar symbol -->
                                <path d="M12 9v6m-2-4h4a1.5 1.5 0 0 1 0 3h-4a1.5 1.5 0 0 1 0-3z" stroke="#1B5E20" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                <!-- Left coin -->
                                <circle cx="5" cy="12" r="1.5" fill="#66BB6A"/>
                                <!-- Right coin -->
                                <circle cx="19" cy="12" r="1.5" fill="#66BB6A"/>
                                </svg>

                                <span>Subscriptions</span>
                            </a>
                        </li>
                    </ul>
            </div>
        </div>
        @endif
    </li>
</div>