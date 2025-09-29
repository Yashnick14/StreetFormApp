<header class="bg-black text-white shadow fixed top-0 left-0 right-0 w-full z-50">
    <div class="flex justify-between items-center h-16 px-4 sm:px-6 w-full">
        
        <!-- ✅ Logo on the far left -->
        <div class="flex items-center">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/images/Logo.png') }}" 
                     alt="Logo" class="h-8 sm:h-10 w-auto">
            </a>
        </div>

        <!-- ✅ Right section: hamburger (on mobile) + profile dropdown -->
        <div class="flex items-center space-x-3">
            <!-- Mobile Menu Toggle -->
            <button class="lg:hidden p-2 text-white focus:outline-none"
                    x-data
                    x-on:click="$dispatch('toggle-sidebar')">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Jetstream Profile Dropdown -->
            <div class="relative">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-white hover:text-gray-300 focus:outline-none">
                                <img class="h-8 w-8 rounded-full object-cover"
                                     src="{{ Auth::user()->profile_photo_url }}"
                                     alt="{{ Auth::user()->name }}" />
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Fallback: Initials -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-700 text-white font-semibold focus:outline-none">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endif
            </div>
        </div>
    </div>
</header>
