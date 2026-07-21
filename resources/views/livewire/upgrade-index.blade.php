<!-- resources/views/livewire/upgrade-plan.blade.php -->
<div>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-800 min-h-screen"
        wire:key="my-upgrade-module-{{time()}}">
        <!-- Header Section -->
        <!-- Header with Left-Aligned Text and Right-Side Checkout Button -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12 gap-6">
            <!-- Left-aligned title and subtitle -->
            <div class="text-left">
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white sm:text-2xl">
                    Choose Your Perfect Plan
                </h2>
                <p class="mt-4 text-xl text-gray-500 dark:text-gray-300">
                    Select the plan that best fits your needs and elevate your experience.
                </p>
            </div>

            <!-- Right-aligned checkout button -->
            <div class="md:ml-auto">
                <button wire:click="checkout"
                    class="group px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out relative overflow-hidden {{ $selectedPlan ? '' : 'opacity-50 cursor-not-allowed' }}"
                    {{ $selectedPlan ? '' : 'disabled' }} wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-wait">

                    <!-- Background animation effect -->
                    <span
                        class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-20 group-hover:animate-pulse transition-opacity duration-300"></span>

                    <!-- Subtle shine effect -->
                    <span
                        class="absolute -inset-full h-full w-1/4 z-5 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-40 group-hover:animate-shine"></span>

                    <!-- Cart icon that appears on hover -->
                    <span
                        class="absolute left-3 transform -translate-x-full opacity-0 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>

                    <!-- Button content with flex layout -->
                    <span class="flex items-center justify-center relative z-10">
                        <!-- Text that moves right when hovered to make room for the cart icon -->
                        <span class="transform group-hover:translate-x-3 transition-transform duration-300 ease-in-out">
                            Proceed to Checkout
                        </span>

                        <!-- Arrow icon -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 ml-2 transform group-hover:translate-x-1 group-hover:scale-110 transition-transform duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>

                        <!-- Loading spinner that shows only during checkout -->
                        <span wire:loading wire:target="checkout"
                            class="absolute inset-0 flex items-center justify-center bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg">
                            <div class="flex items-center">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="ml-2 font-medium whitespace-nowrap">Processing...</span>
                            </div>
                        </span>
                    </span>
                </button>
            </div>
        </div>


        <!-- Error Alert -->
        @if (session()->has('error'))
            <div class="max-w-3xl mx-auto mb-8">
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md" role="alert">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="font-bold">Error</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Plans Grid Section -->
            <div class="mt-6 space-y-6">
                <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 lg:grid-cols-4 gap-x-4">
                    @foreach ($plans as $keyPlan => $myplans)
                        <div class="group relative rounded-lg bg-white dark:bg-gray-700 p-4 shadow ring-1 ring-gray-900/10 dark:ring-gray-600 transition-all duration-200 ease-in-out
                                {{ $selectedPlan === $keyPlan ? 'ring-2 ring-primary scale-102 bg-primary bg-opacity-5 dark:bg-primary dark:bg-opacity-10' : '' }}">

                            <!-- Popular Tag -->
                            @if($myplans['popular'] ?? false)
                                <div class="absolute -top-2 inset-x-0 flex justify-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary text-white shadow-sm">
                                        <svg class="w-2.5 h-2.5 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        POPULAR
                                    </span>
                                </div>
                            @endif

                            <div class="{{ isset($myplans['popular']) && $myplans['popular'] ? 'pt-3' : '' }}">
                                <!-- Plan Name -->
                                <h3 class="text-xl font-bold {{ $selectedPlan === $keyPlan ? 'text-primary' : 'text-gray-900 dark:text-white' }} mb-2">
                                    {{ $myplans['name'] }}
                                </h3>

                                <!-- Price -->
                                <div class="flex items-baseline mb-3">
                                    <span class="text-3xl font-bold {{ $selectedPlan === $keyPlan ? 'text-primary' : 'text-gray-900 dark:text-white' }}">
                                        {{ $myplans['price'] }}
                                    </span>
                                    <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-300">INR</span>
                                    <span class="ml-1 text-xs font-medium text-gray-500 dark:text-gray-400">/mo</span>
                                </div>

                                <!-- Description - shortened -->
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
                                    {{ $myplans['lines'] }}
                                </p>

                                <!-- Feature List - compact -->
                                <ul class="space-y-1.5 mb-4 text-sm">
                                    @foreach ($myplans['features'] as $feature)
                                        @if(is_array($feature) && isset($feature['included']))
                                            <li class="flex items-start">
                                                @if($feature['included'])
                                                    <svg class="w-4 h-4 {{ $selectedPlan === $keyPlan ? 'text-primary' : 'text-green-500' }} flex-shrink-0 mr-1.5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-gray-700 dark:text-gray-300">{{ $feature['text'] }}</span>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mr-1.5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-gray-400">{{ $feature['text'] }}</span>
                                                @endif
                                            </li>
                                        @else
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 {{ $selectedPlan === $keyPlan ? 'text-primary' : 'text-green-500' }} flex-shrink-0 mr-1.5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>

                                <!-- Select Plan Button -->
                                @if($myplans['show_button'] ?? false)
                                    <div>
                                        @if($selectedPlan === $keyPlan)
                                        <button wire:click="checkout"
                                            class="w-full px-3 py-2 text-sm rounded-md font-medium transition duration-200 ease-in-out bg-primary text-white shadow-sm hover:bg-primary-dark">
                                            <span class="flex items-center justify-center relative z-10">
                                                <!-- Text that moves right when hovered to make room for the cart icon -->
                                                <span class="transform group-hover:translate-x-3 transition-transform duration-300 ease-in-out">
                                                    Proceed to Checkout
                                                </span>

                                                <!-- Arrow icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 ml-2 transform group-hover:translate-x-1 group-hover:scale-110 transition-transform duration-300"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>

                                                <!-- Loading spinner that shows only during checkout - now in one line -->
                                                <span wire:loading wire:target="checkout"
                                                    class="absolute inset-0 flex items-center justify-center bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg">
                                                    <div class="flex items-center">
                                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                                stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                            </path>
                                                        </svg>
                                                        <span class="ml-2 font-medium whitespace-nowrap">Processing...</span>
                                                    </div>
                                                </span>
                                            </span>
                                        </button>
                                        @else
                                        <button wire:click="selectPlan('{{ $keyPlan }}')"
                                            class="w-full px-3 py-2 text-sm rounded-md font-medium transition duration-200 ease-in-out
                                                    {{ $selectedPlan === $keyPlan 
                                        ? 'bg-primary text-white shadow-sm hover:bg-primary-dark'
                                        : 'bg-white border border-primary text-primary hover:bg-primary hover:text-white' }}">
                                            {{ $selectedPlan === $keyPlan ? 'Selected' : 'Select Plan' }}
                                        </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-2">
                    <p class="text-xs text-gray-500">* All prices are included of {{ $gst_rate ?? 18 }}% GST</p>
                </div>
            </div>

        <!-- Checkout Button Section -->
        <div class="mt-16 flex justify-center">
            <button wire:click="checkout"
                class="group px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out relative overflow-hidden {{ $selectedPlan ? '' : 'opacity-50 cursor-not-allowed' }}"
                {{ $selectedPlan ? '' : 'disabled' }} wire:loading.attr="disabled"
                wire:loading.class="opacity-75 cursor-wait">

                <!-- Background animation effect -->
                <span
                    class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-20 group-hover:animate-pulse transition-opacity duration-300"></span>

                <!-- Subtle shine effect -->
                <span
                    class="absolute -inset-full h-full w-1/4 z-5 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-40 group-hover:animate-shine"></span>

                <!-- Cart icon that appears on hover -->
                <span
                    class="absolute left-3 transform -translate-x-full opacity-0 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </span>

                <!-- Button content with flex layout -->
                <span class="flex items-center justify-center relative z-10">
                    <!-- Text that moves right when hovered to make room for the cart icon -->
                    <span class="transform group-hover:translate-x-3 transition-transform duration-300 ease-in-out">
                        Proceed to Checkout
                    </span>

                    <!-- Arrow icon -->
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 ml-2 transform group-hover:translate-x-1 group-hover:scale-110 transition-transform duration-300"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>

                    <!-- Loading spinner that shows only during checkout - now in one line -->
                    <span wire:loading wire:target="checkout"
                        class="absolute inset-0 flex items-center justify-center bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg">
                        <div class="flex items-center">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="ml-2 font-medium whitespace-nowrap">Processing...</span>
                        </div>
                    </span>
                </span>
            </button>
        </div>

        <!-- Additional information section -->
        <div class="mt-16 max-w-2xl mx-auto text-center text-gray-500 dark:text-gray-400">
            <p class="text-sm">
                By proceeding with a subscription, you agree to our <a href="{{ route('terms-conditions') }}"
                    class="text-primary hover:underline">Terms of Service</a> and <a href="{{ route('privacy-policy') }}"
                    class="text-primary hover:underline">Privacy Policy</a>.
                Need help? <a href="mailto:support@docmey.com" class="text-primary hover:underline">Contact our support team</a>.
            </p>

            <div class="mt-6 flex justify-center space-x-6">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-primary mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Secure Payment</span>
                </div>
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-primary mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Cancel Anytime</span>
                </div>
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-primary mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                    <span>24/7 Support</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom animations -->
    <style>
        @keyframes shine {
            100% {
                left: 125%;
            }
        }

        .animate-shine {
            animation: shine 1.5s ease-in-out infinite;
        }
    </style>

    <!-- Scripts -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @script
    <script>
        $wire.on('swal:modal', (event) => {
            console.log(event.detail)
            Swal.fire({
                icon: event.detail.type,
                title: event.detail.title,
                text: event.detail.text,
                confirmButtonColor: event.detail.type === 'success' ? '#4CAF50' : '#F44336'
            });
        });
        $wire.on('checkoutReady', (data) => {
            var baseAmount = data.rzpdata.amount;
            var gstAmount = baseAmount * 0.18;
            var finalAmount = baseAmount + gstAmount;
            
            var options = {
                callback_url: "https://eneqd3r9zrjok.x.pipedream.net/",
                subscription_id: data.rzpdata.subscription_id,
                key: data.rzpdata.key,
                amount:finalAmount,
                currency: data.rzpdata.currency,
                name:"{{ env('APP_NAME')}}",
                description: data.rzpdata.plan_name + " Plan Subscription (Incl. 18% GST)",
                order_id: data.rzpdata.order_id,
                image: "{{ asset('public/favicon.ico') }}", // Add your logo URL here
                handler: function (response) {
                    Livewire.dispatch('sub-created', { response: response });
                },
                prefill: {
                    name: "{{ auth()->user()->name ?? '' }}",
                    email: "{{ auth()->user()->email ?? '' }}",
                    mobile: "{{ auth()->user()->mobile ?? '' }}",
                },
                theme: {
                    color: "#0F8389"
                },
                modal: {
                    ondismiss: function () {
                        Livewire.dispatch('payment-cancelled');
                    }
                }
            };
            var rzp = new Razorpay(options);
            rzp.open();
        });
    </script>
    @endscript
</div>