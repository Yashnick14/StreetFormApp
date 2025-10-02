<x-guest-layout>
    <div class="relative min-h-screen flex items-center justify-center bg-cover bg-center"
         style="background-image: url('{{ asset('assets/images/SF-bg.jpg') }}');">

        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Auth Card (centered) -->
        <div class="relative w-full max-w-md">
            <x-authentication-card class="backdrop-blur-sm bg-white/95 shadow-lg rounded-lg p-6">
                <x-slot name="logo">
                    <!-- <x-authentication-card-logo /> -->
                </x-slot>

                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" />

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2 text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 border border-red-300 rounded p-2 text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Register</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Username -->
                    <div>
                        <x-label for="username" value="Username" />
                        <x-input id="username" class="block mt-1 w-full"
                                 type="text" name="username" :value="old('username')" required autofocus />
                    </div>

                    <!-- First Name -->
                    <div class="mt-4">
                        <x-label for="firstname" value="First Name" />
                        <x-input id="firstname" class="block mt-1 w-full"
                                 type="text" name="firstname" :value="old('firstname')" required />
                    </div>

                    <!-- Last Name -->
                    <div class="mt-4">
                        <x-label for="lastname" value="Last Name" />
                        <x-input id="lastname" class="block mt-1 w-full"
                                 type="text" name="lastname" :value="old('lastname')" required />
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <x-label for="email" value="Email" />
                        <x-input id="email" class="block mt-1 w-full"
                                 type="email" name="email" :value="old('email')" required />
                    </div>

                    <!-- Phone -->
                    <div class="mt-4">
                        <x-label for="phone" value="Phone Number" />
                        <x-input id="phone" class="block mt-1 w-full"
                                 type="text" name="phone" :value="old('phone')" required />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-label for="password" value="Password" />
                        <x-input id="password" class="block mt-1 w-full"
                                 type="password" name="password" required autocomplete="new-password" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-label for="password_confirmation" value="Confirm Password" />
                        <x-input id="password_confirmation" class="block mt-1 w-full"
                                 type="password" name="password_confirmation" required />
                    </div>

                    <!-- User Type (admin option only if no admin exists) -->
                    @if (!\App\Models\User::where('usertype', 'admin')->exists())
                        <div class="mt-4">
                            <x-label for="usertype" value="User Type" />
                            <select id="usertype" name="usertype"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                <option value="customer">Customer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="usertype" value="customer">
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-4">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900"
                           href="{{ route('login') }}">
                            Already registered?
                        </a>

                        <x-button class="ms-4 bg-black hover:bg-gray-800">
                            Register
                        </x-button>
                    </div>
                </form>
            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>
