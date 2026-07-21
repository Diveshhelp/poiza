<div>
  @if($show)
  <div class="fixed inset-0 z-50 overflow-y-auto backdrop-blur-sm bg-black/30"
    aria-labelledby="security-code-modal"
    role="dialog"
    aria-modal="true">
    <div class="flex min-h-screen items-center justify-center p-4">
      <!-- Modal panel with subtle animation -->
      <div class="relative transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md animate-fadeIn dark:bg-slate-800">
        <!-- Top accent bar -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-1.5 w-full"></div>
        
        <div class="px-6 py-6">
          <div class="flex items-center mb-5">
            <!-- Lock icon -->
            <div class="mr-4 bg-indigo-100 rounded-full p-2.5 dark:bg-indigo-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
              Security Verification
            </h3>
          </div>
          
          <p class="text-sm text-gray-600 mb-6 dark:text-gray-300">
            For your security, please enter your security code.
          </p>
          
          <div class="mt-4">
            <label for="security-code" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">Email</label>
            <input
              id="email" 
              class="block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xl tracking-widest text-center font-mono dark:bg-slate-700 dark:border-slate-600 dark:text-white" 
              type="text" 
              name="email" 
              wire:model="email"
              required 
              autofocus 
              placeholder="your@email.com"
            />
            @error('email')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              {{ $message }}
            </p>
            @enderror

            <label for="security-code" class="mt-2 block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">Security Code</label>
            <input
              id="security-code"
              type="password"
              wire:model="code"
              autofocus
              wire:keydown.enter="verifyCode"
              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg tracking-widest text-center font-mono dark:bg-slate-700 dark:border-slate-600 dark:text-white"
              placeholder="• • • • • •"
              maxlength="15"
              autocomplete="off"
            />
            @error('code')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              {{ $message }}
            </p>
            @enderror
          </div>
          
          <div class="mt-8 flex justify-between items-center">
            <button
              type="button"
              wire:click="$set('show', false)"
              class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors dark:bg-slate-700 dark:text-gray-200 dark:border-slate-600 dark:hover:bg-slate-600">
              Cancel
            </button>
            
            <button
              wire:click="verifyCode"
              class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-md hover:shadow-lg">
              <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span wire:loading.remove>Verify Code</span>
              <svg wire:loading class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading>Verifying...</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>