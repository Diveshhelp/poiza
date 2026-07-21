<div class="p-5 dark:bg-dark-bg dark:border dark:border-gray-600 rounded-xl bg-white shadow-xl transition-all duration-300">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center w-full gap-4 pb-4 border-b border-gray-100 dark:border-gray-700">
        <!-- Left Side: Welcome & Status -->
        <div class="flex items-center gap-3">
            <div class="p-3 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-xl shadow-md text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-extrabold text-xl text-gray-900 dark:text-white tracking-tight">Welcome back, {{ auth()->user()->name ?? 'Valued User' }}! 👋</h1>
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">Online</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Here is what's happening with your business operations today.</p>
            </div>
        </div>

        <!-- Right Side: Quick Action / System Badge -->
        <div class="flex items-center gap-3 w-full lg:w-auto justify-end">
            <div class="flex items-center gap-2.5 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-pink-500/10 dark:bg-dark-bg/60 backdrop-blur-md px-4 py-2 rounded-lg border border-indigo-500/20 shadow-inner">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500"></span>
                </span>
                <span class="font-bold text-xs uppercase tracking-wider text-indigo-700 dark:text-indigo-300">ERP Monitoring Live</span>
            </div>
        </div>
    </div>

    <!-- Main Banner Content with Rich Design -->
    <div class="mt-5 relative overflow-hidden bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 rounded-xl p-6 sm:p-8 text-white shadow-lg">
        <!-- Decorative background glow blobs -->
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-purple-500/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-blue-500/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
            <div class="lg:col-span-2 space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 backdrop-blur-md border border-white/20 text-indigo-200">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Enterprise Control Center
                </div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Streamline Your Orders & Production Flow</h2>
                <p class="text-sm text-indigo-100/90 leading-relaxed max-w-2xl">
                    Welcome to your comprehensive Enterprise Resource Planning (ERP) tracking system. Monitor incoming orders, analyze live production pipelines, oversee material allocation, and manage every moving part of your business securely from a single centralized dashboard.
                </p>
                
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <div class="flex items-center gap-2 text-xs text-indigo-200">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Real-time tracking
                    </div>
                    <div class="flex items-center gap-2 text-xs text-indigo-200">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Automated reports
                    </div>
                    <div class="flex items-center gap-2 text-xs text-indigo-200">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Secure access
                    </div>
                </div>
            </div>

            <!-- Quick Action Box / Stats card preview inside banner -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-5 border border-white/15 flex flex-col justify-between space-y-4 shadow-xl">
                <div>
                    <span class="text-xs uppercase tracking-wider text-indigo-300 font-medium">System Status</span>
                    <h3 class="text-lg font-bold text-white mt-1">All Systems Operational</h3>
                    <p class="text-xs text-indigo-200/80 mt-1">Modules, database connections, and sync queues are running smoothly without interruptions.</p>
                </div>
                <div class="pt-2 border-t border-white/10 flex justify-between items-center text-xs text-indigo-200">
                    <span>Last Sync: Just now</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                </div>
            </div>
        </div>
    </div>
</div>