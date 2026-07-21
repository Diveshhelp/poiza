{{-- resources/views/livewire/my-referrals.blade.php --}}
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-4">My Referrals</h2>
                
                {{-- Success/Error Messages --}}
                @if ($successMessage)
                    <div class="mt-4 bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ $successMessage }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if ($errorMessage)
                    <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ $errorMessage }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Referral Statistics --}}
                @php
                    $stats = $this->getReferralStats();
                @endphp
                <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-indigo-50 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-indigo-800">Total Referrals</h3>
                        <p class="text-3xl font-bold text-indigo-600">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-yellow-800">Pending</h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-green-800">Completed</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-purple-800">Bonus Days</h3>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['bonus_days_earned'] }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-sm text-purple-700">{{ $stats['bonus_days_remaining'] }} days available</p>
                            @if($stats['bonus_days_remaining'] > 0)
                                <button 
                                    wire:click="applyBonusDays"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-75"
                                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 cursor-pointer"
                                >
                                    <span wire:loading.remove wire:target="applyBonusDays">Apply Days</span>
                                    <span wire:loading wire:target="applyBonusDays">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Bonus explanation card --}}
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-4 rounded-lg shadow-md mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium">Earn 7-Day Subscription Bonus!</h3>
                            <p class="mt-1 text-white text-opacity-90">
                                For each friend who registers using your referral link, you'll receive a 7-day extension to your subscription. 
                                Invite more friends to get more free days!
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- Search and Filter --}}
                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <div class="w-full md:w-1/2">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <div class="mt-1">
                            <input type="text" wire:model.debounce.300ms="search" id="search" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Search by name or email">
                        </div>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label for="filter" class="block text-sm font-medium text-gray-700">Filter</label>
                        <select wire:model="filter" id="filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="all">All Referrals</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                
                {{-- Loading indicator --}}
                <div wire:loading class="flex justify-center items-center py-4">
                    <svg class="animate-spin h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                
                {{-- Referrals Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Referral Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Join Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bonus Applied
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($referrals as $referral)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($referral->refer_to && isset($referral->referred_user))
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $referral->referred_user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $referral->referred_user->email }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500">
                                                    Pending user
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($referral->is_join == 1)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $referral->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($referral->bonus_applied == 1)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>Applied</span>
                                                <span class="mx-1 text-green-600">•</span>
                                                <span class="text-xs text-green-700">{{ $referral->bonus_applied_at ? \Carbon\Carbon::parse($referral->bonus_applied_at)->format('M d, Y') : 'N/A' }}</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No referrals found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Links --}}
                <div class="mt-4">
                    {{ $referrals->links() }}
                </div>
                
                {{-- Referral Link Section --}}
                <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Share Your Referral Link</h3>
                    
                    <div x-data="{ copied: false, referralUrl: '{{ url('/register?ref=' . base64_encode(Auth::id() . '-' . uniqid())) }}' }">
                        <div class="flex">
                            <input 
                                type="text" 
                                readonly 
                                x-ref="referralInput"
                                x-bind:value="referralUrl"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-l-md" 
                            >
                            <button 
                                @click="navigator.clipboard.writeText(referralUrl); copied = true; setTimeout(() => copied = false, 2000)"
                                class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <template x-if="!copied">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </template>
                                <template x-if="copied">
                                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </template>
                            </button>
                        </div>
                        <p class="mt-2 text-sm text-gray-500" x-show="copied">Copied to clipboard!</p>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            Share this link with your friends. When they sign up using your link, you'll receive referral credit!
                        </p>
                    </div>
                    
                    {{-- Social Share Buttons --}}
                    <div class="mt-4">
                        <span class="text-sm font-medium text-gray-700">Share with</span>
                        <div class="mt-2 flex space-x-4">
                            @php
                                $shareUrl = url('/register?ref=' . base64_encode(Auth::id() . '-' . uniqid()));
                                $shareText = 'Join me on ' . config('app.name') . '!';
                            @endphp
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareText) }}" target="_blank" class="text-blue-400 hover:text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" class="text-blue-600 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="mailto:?subject={{ urlencode('Join me on ' . config('app.name')) }}&body={{ urlencode('Check out this platform using my referral link: ' . $shareUrl) }}" class="text-red-500 hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" class="text-blue-700 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode('Join me on ' . config('app.name') . ' using my referral link: ' . $shareUrl) }}" target="_blank" class="text-green-500 hover:text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                {{-- Referral Instructions --}}
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">How It Works</h3>
                    <ol class="list-decimal pl-5 space-y-2 text-gray-600">
                        <li>Share your unique referral link with friends</li>
                        <li>When they sign up using your link, they'll be counted as your referral</li>
                        <li><span class="font-medium text-indigo-600">You'll earn a {{ env('DEFAULT_DAY_BONUS') }}-day subscription bonus for each successful referral</span></li>
                        <li>Track your referrals and bonus days in this dashboard</li>
                    </ol>
                </div>
                
                {{-- Bonus Day Management Section --}}
                <div class="mt-8 bg-white border border-gray-200 shadow rounded-lg p-6">
                    <h3 class="text-xl font-medium text-gray-900 mb-4">Your Bonus Days</h3>
                    
                    <div class="bg-purple-50 rounded-lg p-4 mb-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-base font-medium text-purple-800">Total Bonus Days Earned: <span class="font-bold">{{ $stats['bonus_days_earned'] }}</span></p>
                                <p class="text-base font-medium text-purple-800">Available to Apply: <span class="font-bold">{{ $stats['bonus_days_remaining'] }}</span></p>
                            </div>
                            
                            @if($stats['bonus_days_remaining'] > 0)
                                <div class="mt-4 md:mt-0">
                                    <button 
                                        wire:click="applyBonusDays"
                                        wire:loading.attr="disabled"
                                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0 cursor-pointer"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span wire:loading.remove wire:target="applyBonusDays">Apply My Bonus Days</span>
                                        <span wire:loading wire:target="applyBonusDays" class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Applying...
                                        </span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-base font-medium text-gray-900 mb-2">How Bonus Days Work</h4>
                        <ul class="list-disc pl-5 space-y-1 text-gray-600">
                            <li>You earn {{ env('DEFAULT_DAY_BONUS') }} bonus days for each person who registers using your referral link</li>
                            <li>Your bonus days accumulate in your account until you choose to apply them</li>
                            <li>Click "Apply My Bonus Days" to extend your current subscription</li>
                            <li>The system will extend your subscription end date by the number of available bonus days</li>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
