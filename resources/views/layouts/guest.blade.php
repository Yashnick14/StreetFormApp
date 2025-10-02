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

        <!-- Toast Container -->
        <div x-data="{ toasts: [] }"
             @toast.window="
                toasts.push({ id: Date.now(), type: $event.detail.type, message: $event.detail.message });
                setTimeout(() => { toasts.shift() }, 4000);
             "
             class="fixed right-5 top-20 z-50 space-y-3">

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
