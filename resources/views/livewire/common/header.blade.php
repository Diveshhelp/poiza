<nav class="fixed inset-x-0 top-0 z-50 flex h-16 items-center gap-4 px-4 transition sm:px-6 lg:z-30 backdrop-blur-sm dark:backdrop-blur bg-white/[var(--bg-opacity-light)] dark:bg-zinc-900/[var(--bg-opacity-dark)] lg:left-61 xl:left-55 justify-between"
 style="--bg-opacity-light: 0.5; --bg-opacity-dark: 0.2;">
    <div class="absolute inset-x-0 top-full h-px transition bg-zinc-900/7.5 dark:bg-white/7.5"></div>

    @if(isset($allData['is_trial']) && $allData['is_trial'] == "yes")
       <!-- Trial Icon -->
        <div  <?php if(round($allData['daysLeft'])>0) {?>  title="Trial Activated" <?php } else { ?>  title="Trial Expired" <?php  } ?> class="text-blue-500 bg-white p-2 rounded-full shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    @else
     <!-- Large Verified Icon -->
   <div <?php if(round($allData['daysLeft'])>0) {?>  title="Pro Plan Activated" <?php } else { ?>  title="Pro Plan Expired" <?php  } ?> class="text-emerald-500 bg-white p-2 rounded-full shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    @endif
            <?php /*
    @if(isset($allData['is_trial']) && $allData['is_trial'] == "yes")
        <div class="flex items-center justify-between space-x-2 bg-blue-50 border-l-3 border-blue-500  rounded py-1.5 px-2.5 text-blue-800">
        <div class="flex items-center space-x-2">
            <div class="text-blue-500 bg-white p-0.5 rounded-full shadow-sm flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-xs">
                <div class="flex items-center font-medium">
                    Trial Active
                    <span class="ml-1.5 px-1.5 py-0.5 text-xs bg-blue-500 text-white rounded-full inline-flex items-center text-2xs">
                        {{ round($allData['daysLeft']) }}d
                    </span>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="flex items-center justify-between space-x-2 bg-emerald-50 border-l-3 border-emerald-500  rounded py-1.5 px-2.5 text-emerald-800">
            <div class="flex items-center space-x-2">
                <div class="text-emerald-500 bg-white p-0.5 rounded-full shadow-sm flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-xs">
                    <div class="flex items-center font-medium">
                        {{ $allData['plan_name'] ?? 'Pro' }}
                    </div>
                </div>
            </div>
        </div>
    @endif */?>
    <div class="xl:flex hidden items-center space-x-4">
        <div class="hidden xl:block xl:max-w-md xl:flex-auto">
            <button type="button"
                class="hidden gap-2 lg:w-[400px] bg-light-gray dark:bg-dark-bg h-12 rounded-lg items-center px-4 justify-between">
                <div class="flex space-x-2 items-center text-zinc-500">
                    <svg viewBox="0 0 20 20" fill="none" class="h-8 w-8 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12.01 12a4.25 4.25 0 1 0-6.02-6 4.25 4.25 0 0 0 6.02 6Zm0 0 3.24 3.25"></path>
                    </svg>
                </div>
            </button>
        </div>
    </div>
    
    <div x-cloak x-data="{ mobileMenuOpen: false }">
        <div class="flex items-center gap-5 lg:hidden">
            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                class="flex h-6 w-6 items-center justify-center rounded-md transition hover:bg-zinc-900/5 dark:hover:bg-white/5"
                aria-label="Toggle navigation">
                <svg viewBox="0 0 10 9" fill="none" stroke-linecap="round" aria-hidden="true"
                    class="w-2.5 stroke-zinc-900 dark:stroke-white">
                    <path d="M.5 1h9M.5 8h9M.5 4.5h9"></path>
                </svg>
            </button>
            <x-authentication-card-logo />
        </div>
        <div class="flex items-center gap-5">
            <nav class="hidden md:block"></nav>
            <div hidden=""
                style="position: fixed; top: 1px; left: 1px; width: 1px; height: 0px; padding: 0px; margin: -1px; overflow: hidden; clip: rect(0px, 0px, 0px, 0px); white-space: nowrap; border-width: 0px; display: none;">
            </div>
            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="absolute top-full left-0 right-0 bg-white dark:bg-zinc-800 shadow-lg p-4 mt-2 max-h-[calc(100vh-5rem)] overflow-y-auto">
                <nav class="flex flex-col space-y-2">
                    @include('livewire.common.menu')
                </nav>
            </div>
        </div>
    </div>

    <div x-cloak x-data="{ open: false }" class="relative inline-block text-left scrollbar" @click.away="open = false"
        @keydown.escape.window="open = false">
        <div class="flex items-center space-x-4 ">
            <!-- Add the notification component inline -->
            <div x-data="{ notificationOpen: false }" class="relative">
                <!-- Notification Toggle Button -->
                <button  x-show="!notificationOpen"  @click="notificationOpen = true" class="relative p-2 text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFC107">
                        <path d="M12 2C10.34 2 9 3.34 9 5V6.26C6.17 7.14 4 9.91 4 13V17L2 19V20H22V19L20 17V13C20 9.91 17.83 7.14 15 6.26V5C15 3.34 13.66 2 12 2Z"/>
                        <path d="M12 22C13.1 22 14 21.1 14 20H10C10 21.1 10.9 22 12 22Z" fill="#424242"/>
                    </svg>
                    
                    @if($unreadCount)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>

                <!-- Notification Panel -->
                <div  x-show="notificationOpen" class="inset-0 z-50 overflow-hidden">
                    <!-- Backdrop -->
                    <div  class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="notificationOpen = false"></div>
                    <!-- Sliding Panel -->
                    <div class="fixed inset-y-0 right-0 pl-10 max-w-full">
                    <div class="w-[calc(100vw-16px)] sm:w-screen max-w-md transform transition-transform duration-500 ease-in-out pr-[30px] sm:pr-0 mr-4 sm:mr-0"
                    :class="notificationOpen ? 'translate-x-0' : 'translate-x-full'">
                            <div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-xl">
                                <!-- Enhanced Header with modern styling and animations -->
                                <div class="flex-shrink-0 px-7 py-6 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <!-- Left side with title and icon -->
                                        <div class="flex items-center space-x-5">
                                            <!-- Animated notification icon -->
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-blue-500/20 dark:bg-blue-400/20 rounded-xl animate-pulse"></div>
                                                <div class="relative p-2.5 bg-gradient-to-br from-blue-500/10 to-blue-600/20 dark:from-blue-400/20 dark:to-blue-500/30 rounded-xl backdrop-blur-sm">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 transform transition-transform duration-700 hover:scale-110" 
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Title and subtitle with improved typography -->
                                            <div class="space-y-1">
                                                <h2 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">
                                                    Notifications
                                                </h2>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    Stay informed 
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Close button with enhanced interaction -->
                                        <button 
                                            @click="notificationOpen = false"
                                            class="group p-2.5 rounded-xl transition-all duration-300
                                                text-gray-400 hover:text-gray-900 dark:hover:text-white
                                                bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700
                                                focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                        >
                                            <span class="sr-only">Close notification panel</span>
                                            <svg class="h-4 w-4 transform transition-transform duration-300 group-hover:rotate-90" 
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Decorative elements -->
                                    <div class="absolute bottom-0 left-0 right-0">
                                        <!-- Gradient line -->
                                        <div class="h-px bg-gradient-to-r from-transparent via-gray-200 dark:via-gray-700 to-transparent"></div>
                                        <!-- Decorative dots -->
                                        <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 flex space-x-1.5">
                                            <div class="w-1 h-1 rounded-full bg-blue-500/50 dark:bg-blue-400/50"></div>
                                            <div class="w-1 h-1 rounded-full bg-blue-500/30 dark:bg-blue-400/30"></div>
                                            <div class="w-1 h-1 rounded-full bg-blue-500/10 dark:bg-blue-400/10"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Content Area - Simple & Clean Design -->
                                <div class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                                    @if($this->canCreateNotifications())
                                    <!-- Create Notification Form - Clean & Simple -->
                                    <div x-data="{ formExpanded: false }" class="sticky top-0 z-10 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                                        <div class="max-w-3xl mx-auto px-4 py-3">
                                            <!-- Expandable Header -->
                                            <div class="flex items-center justify-between cursor-pointer" @click="formExpanded = !formExpanded">
                                                <div class="flex items-center gap-2">
                                                    <div class="relative w-6 h-6 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-sm font-medium text-gray-800 dark:text-white">
                                                        Create New Notification
                                                    </h3>
                                                </div>
                                                
                                                <div class="flex items-center gap-2">
                                                    
                                                    <!-- Toggle Arrow Icon -->
                                                    <div class="h-6 w-6 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                        <svg class="w-4 h-4 transition-transform duration-300" :class="formExpanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Expandable Content -->
                                            <div 
                                                x-show="formExpanded" 
                                                x-transition:enter="transition ease-out duration-200" 
                                                x-transition:enter-start="opacity-0 transform -translate-y-2" 
                                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                class="mt-3 transition-all duration-300"
                                            >
                                                <!-- Textarea with character count -->
                                                <div x-data="{ count: 0, maxLength: 500 }" class="relative mb-3">
                                                    <textarea
                                                        wire:model.defer="notificationText"
                                                        x-on:input="count = $event.target.value.length"
                                                        class="w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300
                                                        bg-white dark:bg-gray-800
                                                        border border-gray-200 dark:border-gray-700
                                                        rounded-lg
                                                        focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                                                        transition-all duration-200
                                                        placeholder-gray-400 dark:placeholder-gray-500"
                                                        placeholder="Write your notification message here..."
                                                        rows="3"
                                                        maxlength="500"
                                                    ></textarea>
                                                    
                                                    <!-- Character counter -->
                                                    <div class="absolute bottom-2 right-2 text-xs text-gray-400">
                                                        <span x-text="count"></span>/<span x-text="maxLength"></span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Action Buttons -->
                                                <div class="flex items-center justify-end gap-2">
                                                    <button
                                                        type="button"
                                                        class="px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300
                                                        bg-white dark:bg-gray-800
                                                        border border-gray-200 dark:border-gray-700
                                                        rounded-lg
                                                        hover:bg-gray-50 dark:hover:bg-gray-700
                                                        focus:outline-none focus:ring-2 focus:ring-gray-500/20
                                                        transition-all duration-200"
                                                        wire:click="$set('notificationText', '')"
                                                    >
                                                        Clear
                                                    </button>
                                                    <button
                                                        wire:click="createNotification"
                                                        class="px-3 py-1.5 text-sm font-medium text-white
                                                        bg-blue-500
                                                        rounded-lg
                                                        hover:bg-blue-600
                                                        focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                                        disabled:opacity-50 disabled:cursor-not-allowed
                                                        transition-all duration-200
                                                        flex items-center gap-1"
                                                        wire:loading.class="opacity-75 cursor-wait"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                        </svg>
                                                        <span wire:loading.remove>Send</span>
                                                        <span wire:loading>Sending...</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Notifications List Container -->
                                    <div class="max-w-3xl mx-auto py-4 px-4 overflow-y-auto max-h-[1124px]">
                                        <div class="space-y-3">
                                            @forelse ($notifications as $notification)
                                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                                <!-- Simple, Clean Notification Layout -->
                                                <div class="p-4">
                                                    <!-- Header Section -->
                                                    <div class="flex items-start justify-between mb-3">
                                                        <div class="flex items-start">
                                                            <!-- Simple Avatar -->
                                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                                                {{ in_array('1', explode(',', $notification->createdBy->user_role))
                                                                ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400'
                                                                : 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                                                {{ strtoupper(substr($notification->createdBy->name, 0, 1)) }}
                                                            </div>
                                                            
                                                            <!-- User Info -->
                                                            <div>
                                                                <div class="flex items-center">
                                                                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $notification->createdBy->name }}</span>
                                                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full
                                                                        {{ in_array('1', explode(',', $notification->createdBy->user_role))
                                                                        ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/20 dark:text-purple-400'
                                                                        : 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' }}">
                                                                        {{ in_array('1', explode(',', $notification->createdBy->user_role)) ? 'Super Admin' : 'Admin' }}
                                                                    </span>
                                                                </div>
                                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                                    {{ $notification->created_at->diffForHumans() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Read Status Button -->
                                                        @if ($notification->reads->where('read_by', auth()->id())->count())
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600
                                                            dark:text-green-400 bg-green-50 dark:bg-green-900/20 rounded-full">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                            Read
                                                        </span>
                                                        @else
                                                        <button wire:click="markAsRead({{ $notification->id }})"
                                                            class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                                            {{ in_array('1', explode(',', $notification->createdBy->user_role))
                                                            ? 'text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100'
                                                            : 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100' }} 
                                                            dark:hover:bg-opacity-30 transition-colors">
                                                            Mark as Read
                                                        </button>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Notification Content -->
                                                    <div class="mb-3">
                                                        <p class="text-sm text-gray-700 dark:text-gray-200 leading-relaxed">
                                                            {{ $notification->notification }}
                                                        </p>
                                                    </div>
                                                    
                                                    <!-- Readers Count & Toggle -->
                                                    @if($notification->reads->count() > 0)
                                                    <div x-data="{ showReaders: false }">
                                                        <button @click="showReaders = !showReaders"
                                                            class="flex items-center justify-between w-full px-2 py-1.5 text-xs font-medium text-gray-500 dark:text-gray-400 
                                                                border-t border-gray-100 dark:border-gray-700 mt-2 pt-2
                                                                hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                                                            <div class="flex items-center">
                                                                <svg class="w-4 h-4 mr-1 transition-transform duration-200"
                                                                    :class="{ 'rotate-90': showReaders }"
                                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 5l7 7-7 7"/>
                                                                </svg>
                                                                Read by {{ $notification->reads->count() }} {{ Str::plural('person', $notification->reads->count()) }}
                                                            </div>
                                                            <span class="text-xs text-gray-400 dark:text-gray-500" x-text="showReaders ? 'Hide' : 'Show'"></span>
                                                        </button>
                                                        
                                                        <!-- Readers List -->
                                                        <div x-show="showReaders"
                                                            x-transition:enter="transition ease-out duration-200"
                                                            x-transition:enter-start="opacity-0"
                                                            x-transition:enter-end="opacity-100"
                                                            x-transition:leave="transition ease-in duration-150"
                                                            x-transition:leave-start="opacity-100"
                                                            x-transition:leave-end="opacity-0"
                                                            class="mt-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                                                            
                                                            <div class="space-y-2">
                                                                @foreach($notification->reads as $read)
                                                                <div class="flex items-center justify-between p-2 text-xs rounded-lg bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                                                                    <span class="font-medium text-gray-700 dark:text-gray-200">
                                                                        {{ $read->reader->name }}
                                                                    </span>
                                                                    <span class="text-gray-500 dark:text-gray-400">
                                                                        {{ $read->read_at ? $read->read_at->diffForHumans() : 'N/A' }}
                                                                    </span>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @empty
                                            <!-- Simple Empty State -->
                                            <div class="p-8 text-center bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
                                                    <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                    </svg>
                                                </div>
                                                <p class="text-gray-700 dark:text-gray-300 font-medium">No notifications available</p>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Check back later for updates</p>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- LogOut Button -->
            <button type="button" wire:click="removeCode" wire:confirm="Are you sure you want to logout?"
            class="mr-1 sm:mr-2 p-1.5 sm:p-2 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300"
            title="Logout from your account!">
              
                <svg xmlns="http://www.w3.org/2000/svg" width="24" class="h-4 w-4 sm:h-5 sm:w-5" height="24" viewBox="0 0 24 24" fill="#F44336">
  <path d="M16 13V11H8V8L4 12L8 16V13H16Z"/>
  <path d="M20 3H12V5H20V19H12V21H20C21.1 21 22 20.1 22 19V5C22 3.9 21.1 3 20 3Z" fill="#424242"/>
</svg>

                <span class="sr-only">Logout</span>
            </button>

            <!-- Account Button -->
            <button type="button"
            class="inline-flex items-center justify-center gap-x-1 sm:gap-x-1.5 rounded-md bg-white px-2 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm font-medium sm:font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1 transition-colors"
            @click="open = !open" aria-expanded="true" aria-haspopup="true">
                <span class="hidden xs:inline">Account</span>
                <span class="inline xs:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
                <svg class="-mr-0.5 sm:-mr-1 h-4 w-4 sm:h-5 sm:w-5 text-gray-400 transition-transform duration-200"
                    :class="{ 'rotate-180': open }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Dropdown Menu -->
       <!-- Parent container needs to be positioned relative -->
        <div class="relative">
    
            <div x-show="open" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                class="absolute right-0 z-10 mt-3 w-72 origin-top-right rounded-xl bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black/5 dark:ring-white/10 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none"
                role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                
                <!-- User Info Section -->
                <div class="p-4 space-y-3" role="none">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-medium text-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <!-- Role Badge with gradient background -->
                    <div class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm border 
                        @if(in_array('1', explode(',', auth()->user()->user_role)))
                            bg-gradient-to-r from-purple-50 to-purple-100 text-purple-700 dark:from-purple-900/30 dark:to-purple-800/30 dark:text-purple-300 border-purple-200 dark:border-purple-800
                        @elseif(in_array('2', explode(',', auth()->user()->user_role)))
                            bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 dark:from-blue-900/30 dark:to-blue-800/30 dark:text-blue-300 border-blue-200 dark:border-blue-800
                        @elseif(in_array('3', explode(',', auth()->user()->user_role)))
                            bg-gradient-to-r from-green-50 to-green-100 text-green-700 dark:from-green-900/30 dark:to-green-800/30 dark:text-green-300 border-green-200 dark:border-green-800
                        @elseif(in_array('4', explode(',', auth()->user()->user_role)))
                            bg-gradient-to-r from-amber-50 to-amber-100 text-amber-700 dark:from-amber-900/30 dark:to-amber-800/30 dark:text-amber-300 border-amber-200 dark:border-amber-800
                        @else
                            bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 dark:from-gray-900/30 dark:to-gray-800/30 dark:text-gray-300 border-gray-200 dark:border-gray-800
                        @endif transition-all duration-200 hover:shadow-md">
                            <svg class="w-3.5 h-3.5 mr-1.5 
                            @if(in_array('1', explode(',', auth()->user()->user_role)))
                                text-purple-500 dark:text-purple-400
                            @elseif(in_array('2', explode(',', auth()->user()->user_role)))
                                text-blue-500 dark:text-blue-400
                            @elseif(in_array('3', explode(',', auth()->user()->user_role)))
                                text-green-500 dark:text-green-400
                            @elseif(in_array('4', explode(',', auth()->user()->user_role)))
                                text-amber-500 dark:text-amber-400
                            @else
                                text-gray-500 dark:text-gray-400
                            @endif" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ auth()->user()->getRoleNameAttribute() }}</span>
                    </div>
                </div>

                <!-- Action Links -->
                <div class="py-2" role="none">
                    <a href="{{ route('profile.show') }}" 
                        class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                </div>

                <!-- My Subscription Links -->
                <!-- My Subscription Menu Item - Compact design -->
<div class="py-1.5" role="none">
    <a href="{{ route('my-subscriptions') }}" 
        class="group flex items-center px-4 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2.5 h-4 w-4 text-indigo-500 group-hover:text-indigo-600 transition-colors duration-200">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25H9m6 3H9m3 6-3-3h1.5a3 3 0 1 0 0-6M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span>My Subscription</span>
    </a>
</div>

<!-- Subscription Status Panels - Compact version -->
<div class="px-2 py-1.5" role="none">
    @if(isset($allData['is_trial']) && $allData['is_trial'] == "yes")
    <!-- Trial Period Notification - Compact design -->
    <div class="flex items-center justify-between space-x-2 bg-blue-50 border-l-3 border-blue-500 
                rounded py-1.5 px-2.5 text-blue-800">
        <!-- Icon and Text Container -->
        <div class="flex items-center space-x-2">
            <!-- Icon -->
            <div class="text-blue-500 bg-white p-0.5 rounded-full shadow-sm flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <!-- Text Content -->
            <div class="text-xs">
                <div class="flex items-center font-medium">
                    Trial Active
                    <span class="ml-1.5 px-1.5 py-0.5 text-xs bg-blue-500 text-white rounded-full inline-flex items-center text-2xs">
                        {{ round($allData['daysLeft']) }}d
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Upgrade Button -->
        @if(env('IS_PAYMENT_LIVE')==true)
            <a href="{{ route('upgrade-account') }}" 
            class="text-2xs px-2 py-0.5 bg-blue-500 text-white font-medium rounded hover:bg-blue-600 transition-colors">
                Upgrade
            </a>
        @endif
    </div>
    @else
    <!-- Active Subscription Notification - Compact design -->
    <div class="flex items-center justify-between space-x-2 bg-emerald-50 border-l-3 border-emerald-500 
                rounded py-1.5 px-2.5 text-emerald-800">
        <!-- Icon and Text Container -->
        <div class="flex items-center space-x-2">
            <!-- Icon -->
            <div class="text-emerald-500 bg-white p-0.5 rounded-full shadow-sm flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <!-- Text Content -->
            <div class="text-xs">
                <div class="flex items-center font-medium">
                    {{ $allData['plan_name'] ?? 'Pro' }}
                    <span class="ml-1.5 px-1.5 py-0.5 text-2xs bg-emerald-100 text-emerald-800 rounded-full inline-flex items-center">
                        {{ round($allData['daysLeft']) }}d left
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Renewal Button -->
        <a href="{{ route('upgrade-account') }}" 
           class="text-2xs px-2 py-0.5 bg-white text-emerald-800 font-medium rounded border border-emerald-200 hover:bg-emerald-50 transition-colors">
            Renew
        </a>
    </div>
    @endif
                </div>
                <!-- Sign Out Section -->
                <div class="py-2" role="none">
                    <form method="POST" action="{{ route('logout') }}" role="none">
                        @csrf
                        <button type="submit"
                            class="group flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</nav>
