<div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-4 ml-5">
        {{-- Flash Messages --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Document Selection Header --}}
        <div class="flex flex-wrap items-center justify-between">
            <div class="min-w-0 flex-1 pr-2">
                <h3 class="text-lg font-medium text-gray-900"> Subscriptions List
                </h3>
                <p
                    class="mt-1 md:mt-2 text-xs md:text-sm text-gray-600 leading-relaxed line-clamp-1 md:line-clamp-none">
                </p>
            </div>
            <div class="mb-2 flex shrink-0 md:ml-4 md:mt-0">
                <a href="{{ route('document-collections') }}"
                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="mr-1 md:mr-3 size-4 md:size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    <span class="whitespace-nowrap">Go back</span>
                </a>
                <a href="{{ route("admin.login-analytics") }}"
                    class="ml-2 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                    <span class="whitespace-nowrap">Login History</span>
                </a>
            </div>
        </div>

        {{-- Documents Table --}}
        <div class="overflow-x-auto mt-4">
            <div x-data="{ 
                activeTeam: null,
                activePanel: null,
                togglePanel(teamId, panel) {
                    if (this.activeTeam === teamId && this.activePanel === panel) {
                        // If clicking the same panel that's already open, close it
                        this.activeTeam = null;
                        this.activePanel = null;
                    } else {
                        // Otherwise, open the new panel and close any others
                        this.activeTeam = teamId;
                        this.activePanel = panel;
                    }
                }
            }">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Team Name
                            </th>
                            <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trial Mode
                            </th>
                            <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trial Start
                            </th>
                            <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trial End
                            </th>
                            <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Members
                            </th>
                            <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subscriptions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($teamList as $team)
                            <tr class="font-medium bg-gray-50 hover:bg-gray-100">
                                <td class="px-2 py-1 whitespace-nowrap text-xs">
                                    <div>{{ $team->name ?? '' }}</div>
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-xs">
                                    <div>{{ $team->is_trial_mode ?? '' }}</div>
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-xs">
                                    {{ $team->trial_start_date }}
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-xs">
                                    {{ $team->trial_end_date }}
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-xs">
                                    <button 
                                        @click="togglePanel({{ $team->id }}, 'members')" 
                                        class="text-blue-600 hover:text-blue-900 text-xs flex items-center"
                                    >
                                        {{ $team->teamUsers->count() }} Member
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                            class="h-3 w-3 ml-1 transition-transform" 
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor" 
                                            :class="{'rotate-180': activeTeam === {{ $team->id }} && activePanel === 'members'}"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-2 py-1 text-xs">
                                    <button 
                                        @click="togglePanel({{ $team->id }}, 'subscription')" 
                                        class="text-xs flex items-center"
                                        :class="{
                                            'text-blue-600 hover:text-blue-900': {{ $team->teamSubHistory->count() }} > 0,
                                            'text-gray-400 cursor-not-allowed': {{ $team->teamSubHistory->count() }} === 0
                                        }"
                                        :disabled="{{ $team->teamSubHistory->count() }} === 0"
                                    >
                                        {{ $team->teamSubHistory->count() }} Subscription
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                            class="h-3 w-3 ml-1 transition-transform" 
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor" 
                                            :class="{'rotate-180': activeTeam === {{ $team->id }} && activePanel === 'subscription'}"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    
                                </td>
                                <td class="px-2 py-1 text-xs">
                                <button 
                                        wire:click="openModal({{ $team->id }})" 
                                        class="text-green-600 hover:text-green-900 text-xs flex items-center"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Extend
                                    </button>
                                </td>
                            </tr>

                            @if($team->teamUsers->count() > 0)
                                <tr x-show="activeTeam === {{ $team->id }} && activePanel === 'members'" x-cloak>
                                    <td colspan="6" class="px-2 py-1">
                                        <div class="bg-white p-2 rounded shadow-sm">
                                            <h4 class="font-medium text-gray-700 text-xs mb-1">Member of {{ $team->name }}</h4>
                                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Name</th>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Role</th>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Email</th>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Token</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($team->teamUsers as $member)
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-2 py-1 text-xs">{{ $member->name }}</td>
                                                            <td class="px-2 py-1 text-xs">{{ $member->role ?? 'Member' }}</td>
                                                            <td class="px-2 py-1 text-xs">{{ $member->email }}</td>
                                                            <td class="px-2 py-1 text-xs">{{ $member->unsubscribe_token }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @else
                            <tr x-show="activeTeam === {{ $team->id }} && activePanel === 'members'" x-cloak>
                                    <td colspan="6" class="px-2 py-1">
                                        <div class="bg-white p-2 rounded shadow-sm">
                                            <h4 class="font-medium text-gray-700 text-xs mb-1">Member of {{ $team->name }}</h4>
                                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Name</th>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Role</th>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Email</th>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Token</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-2 py-1 text-xs text-center" colspan="5">No any member yet</td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            @if($team->teamSubHistory->count() > 0)
                                <tr x-show="activeTeam === {{ $team->id }} && activePanel === 'subscription'" x-cloak>
                                    <td colspan="6" class="px-2 py-1">
                                        <div class="bg-white p-2 rounded shadow-sm">
                                            <h4 class="font-medium text-gray-700 text-xs mb-1">Subscription History of {{ $team->name }}</h4>
                                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Plan ID</th>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Razorpay Subscription ID</th>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Plan Start Date</th>
                                                        <th scope="col"
                                                            class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Plan End Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($team->teamSubHistory as $history)
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-2 py-1 text-xs">{{ $history->plan_id }}</td>
                                                            <td class="px-2 py-1 text-xs">{{ $history->razorpay_subscription_id }}</td>
                                                            <td class="px-2 py-1 text-xs">{{ $history->starts_at }}</td>
                                                            <td class="px-2 py-1 text-xs">{{ $history->ends_at }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="px-2 py-1 whitespace-nowrap text-center text-gray-500 text-xs">
                                    No team selected yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
     <!-- Modal for Extending Subscription -->
     <div x-data="{ show: @entangle('isModalOpen') }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal Content -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Extend Subscription
                            </h3>
                            <div class="mt-2">
                                @if($selectedTeam)
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-500">Team: <span class="font-semibold">{{ $selectedTeam->name??'' }}</span></p>
                                        </div>
                                    
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                                            <input type="date" id="startDate" wire:model="startDate" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('startDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                                            <input type="date" id="endDate" wire:model="endDate" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('endDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                   
                                @else
                                    <p class="text-sm text-red-500">No subscription found for this team.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="extendSubscription" type="button" class="ml-2 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0" @if(!$selectedTeam) disabled @endif>
                        Extend Subscription
                    </button>
                    <button wire:click="closeModal" type="button" class="ml-2 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
