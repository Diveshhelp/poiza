<div
    x-data="notificationComponent()"
    @notify-success.window="show($event.detail, 'success')"
    @notify-error.window="show($event.detail, 'error')"
    @notify-warning.window="show($event.detail, 'warning')"
    @notify-info.window="show($event.detail, 'info')"
    class="fixed top-4 right-4 z-1111 space-y-2 w-96">
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-show="true"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="-translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transform ease-in duration-300 transition"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="-translate-y-full"
            :class="{
                'bg-green-50 border-green-400': notification.type === 'success',
                'bg-red-50 border-red-400': notification.type === 'error',
                'bg-yellow-50 border-yellow-400': notification.type === 'warning',
                'bg-blue-50 border-blue-400': notification.type === 'info'
            }"
            class="w-full border-b-4 shadow-lg"
        >
            <div class="max-w-7xl mx-auto px-4 py-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <!-- Success Icon -->
                            <svg x-show="notification.type === 'success'" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <!-- Error Icon -->
                            <svg x-show="notification.type === 'error'" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <!-- Warning Icon -->
                            <svg x-show="notification.type === 'warning'" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <!-- Info Icon -->
                            <svg x-show="notification.type === 'info'" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium" 
                               :class="{
                                   'text-green-800': notification.type === 'success',
                                   'text-red-800': notification.type === 'error',
                                   'text-yellow-800': notification.type === 'warning',
                                   'text-blue-800': notification.type === 'info'
                               }"
                               x-text="notification.message"></p>
                        </div>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="remove(notification.id)" 
                                class="inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
                                :class="{
                                    'text-green-400 hover:text-green-500 focus:ring-green-500': notification.type === 'success',
                                    'text-red-400 hover:text-red-500 focus:ring-red-500': notification.type === 'error',
                                    'text-yellow-400 hover:text-yellow-500 focus:ring-yellow-500': notification.type === 'warning',
                                    'text-blue-400 hover:text-blue-500 focus:ring-blue-500': notification.type === 'info'
                                }">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        function notificationComponent() {
            return {
                notifications: [],
                show(message, type) {
                    const id = Date.now();
                    this.notifications.push({
                        id,
                        message,
                        type
                    });
                    setTimeout(() => this.remove(id), 5000);
                },
                remove(id) {
                    this.notifications = this.notifications.filter(notification => notification.id !== id);
                }
            }
        }
    </script>
</div>