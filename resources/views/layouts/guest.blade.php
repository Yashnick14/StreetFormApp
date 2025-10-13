<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <title>{{ config('app.name', 'StreetForm') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts


        <!-- Auto trigger session messages as toast -->
        @if (session('error'))
            <script>
                window.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { type: 'error', message: "{{ session('error') }}" }
                    }));
                });
            </script>
        @endif

        @if (session('success'))
            <script>
                window.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { type: 'success', message: "{{ session('success') }}" }
                    }));
                });
            </script>
        @endif

        @if (session('status'))
            <script>
                window.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { type: 'success', message: "{{ session('status') }}" }
                    }));
                });
            </script>
        @endif
    </body>
</html>
