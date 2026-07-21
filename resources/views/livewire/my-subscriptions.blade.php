<div class="mb-5">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 pb-4 ">
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
        @if($cancelStatus === 'success' && $message)
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"> {{ $message }}</span>
            </div>
        @elseif($cancelStatus === 'failed' && $message)
             <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ $message }}</span>
            </div>
        @endif
        {{-- Document Selection Header --}}
        <div class="flex flex-wrap items-center justify-between">
            <div class="min-w-0 flex-1 pr-2">
                <h3 class="text-lg font-medium text-gray-900"> Team Subscriptions & Invoices
                </h3>
                <p class="mt-1 md:mt-2 text-xs md:text-sm text-gray-600 leading-relaxed line-clamp-1 md:line-clamp-none">
                </p>
            </div>
            <div class="mb-2 flex shrink-0 md:ml-4 md:mt-0">
                <a href="{{ route('dashboard') }}"
                    class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-xs md:text-sm font-semibold before:w-0 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="mr-1 md:mr-3 size-4 md:size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    <span class="whitespace-nowrap">Back</span>
                </a>
            </div>
        </div>
        {{-- Documents Table --}}
        <div class="overflow-x-auto mt-4">
            <!-- document-selections.blade.php -->
            <div>
                  <!-- Status messages -->
                 
                <!-- Main Document Table -->
                <table class="min-w-full divide-y divide-gray-200 text-xs">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Start At</th>
                            <th scope="col"
                                class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                End At</th>
                            <th scope="col"
                                class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At</th>
                            <th scope="col"
                                class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Type</th>
                            <th scope="col"
                                class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                            <th scope="col"
                                class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cancel Subscription</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                         @foreach ($customerTrial as $index => $trialplan)
                            <tr class="bg-blue-50 hover:bg-blue-100 font-medium bg-gray-50 hover:bg-gray-100">
                                <td class="px-2 py-1 whitespace-nowrap text-xs">
                                    {{date('d-m-Y', strtotime($trialplan['starts_at'])) }}
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-xs">
                                    {{date('d-m-Y', strtotime($trialplan['ends_at'])) }}
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-xs">
                                    {{ date('d-m-Y', strtotime($trialplan['created_at']))}}
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-xs">
                                {{ $trialplan['razorpay_subscription_id'] }}
                                </td>
                                <td class="px-2 py-1 text-xs">
                                    -
                                </td>
                                <td class="px-2 py-1 text-xs">
                                    &nbsp;
                                </td>
                            </tr>
                    @endforeach

                        @forelse ($customerSubscriptions as $index => $plan)
                            @if($plan['success'])
                                <tr class="bg-blue-50 hover:bg-blue-100 font-medium bg-gray-50 hover:bg-gray-100">
                                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                                        {{ date('d-m-Y', $plan['subscription']['current_start']) }}
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                                        {{ date('d-m-Y', $plan['subscription']['current_end']) }}
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                                        {{ date('d-m-Y h:i: A', $plan['subscription']['created_at']) }}
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-xs">
                                        {{ $plan['subscription']['payment_method'] }}
                                    </td>
                                    <td class="px-2 py-1 text-xs">
                                        <button type="button" onclick="toggleInvoices('invoice-{{ $index }}')"
                                            class="text-xs flex items-center text-blue-600 hover:text-blue-900">
                                            <span>View Invoices</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    
                                    </td>
                                    <td class="px-2 py-1 text-xs">
                                        @php
                                            $cancelSubscription = App\Models\TeamSubscriptions::where('razorpay_subscription_id', $plan['subscription']['id'])
                                                ->where("team_id", Auth::user()->currentTeam->id)
                                                ->first()->canceled_at;
                                        @endphp
                                        
                                        @if($cancelSubscription == NULL && $plan['subscription']['status'] == 'active')
                                            <!-- Improved Cancel Button -->
                                            <button 
                                                type="button"
                                                onclick="showCancelModal('{{ $plan['subscription']['id'] }}')"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-150 ease-in-out transform hover:scale-105">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Cancel
                                            </button>
                                            
                                            <!-- Enhanced Cancellation Modal -->
                                            <div 
                                                x-data="{ 
                                                    open: false, 
                                                    subscriptionId: '', 
                                                    reason: '',
                                                    isProcessing: false,
                                                    clearAndClose() {
                                                        this.open = false;
                                                        this.reason = '';
                                                        this.isProcessing = false;
                                                        this.subscriptionId = '';
                                                    },
                                                    init() {
                                                        this.$watch('$wire.cancelStatus', value => {
                                                            this.isProcessing = (value === 'processing');
                                                            
                                                            if (value === 'success') {
                                                                setTimeout(() => { 
                                                                    this.clearAndClose();
                                                                    // Reload the page to show updated status
                                                                    window.location.reload();
                                                                }, 1000);
                                                            }
                                                        });
                                                    }
                                                }"
                                                x-show="open"
                                                x-on:show-cancel-modal.window="open = true; subscriptionId = $event.detail.subscriptionId"
                                                x-on:close-cancel-modal.window="clearAndClose()"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 transform scale-95"
                                                x-transition:enter-end="opacity-100 transform scale-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100 transform scale-100"
                                                x-transition:leave-end="opacity-0 transform scale-95"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
                                                style="display: none;"
                                            >
                                                <div 
                                                    class="bg-white rounded-lg shadow-2xl w-full max-w-md mx-auto overflow-hidden"
                                                    @click.away="clearAndClose()"
                                                    x-show="open"
                                                    x-transition
                                                >
                                                    <!-- Modal Header with Icon -->
                                                    <div class="px-6 py-4 bg-red-50 border-b border-gray-200 flex items-center">
                                                        <div class="flex-shrink-0 bg-red-100 rounded-full p-2 mr-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-medium text-gray-900">Cancel Subscription</h3>
                                                    </div>
                                                    
                                                    <div class="px-6 py-4">
                                                        <p class="text-sm text-gray-600 mb-4">
                                                            You're about to cancel your subscription. This action cannot be undone. 
                                                            Your subscription will remain active until the end of the current billing period.
                                                        </p>
                                                        
                                                        <div class="mb-4">
                                                            <label for="cancellation-reason" class="block text-sm font-medium text-gray-700 mb-1">
                                                                Why are you cancelling? (optional)
                                                            </label>
                                                            <textarea 
                                                                id="cancellation-reason" 
                                                                x-model="reason" 
                                                                class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                rows="3"
                                                                placeholder="Please let us know why you're cancelling so we can improve our service..."
                                                            ></textarea>
                                                        </div>
                                                        
                                                        <!-- Status message -->
                                                        <div 
                                                            x-show="$wire.message && $wire.cancelStatus" 
                                                            x-transition
                                                            class="mb-4 p-3 rounded-md text-sm"
                                                            :class="$wire.cancelStatus === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'"
                                                        >
                                                            <div class="flex">
                                                                <div class="flex-shrink-0">
                                                                    <template x-if="$wire.cancelStatus === 'success'">
                                                                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </template>
                                                                    <template x-if="$wire.cancelStatus === 'failed'">
                                                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </template>
                                                                </div>
                                                                <div class="ml-3">
                                                                    <p x-text="$wire.message"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Modal Footer -->
                                                    <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-3">
                                                        <button 
                                                            type="button" 
                                                            @click="clearAndClose()" 
                                                            x-bind:disabled="isProcessing"
                                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150"
                                                        >
                                                            Keep Subscription
                                                        </button>
                                                        <button 
                                                            type="button" 
                                                            @click="isProcessing = true; $wire.cancelSubscription(subscriptionId, reason)"
                                                            x-bind:disabled="isProcessing"
                                                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150"
                                                            :class="{'opacity-75 cursor-not-allowed': isProcessing}"
                                                        >
                                                            <template x-if="!isProcessing">
                                                                <span>Confirm Cancellation</span>
                                                            </template>
                                                            <template x-if="isProcessing">
                                                                <span class="flex items-center">
                                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                    </svg>
                                                                    Processing...
                                                                </span>
                                                            </template>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Script to trigger the modal -->
                                            <script>
                                                function showCancelModal(subscriptionId) {
                                                    window.dispatchEvent(
                                                        new CustomEvent('show-cancel-modal', { 
                                                            detail: { 
                                                                subscriptionId: subscriptionId 
                                                            } 
                                                        })
                                                    );
                                                }
                                            </script>
                                        @else
                                            <!-- Cancelled Status Badge -->
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                                <!-- Invoice subtable - hidden by default -->
                                <tr id="invoice-{{ $index }}" class="hidden">
                                    <td colspan="5" class="px-0 py-0 border-0">
                                        <div class="bg-gray-50 p-2 rounded-md">
                                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice ID</th>
                                                        <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                        <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                                                        <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                        <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($plan['subscription']['invoices'] as $invoice)
                                                    <tr class="bg-white hover:bg-gray-50">
                                                        <td class="px-2 py-1 whitespace-nowrap text-xs">{{ $invoice['id'] }}</td>
                                                        <td class="px-2 py-1 whitespace-nowrap text-xs">{{ $invoice['amount'] / 100 }}</td>
                                                        <td class="px-2 py-1 whitespace-nowrap text-xs">{{ $invoice['currency'] }}</td>
                                                        <td class="px-2 py-1 whitespace-nowrap text-xs">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice['status'] === 'issued' ? 'bg-yellow-100 text-yellow-800' : ($invoice['status'] === 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                                {{ ucfirst($invoice['status']) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-2 py-1 whitespace-nowrap text-xs">{{ $invoice['date'] }}</td>
                                                        <td class="px-2 py-1 whitespace-nowrap text-xs">
                                                            <a href="{{ $invoice['invoice_url'] }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                                View
                                                            </a>
                                                            
                                                        </td>
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
                                <td colspan="5" class="px-2 py-1 whitespace-nowrap text-center text-gray-500 text-xs">
                                    No subscriptions found.
                                </td>
                            </tr>
                        @endforelse

                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
<script>
    function toggleInvoices(id) {
        const element = document.getElementById(id);
        if (element.classList.contains('hidden')) {
            element.classList.remove('hidden');
        } else {
            element.classList.add('hidden');
        }
    }
</script>

<!-- Script to trigger the modal -->
<script>
    function showCancelModal(subscriptionId) {
        window.dispatchEvent(
            new CustomEvent('show-cancel-modal', { 
                detail: { 
                    subscriptionId: subscriptionId 
                } 
            })
        );
    }
</script> 
   
</div>
