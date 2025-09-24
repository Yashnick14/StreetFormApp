<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        @if(session('api_token'))
            <meta name="api-token" content="{{ session('api_token') }}">
            <script>
                window.API_TOKEN = "{{ session('api_token') }}";
            </script>
        @endif

        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        
        @stack('head')
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @include('partials.navbar')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <!-- Global Toast Notifications -->
        <div x-data="{ toasts: [] }"
            @toast.window="
                toasts.push({ id: Date.now(), type: $event.detail.type, message: $event.detail.message });
                setTimeout(() => { toasts.shift() }, 4000);
            "
            class="fixed right-5 top-20 z-50 space-y-3"> <!-- ⬅ Changed top-5 → top-20 -->

            <template x-for="toast in toasts" :key="toast.id">
                <div x-transition:enter="transform ease-out duration-300 transition"
                    x-transition:enter-start="translate-x-20 opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave="transform ease-in duration-300 transition"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="translate-x-20 opacity-0"
                    class="flex items-center justify-between px-4 py-3 w-80 rounded-lg shadow-lg border bg-white">

                    <!-- Icon -->
                    <div class="flex items-center">
                        <template x-if="toast.type === 'success'">
                            <svg class="h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <svg class="h-6 w-6 text-red-500 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </template>

                        <!-- Message -->
                        <span x-text="toast.message"
                            class="text-sm font-medium"
                            :class="toast.type === 'success' ? 'text-green-800' : 'text-red-800'"></span>
                    </div>

                    <!-- Close Button -->
                    <button @click="toasts = toasts.filter(t => t.id !== toast.id)"
                            class="ml-3 text-gray-400 hover:text-gray-600">&times;</button>
                </div>
            </template>
        </div>
    </body>
</html>
