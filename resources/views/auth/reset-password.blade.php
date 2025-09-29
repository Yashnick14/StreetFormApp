<x-guest-layout>
    <div class="relative min-h-screen flex items-center justify-center bg-cover bg-center"
         style="background-image: url('{{ asset('assets/images/SF-bg.jpg') }}');">
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
            
            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="block">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" 
                             class="block mt-1 w-full" 
                             type="email" 
                             name="email" 
                             :value="old('email', $request->email)" 
                             required autofocus autocomplete="username" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" 
                             class="block mt-1 w-full" 
                             type="password" 
                             name="password" 
                             required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" 
                             class="block mt-1 w-full" 
                             type="password" 
                             name="password_confirmation" 
                             required autocomplete="new-password" />
                </div>

                <!-- Button -->
                <div class="flex items-center justify-end mt-4">
                    <x-button>
                        {{ __('Reset Password') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
