<!-- Password Manager with Space-Saving Design -->
<div>
    @if(!$isAuthenticated)
        <!-- Login Modal - More Compact -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden max-w-md w-full">
                <div class="bg-indigo-600 px-4 py-3">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h2 class="text-lg font-bold ml-2 text-white">Password Manager</h2>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600 mb-4 text-center">Please enter your master password to access your secure passwords.</p>

                    <form wire:submit.prevent="authenticate">
                        <div class="mb-4">
                            <label for="accessPassword" class="block text-xs font-medium text-gray-700 mb-1">Master Password</label>
                            <div class="relative">
                                <input type="password" id="accessPassword" wire:model="accessPassword"
                                    class="w-full text-sm p-2 pl-9 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    autocomplete="off" autofocus>
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            @error('accessPassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                            @if($wrongPasswordAttempt)
                                <div class="text-red-500 text-xs mt-1 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Incorrect password. Please try again.
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-center">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:opacity-25 transition-all duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                Unlock Manager
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Enhanced Session Timer - More Compact -->
        <div x-data="{
                timer: '{{ $formattedRemainingTime }}',
                expiryTime: {{ $sessionExpiryTime ?? 'null' }},
                currentTime: Math.floor(Date.now() / 1000),
                remainingSeconds: 0,
                timerInterval: null,
                isWarning: {{ $timerWarning ? 'true' : 'false' }},
                
                startTimer() {
                    if (!this.expiryTime) return;
                    
                    clearInterval(this.timerInterval);
                    this.updateTimer();
                    this.timerInterval = setInterval(() => { 
                        this.updateTimer();
                    }, 1000);
                },
                
                updateTimer() {
                    this.currentTime = Math.floor(Date.now() / 1000);
                    this.remainingSeconds = Math.max(0, this.expiryTime - this.currentTime);
                    
                    const minutes = Math.floor(this.remainingSeconds / 60);
                    const seconds = this.remainingSeconds % 60;
                    this.timer = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                    // Update warning state
                    this.isWarning = this.remainingSeconds < 60;
                    
                    if (this.remainingSeconds <= 0) {
                        clearInterval(this.timerInterval);
                        $wire.resetSession();
                    }
                },
                
                extendSession() {
                    $wire.extendSession();
                }
            }" 
            x-init="
                // Initialize timer on page load
                startTimer();
                
                // Set up event listeners for session management
                $wire.on('updateTimer', (data) => {
                    expiryTime = data[0].expiryTime;
                    isWarning = data[0].timerWarning;
                    updateTimer();
                });
                
                $wire.on('sessionExtended', (data) => {
                    expiryTime = data[0].expiryTime;
                    isWarning = false;
                    updateTimer();
                });
                
                $wire.on('timerStarted', (data) => {
                    expiryTime = data[0].expiryTime;
                    isWarning = false;
                    startTimer();
                });
                
                $wire.on('sessionExpired', () => {
                    clearInterval(timerInterval);
                    window.location.reload();
                });
                
                // Check authentication status regularly
                setInterval(() => {
                    $wire.checkAuthenticationStatus();
                }, 10000); // Check every 10 seconds
            "
            class="sticky top-0 z-10 px-3 py-1.5 flex justify-between items-center shadow-md transition-all duration-300"
            :class="isWarning ? 'bg-red-600' : 'bg-indigo-600'"
        >
            <div class="text-white font-bold text-sm">{{ $moduleTitle }}</div>
            <div class="flex items-center">
                <div class="text-xs text-white mr-2 flex items-center" 
                     :class="{ 'animate-pulse': isWarning }">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">Session:</span> <span x-text="timer"></span>
                </div>
                <button @click="extendSession" 
                      class="text-white hover:text-indigo-200 focus:outline-none bg-indigo-700 hover:bg-indigo-800 rounded-md px-2 py-0.5 text-xs transition-all duration-200"
                      title="Extend Session">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </span>
                </button>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-2 sm:px-4 pb-4">
            <!-- Notification Messages - More Compact -->
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" 
                     class="bg-green-100 border-l-4 border-green-500 text-green-700 p-2 mb-2 mt-2 text-xs rounded shadow-sm flex items-center" 
                     role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" 
                     class="bg-red-100 border-l-4 border-red-500 text-red-700 p-2 mb-2 mt-2 text-xs rounded shadow-sm flex items-center" 
                     role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Main App Grid - Responsive Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 mt-3">
                <!-- Left Column: Security Info and Add/Edit Form -->
                <div class="lg:col-span-1">
                    <!-- Password Security Section - Compact -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-3">
                        <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-3 py-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <h2 class="text-white text-sm font-semibold">Security Info</h2>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <svg class="mr-0.5 h-2 w-2 text-green-600" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Protected
                                </span>
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="space-y-2">
                                <!-- Security Item -->
                                <div class="flex items-start p-2 bg-indigo-50 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    <div class="ml-2">
                                        <p class="text-xs font-medium text-gray-900">We Cannot See Your Password</p>
                                        <p class="text-xs text-gray-600">Encrypted with multiple security layers.</p>
                                    </div>
                                </div>

                                <!-- Storage Method -->
                                <div class="bg-white rounded-md p-2">
                                    <p class="text-xs font-medium text-gray-900 mb-1">How We Store Your Data:</p>
                                    <div class="space-y-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <span class="flex h-5 w-5 rounded-full bg-indigo-100 items-center justify-center">
                                                    <span class="text-indigo-600 font-medium text-xs">1</span>
                                                </span>
                                            </div>
                                            <div class="ml-2">
                                                <p class="text-xs text-gray-700">Never stored as plain text</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <span class="flex h-5 w-5 rounded-full bg-indigo-100 items-center justify-center">
                                                    <span class="text-indigo-600 font-medium text-xs">2</span>
                                                </span>
                                            </div>
                                            <div class="ml-2">
                                                <p class="text-xs text-gray-700">Multiple encryption layers</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <span class="flex h-5 w-5 rounded-full bg-indigo-100 items-center justify-center">
                                                    <span class="text-indigo-600 font-medium text-xs">3</span>
                                                </span>
                                            </div>
                                            <div class="ml-2">
                                                <p class="text-xs text-gray-700">Stored as:</p>
                                            </div>
                                        </div>
                                        <div class="bg-gray-100 p-2 rounded text-xs font-mono text-gray-700 overflow-x-auto border border-gray-200 text-xs leading-tight">
                                            eyJpdiI6IkdUT3F6YlhNZXR3K290TDN4NERoaUE9PSIsInZhbHVlIjoiTjVxS3lmUzM0WURoYWRxL1FwcFkrQT09IiwibWFjIjoiMzAyNDk2OTAyOWNm...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Form - Compact -->
                    <div x-data="{ open: true }" @password-edit.window="open = true"
                        class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                        <div class="px-3 py-2 bg-indigo-50 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h2 class="text-sm font-semibold text-gray-700 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    {{ $isEditing ? 'Edit' : 'Add New' }} Password
                                </h2>
                                <button @click="open = !open" type="button"
                                    class="inline-flex items-center px-2 py-1 bg-indigo-100 rounded text-xs text-indigo-700 hover:bg-indigo-200 transition-colors duration-200">
                                    <span x-text="open ? 'Hide' : 'Show'"></span>
                                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0" class="p-3">
                            <form wire:submit.prevent="savePassword" class="text-xs">
                                <div class="space-y-3">
                                    <!-- URL Field -->
                                    <div>
                                        <label for="url" class="block text-xs font-medium text-gray-700 mb-1">Website URL/Email</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                            </div>
                                            <input type="text" id="url" wire:model="url"
                                                class="p-1.5 pl-7 block w-full text-xs border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        @error('url') <span class="text-red-500 text-xs mt-0.5">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Password Field -->
                                    <div>
                                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                </svg>
                                            </div>
                                            <input type="password" id="password" wire:model="password"
                                                class="p-1.5 pl-7 block w-full text-xs border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        @error('password') <span class="text-red-500 text-xs mt-0.5">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Category Field -->
                                    <div>
                                        <label for="category" class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                            </div>
                                            <input type="text" id="category" wire:model="category" list="category-list"
                                                class="p-1.5 pl-7 block w-full text-xs border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                            <datalist id="category-list">
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat }}">
                                                @endforeach
                                            </datalist>
                                        </div>
                                        @error('category') <span class="text-red-500 text-xs mt-0.5">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Status Field -->
                                    <div>
                                        <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                                </svg>
                                            </div>
                                            <select id="status" wire:model="status"
                                                class="p-1.5 pl-7 block w-full text-xs border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="active">Active</option>
                                                <option value="archived">Archived</option>
                                            </select>
                                        </div>
                                        @error('status') <span class="text-red-500 text-xs mt-0.5">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Notes Field -->
                                    <div>
                                        <label for="note" class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                                        <div class="relative">
                                            <div class="absolute top-2 left-2 flex items-start pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </div>
                                            <textarea id="note" wire:model="note" rows="2"
                                                class="p-1.5 pl-7 block w-full text-xs border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                        </div>
                                        @error('note') <span class="text-red-500 text-xs mt-0.5">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Form Buttons -->
                                <div class="mt-3 flex space-x-2">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md text-xs text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                        </svg>
                                        {{ $isEditing ? 'Update' : 'Save' }}
                                    </button>
                                    @if($isEditing)
                                        <button type="button" wire:click="resetFields" @click="open = false"
                                            class="inline-flex items-center px-3 py-1.5 bg-gray-200 border border-transparent rounded-md text-xs text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Cancel
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Filters and Password List -->
                <div class="lg:col-span-3">
                    <!-- Enhanced Filters - Inline Design -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-3">
                        <div class="p-3">
                            <h2 class="text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filter Passwords
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="filterKeyword" wire:model.live="filterKeyword" placeholder="Search URLs, notes or categories..."
                                            class="p-1.5 pl-7 block w-full text-xs border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                                <div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <select id="filterCategory" wire:model.live="filterCategory"
                                            class="p-1.5 pl-7 block w-full text-xs border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat }}">{{ $cat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password List - Compact Table -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                        <div class="p-2 border-b border-gray-200 bg-indigo-50">
                            <h2 class="text-sm font-semibold text-gray-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Your Saved Passwords
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL/Email</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($passwords as $pwd)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-3 py-2 text-xs">
                                                <a href="{{ $pwd->url }}" target="_blank"
                                                    class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                    {{ Str::limit($pwd->url, 30) }}
                                                </a>
                                            </td>
                                            <td class="px-3 py-2 text-xs">
                                                <div class="flex items-center">
                                                    <span class="font-mono p-0.5 rounded @if($showPasswordId === $pwd->uuid) bg-green-50 border border-green-200 @endif">
                                                        @if($showPasswordId === $pwd->uuid)
                                                            {{ Crypt::decryptString($pwd->password) }}
                                                        @else
                                                            •••••••••
                                                        @endif
                                                    </span>
                                                    <button wire:click="togglePasswordVisibility('{{ $pwd->uuid }}')"
                                                        class="ml-1 p-0.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                        @if($showPasswordId === $pwd->uuid)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                    <button onclick="copyToClipboard('{{ $pwd->uuid }}')"
                                                        class="ml-1 p-0.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-3 py-2 text-xs">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $pwd->category }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-xs">
                                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full {{ $pwd->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($pwd->status) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-xs font-medium">
                                                <div class="flex space-x-2">
                                                    <button wire:click="editPassword('{{ $pwd->uuid }}')"
                                                        class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </button>
                                                    <button wire:click="deletePassword('{{ $pwd->uuid }}')"
                                                        class="text-red-600 hover:text-red-900 flex items-center"
                                                        wire:confirm='Are you sure you want to delete this password?'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-3 py-4 text-center text-xs text-gray-500">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p class="font-medium">No passwords found</p>
                                                    <p class="text-gray-500 text-xs mt-1">Add your first password using the form</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-3 py-2 border-t border-gray-200 bg-gray-50">
                            {{ $passwords->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Enhanced clipboard function with visual feedback
        function copyToClipboard(uuid) {
            @this.call('copyToClipboard', uuid).then(password => {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(password).then(() => {
                        // Show toast notification instead of alert
                        showToast('Password copied to clipboard!', 'success');
                    }).catch(err => {
                        console.error('Could not copy text: ', err);
                        showToast('Failed to copy password', 'error');
                    });
                } else {
                    // Fallback for browsers that don't support clipboard API
                    const textarea = document.createElement('textarea');
                    textarea.value = password;
                    textarea.style.position = 'fixed';
                    document.body.appendChild(textarea);
                    textarea.focus();
                    textarea.select();
                    
                    try {
                        document.execCommand('copy');
                        showToast('Password copied to clipboard!', 'success');
                    } catch (err) {
                        console.error('Could not copy text: ', err);
                        showToast('Failed to copy password', 'error');
                    }
                    
                    document.body.removeChild(textarea);
                }
            });
        }
        
        // Toast notification function - Compact version
        function showToast(message, type = 'success') {
            // Create toast container if it doesn't exist
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.style.cssText = 'position: fixed; bottom: 10px; right: 10px; z-index: 9999;';
                document.body.appendChild(toastContainer);
            }
            
            // Create toast
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.style.cssText = 'min-width: 200px; margin-top: 6px; padding: 8px 12px; border-radius: 4px; font-size: 12px; display: flex; align-items: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); transform: translateX(100%); transition: transform 0.3s ease;';
            
            // Set background color based on type
            if (type === 'success') {
                toast.style.backgroundColor = '#4caf50';
                toast.style.color = 'white';
                toast.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    ${message}
                `;
            } else {
                toast.style.backgroundColor = '#f44336';
                toast.style.color = 'white';
                toast.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    ${message}
                `;
            }
            
            // Add toast to container
            toastContainer.appendChild(toast);
            
            // Trigger entrance animation
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 50);
            
            // Remove toast after 2.5 seconds
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                toast.style.opacity = '0';
                setTimeout(() => {
                    toastContainer.removeChild(toast);
                }, 300);
            }, 2500);
        }

        // Auto-check authentication status every 15 seconds
        document.addEventListener('DOMContentLoaded', function () {
            // Check authentication every 15 seconds
            setInterval(() => {
                @this.checkAuthenticationStatus();
            }, 15000);
            
            // Add user activity listeners to reset inactivity timer
            const activityEvents = ['mousedown', 'keydown', 'scroll', 'touchstart'];
            
            // Add event listeners
            activityEvents.forEach(eventType => {
                document.addEventListener(eventType, function() {
                    @this.checkAuthenticationStatus();
                }, { passive: true });
            });
        });
    </script>
</div>