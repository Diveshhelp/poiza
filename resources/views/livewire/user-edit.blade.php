<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="mt-10 sm:mt-0">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h3 class="text-lg font-medium text-gray-900">Edit Profile</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        Update your account's profile information and password.
                    </p>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('user-content') }}"
                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mr-3 size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <div class="mt-5 md:mt-0">
                <div class="px-0 sm:px-0 lg:px-0">
                    <div class="mt-8 flow-root">
                        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                            <form wire:submit="updateProfile">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <!-- Name -->
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">
                                                Name
                                            </label>
                                            <div class="mt-1">
                                                <input type="text" wire:model="name"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">
                                                Email
                                            </label>
                                            <div class="mt-1">
                                                <input type="email" wire:model="email"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Security Code -->
                                        <div class="sm:col-span-6">
                                            <label class="block text-sm font-medium text-gray-700">
                                                Security Code
                                            </label>
                                            <div class="mt-1">
                                                <div class="relative">
                                                    <input type="password" wire:model="security_code" id="security_code"
                                                        class="block w-full pl-10 pr-10 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                        </svg>
                                                    </div>
                                                    <button type="button" onclick="togglePassword('security_code')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 show-eye">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 hide-eye hidden">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @error('security_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="sm:col-span-6">
                                            <div class="relative">
                                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                                    <div class="w-full border-t border-gray-300"></div>
                                                </div>
                                                <div class="relative flex justify-center">
                                                    <span class="bg-white px-3 py-1.5 text-sm font-medium text-gray-600 rounded-full border border-gray-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                        </svg>
                                                        Change Password
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Current Password -->
                                        <div class="sm:col-span-6">
                                            <label class="block text-sm font-medium text-gray-700">
                                                Current Password
                                            </label>
                                            <div class="mt-1 relative">
                                                <div class="relative">
                                                    <input type="password" wire:model="current_password" id="current_password"
                                                        class="block w-full pl-10 pr-10 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                    </div>
                                                    <button type="button" onclick="togglePassword('current_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 show-eye transition-opacity duration-200">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 hide-eye hidden transition-opacity duration-200">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- New Password -->
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">
                                                New Password
                                            </label>
                                            <div class="mt-1 relative">
                                                <div class="relative">
                                                    <input type="password" wire:model="new_password" id="new_password"
                                                        class="block w-full pl-10 pr-10 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                    </div>
                                                    <button type="button" onclick="togglePassword('new_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 show-eye transition-opacity duration-200">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 hide-eye hidden transition-opacity duration-200">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">
                                                Confirm Password
                                            </label>
                                            <div class="mt-1 relative">
                                                <input type="password" wire:model="password_confirmation" id="password_confirmation"
                                                    class="block w-full pl-10 pr-10 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                    </div>
                                                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 show-eye">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 hide-eye hidden">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                    </svg>
                                                </button>
                                                @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <script>
                                            function togglePassword(inputId) {
                                                const input = document.getElementById(inputId);
                                                const parent = input.parentElement;
                                                const showEye = parent.querySelector('.show-eye');
                                                const hideEye = parent.querySelector('.hide-eye');
                                                
                                                if (input.type === 'password') {
                                                    input.type = 'text';
                                                    showEye.classList.add('hidden');
                                                    hideEye.classList.remove('hidden');
                                                } else {
                                                    input.type = 'password';
                                                    showEye.classList.remove('hidden');
                                                    hideEye.classList.add('hidden');
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                    <button type="submit"
                                        class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                                        <div wire:loading wire:target="updateProfile" class="mr-2">
                                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>