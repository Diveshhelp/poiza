<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>

    @isset($isnewuser)
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-lg">
            <div class="text-center">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('my-password-reset') }}">
                    {{ __('One Quick Step: Create Your Password!') }}
                </a>
            </div>
        </div>
    @endif
</div>