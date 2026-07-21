<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8" wire:key="my-setting-module-{{time()}}">
        <div class="md:grid md:gap-6">
            <!-- App Settings Section -->
            <div class="md:col-span-1 flex justify-between mb-4">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">{{$moduleTitle}}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Configure your application settings
                    </p>
                </div>
            </div>
            
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form wire:submit.prevent="updateDataObject">
                    <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                        @include("livewire.common.messages")
                        <div class="mt-6 grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="app_name">
                                    App Name
                                </label>
                                <div class="mt-2">
                                    <input type="text" id="app_name" maxlength="99" wire:model="app_name" required
                                        placeholder="Your Application Name" autocomplete="off"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="text-red-500 text-xs mt-1">@error('app_name') {{ $message }} @enderror</div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                        <button type="submit"
                            class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 rounded-md">
                            <span class="indicator-label" wire:loading.remove wire:target="updateDataObject">
                                Update Settings
                            </span>
                            <span class="indicator-progress" wire:loading wire:target="updateDataObject">
                                <svg class="w-5 h-5 mr-3 ml-3 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- SMTP Configuration Section -->
            <div class="md:col-span-1 flex justify-between mt-8 mb-4">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">SMTP Configuration</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Configure your email settings for the entire application
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form wire:submit.prevent="saveSettings">
                    <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                        @if ($success)
                            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                                {{ $success }}
                            </div>
                        @endif

                        @if ($error)
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                                {{ $error }}
                            </div>
                        @endif

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="mail_host">
                                    SMTP Host
                                </label>
                                <div class="mt-2">
                                    <input type="text" wire:model="mail_host" required
                                        placeholder="smtp.gmail.com" id="mail_host"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="text-red-500 text-xs mt-1">@error('mail_host') {{ $message }} @enderror</div>
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="mail_port">
                                    SMTP Port
                                </label>
                                <div class="mt-2">
                                    <input type="number" wire:model="mail_port" required placeholder="587"
                                        id="mail_port"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="text-red-500 text-xs mt-1">@error('mail_port') {{ $message }} @enderror</div>
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="mail_username">
                                    SMTP Username
                                </label>
                                <div class="mt-2">
                                    <input type="text" wire:model="mail_username" required
                                        placeholder="your.email@gmail.com" id="mail_username"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="text-red-500 text-xs mt-1">@error('mail_username') {{ $message }} @enderror</div>
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="mail_password">
                                    SMTP Password
                                </label>
                                <div class="mt-2 relative">
                                    <input type="password" wire:model="mail_password" required
                                        placeholder="Your password or app password" id="mail_password"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="text-red-500 text-xs mt-1">@error('mail_password') {{ $message }} @enderror</div>
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="mail_encryption">
                                    Encryption
                                </label>
                                <div class="mt-2">
                                    <select wire:model="mail_encryption" id="mail_encryption"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="tls">TLS</option>
                                        <option value="ssl">SSL</option>
                                        <option value="null">None</option>
                                    </select>
                                </div>
                                <div class="text-red-500 text-xs mt-1">@error('mail_encryption') {{ $message }} @enderror</div>
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="mail_from_address">
                                    From Email Address
                                </label>
                                <div class="mt-2">
                                    <input type="email" wire:model="mail_from_address" required
                                        placeholder="your.email@gmail.com" id="mail_from_address"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="text-red-500 text-xs mt-1">@error('mail_from_address') {{ $message }} @enderror</div>
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="mail_from_name">
                                    From Name
                                </label>
                                <div class="mt-2">
                                    <input type="text" wire:model="mail_from_name" required
                                        placeholder="Your Company Name" id="mail_from_name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="text-red-500 text-xs mt-1">@error('mail_from_name') {{ $message }} @enderror</div>
                            </div>

                            
                   <!-- Email Signature Editor with Preview Button -->
                    <div class="sm:col-span-2 mt-2" id="previewSection">
                        <div>
                            <label class="block font-medium text-sm text-gray-700" for="mail_signature">
                                Email Signature (HTML)
                            </label>
                            <div class="mt-2">
                                <textarea rows="10" 
                                    wire:model.live="mail_signature"
                                    id="mail_signature"
                                    placeholder="Type your signature here..."
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                            </div>
                            <div class="text-red-500 text-xs mt-1">@error('mail_signature') {{ $message }} @enderror</div>
                            
                            <div class="mt-3 flex justify-end">
                                <a type="button" href="#previewSection"
                                    wire:click="generatePreview" 
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.26-.966zM2.429 4.74a1 1 0 10-.517 1.932l.966.259a1 1 0 00.517-1.932l-.966-.26zm8.814-.569a1 1 0 00-1.415-1.414l-.707.707a1 1 0 101.415 1.415l.707-.708zm-7.071 7.072l.707-.707A1 1 0 003.465 9.12l-.708.707a1 1 0 001.415 1.415zm3.2-5.171a1 1 0 00-1.3 1.3l4 10a1 1 0 001.823.075l1.38-2.759 3.018 3.02a1 1 0 001.414-1.415l-3.019-3.02 2.76-1.379a1 1 0 00-.076-1.822l-10-4z" clip-rule="evenodd" />
                                    </svg>
                                    Generate Preview
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    @if($mail_signature_preview)
                    <div class="sm:col-span-2 mt-4">
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="block font-medium text-sm text-gray-700">
                                    Signature Preview
                                </label>
                                <button type="button" 
                                    wire:click="clearPreview" 
                                    class="text-xs text-gray-500 hover:text-gray-700">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Close Preview
                                    </span>
                                </button>
                            </div>
                            <div class="mt-2 p-4 border rounded-md min-h-[10rem] bg-white shadow-sm">
                                <div class="preview-container">
                                    {!! $mail_signature_preview !!}
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                <p>This is how your signature will appear in emails.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                        <button type="submit"
                            class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 rounded-md">
                            <span class="indicator-label" wire:loading.remove wire:target="saveSettings">
                                Save SMTP Settings
                            </span>
                            <span class="indicator-progress" wire:loading wire:target="saveSettings">
                                <svg class="w-5 h-5 mr-3 ml-3 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Test Email Section -->
            <div class="md:col-span-1 flex justify-between mt-8 mb-4">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Test Connection</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Send a test email to verify your configuration
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form wire:submit.prevent="testConnection">
                    <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                        @if ($testSuccess)
                            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                                {{ $testSuccess }}
                            </div>
                        @endif

                        @if ($testError)
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                                {{ $testError }}
                            </div>
                        @endif
                        
                        <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <label class="block font-medium text-sm text-gray-700" for="testEmail">
                                    Test Email Address
                                </label>
                                <div class="mt-2">
                                    <input type="email" wire:model="testEmail" required
                                        placeholder="Enter email to test" id="testEmail"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="text-red-500 text-xs mt-1">@error('testEmail') {{ $message }} @enderror</div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                        <button type="submit"
                            class="px-4 py-2 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0 rounded-md">
                            <span class="indicator-label" wire:loading.remove wire:target="testConnection">
                                Send Test Email
                            </span>
                            <span class="indicator-progress" wire:loading wire:target="testConnection">
                                <svg class="w-5 h-5 mr-3 ml-3 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>