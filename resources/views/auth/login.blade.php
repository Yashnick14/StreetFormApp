<x-guest-layout>
    <div class="relative min-h-screen flex items-center justify-center bg-cover bg-center"
         style="background-image: url('{{ asset('assets/images/SF-bg.jpg') }}');">

        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Auth Card -->
        <div class="relative w-full max-w-md">
            <x-authentication-card class="backdrop-blur-sm bg-white/95 shadow-lg rounded-lg p-6">
                <x-slot name="logo">
                    <!-- <x-authentication-card-logo /> -->
                </x-slot>

                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" />

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Jetstream status (e.g. password reset link sent) -->
                @if (session('status'))
                    <div class="mb-4 p-3 rounded-lg bg-blue-100 text-blue-700 text-sm font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Login</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-label for="email" value="Email" />
                        <x-input id="email" class="block mt-1 w-full"
                                 type="email" name="email" :value="old('email')"
                                 required autofocus autocomplete="username" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-label for="password" value="Password" />
                        <x-input id="password" class="block mt-1 w-full"
                                 type="password" name="password"
                                 required autocomplete="current-password" />
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900"
                               href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        @endif

                        <x-button class="bg-black hover:bg-gray-800">
                            {{ __('Login') }}
                        </x-button>
                    </div>
                </form>

                <!-- Register Link -->
                <p class="mt-4 text-center text-sm">
                    Don’t have an account?
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Register</a>
                </p>
            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>

{{-- Toast triggers for session messages --}}
@if (session('error'))
    <script>
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { type: 'error', message: "{{ session('error') }}" }
        }));
    </script>
@endif

@if (session('success'))
    <script>
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { type: 'success', message: "{{ session('success') }}" }
        }));
    </script>
@endif

@if (session('status'))
    <script>
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { type: 'success', message: "{{ session('status') }}" }
        }));
    </script>
@endif
