<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6">Dropbox Integration</h2>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Error Message -->
    @if ($connectionError)
        <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg">
            {{ $connectionError }}
        </div>
    @endif

    <!-- Authorization URL Section -->
    @if ($showAuthUrlSection)
        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
            <h3 class="font-semibold text-lg mb-2">Step 1: Authorize with Dropbox</h3>
            <p class="mb-4">Click the button below to open Dropbox authorization in a new window:</p>
            <a href="{{ $authUrl }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Connect to Dropbox
            </a>
            <div class="mt-4 text-sm text-gray-600">
                <p>Once authorized, Dropbox will redirect you to a page with a code.</p>
                <button wire:click="proceedToCodeEntry" class="mt-2 text-blue-600 hover:underline">
                    I've authorized, take me to the next step
                </button>
            </div>
        </div>
    @endif

    <!-- Authorization Code Section -->
    @if ($showCodeSection)
        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
            <h3 class="font-semibold text-lg mb-2">Step 2: Enter Authorization Code</h3>
            <p class="mb-4">Paste the code from the URL you were redirected to:</p>
            <div class="mb-4">
                <input type="text" wire:model="authCode" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Paste your authorization code here">
                @error('authCode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <button wire:click="exchangeCodeForToken" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Submit Code
            </button>
        </div>
    @endif

    <!-- Connected Section -->
    @if ($showSuccessSection)
        <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-green-50">
            <div class="flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <h3 class="font-semibold text-lg">Connected to Dropbox</h3>
            </div>
            
            @if ($dropboxAccountInfo)
                <div class="mb-4 p-3 bg-white rounded border border-gray-200">
                    <h4 class="font-medium text-gray-800">Account Information</h4>
                    <div class="mt-2 text-sm">
                        <p><span class="font-medium">Name:</span> {{ $dropboxAccountInfo['name']['display_name'] ?? 'Not available' }}</p>
                        <p><span class="font-medium">Email:</span> {{ $dropboxAccountInfo['email'] ?? 'Not available' }}</p>
                    </div>
                </div>
            @endif
            
            <div class="mb-4">
                <button wire:click="toggleTokenDetails" class="text-blue-600 text-sm hover:underline flex items-center">
                    {{ $showTokenDetails ? 'Hide' : 'Show' }} Connection Details
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $showTokenDetails ? 'M19 9l-7 7-7-7' : 'M9 5l7 7-7 7' }}" />
                    </svg>
                </button>
                
                @if ($showTokenDetails)
                    <div class="mt-2 p-3 bg-gray-50 rounded border border-gray-200 text-sm">
                        <p><span class="font-medium">Connected since:</span> {{ $tokenInfo['connected_since'] ?? 'Unknown' }}</p>
                        <p><span class="font-medium">Last refreshed:</span> {{ $tokenInfo['last_refreshed'] ?? 'Unknown' }}</p>
                        <p><span class="font-medium">Access token expires:</span> {{ $tokenInfo['access_token_expires'] ?? 'Unknown' }}</p>
                    </div>
                @endif
            </div>
            
            <div class="flex space-x-4">
                <button wire:click="refreshToken" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Refresh Token
                </button>
                
                <button wire:click="fetchAccountInfo" class="px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Test Connection
                </button>
                
                <button wire:click="disconnectDropbox" class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Disconnect
                </button>
            </div>
        </div>
    @endif
</div>