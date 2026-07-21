<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('public/favicon.ico') }}" type="image/x-icon">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Add this to your layout or view if you haven't already -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Optional: For elements that should be invisible but maintain layout */
        [x-cloak="block"] {
            display: block !important;
        }

        /* Optional: For elements that should be invisible but maintain flex layout */
        [x-cloak="flex"] {
            display: flex !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    @if(app()->environment('production'))
            <!-- Production assets using Vite manifest -->
            @php
                $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
                $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
                $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
            @endphp
            
            @if($cssFile)
                <link rel="stylesheet" href="{{ asset('public/build/'.$cssFile) }}">
            @endif
            
            @if($jsFile)
                <script src="{{ asset('public/build/'.$jsFile) }}" defer></script>
            @endif
            <script src="{{ asset('public/vendor/livewire/livewire.js') }}"></script>
            <script src="{{ asset('public/build/assets/my.js') }}"></script>
            <script src="{{ asset('public/my.js') }}"></script>
        @else
            <!-- Development assets -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            <script src="{{ asset('my.js') }}"></script>
            @livewireStyles
        @endif
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

</head>
<body class="font-sans antialiased">
    <div class="bg-light-gray dark:bg-[#18181b] min-h-screen absolute inset-x-0 top-0">
        <!-- Page Loader -->
        <div id="page-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-light-gray dark:bg-[#18181b] bg-opacity-80 dark:bg-opacity-80">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-primary-600"></div>
        </div>
        
        @livewire('sidebar')
        <!-- In your main layout file -->
        <livewire:security-code-modal :show="$showSecurityModal ?? false" />
        <!-- Page Content -->
        <main class="pt-20 pr-4 lg:pl-[240px] xl:pl-[190px] pl-4 text-left">
            <x-notifications/>
            {{-- @livewire('notifications') --}}
           
            {{ $slot ??''}}
        </main>
        @livewire('footer')
    </div>
    @stack('modals')
    @livewireScripts
    
    <!-- Add script to hide loader when page is ready -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide the loader when page is fully loaded
            document.getElementById('page-loader').style.display = 'none';
        });
        
        // Show loader when navigating between pages (for Livewire page transitions)
        document.addEventListener('livewire:navigating', function() {
            document.getElementById('page-loader').style.display = 'flex';
        });
        
        document.addEventListener('livewire:navigated', function() {
            document.getElementById('page-loader').style.display = 'none';
        });
    </script>
</body>
@push('scripts')
@endpush
</html>