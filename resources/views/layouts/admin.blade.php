<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @if(session('api_token'))
    <meta name="api-token" content="{{ session('api_token') }}">
    <script>
        window.API_TOKEN = "{{ session('api_token') }}";
    </script>
    @endif


    <title>@yield('title', config('app.name', 'StreetForm'))</title>

    {{-- Vite compiled assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">

        <!-- Top Navbar -->
        @include('partials.admin-navbar')

        <div class="flex flex-1">
            <!-- Sidebar -->
            @include('partials.admin-sidebar')

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <h1 class="text-2xl font-bold mb-6">@yield('page-title')</h1>

                {{-- For Blade views --}}
                @yield('content')

                {{-- For Livewire components --}}
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    @livewireScripts

    @stack('scripts')

</body>
</html>
