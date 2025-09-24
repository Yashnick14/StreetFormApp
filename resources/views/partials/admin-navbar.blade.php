<header class="bg-black text-white shadow w-full">
    <div class="flex justify-between items-center h-16 px-6">
        
        <!-- 👤 Custom Logo on Left -->
        <div class="flex items-center">
           <a href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('assets/images/Logo.png') }}" 
         alt="Logo" class="h-10 w-auto">
          </a>

        </div>

        <!-- ✅ Jetstream Profile Dropdown on Right -->
        <div class="flex items-center">
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div class="ml-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-white hover:text-gray-300 focus:outline-none">
                                <div>
                                    <img class="h-8 w-8 rounded-full object-cover"
                                         src="{{ Auth::user()->profile_photo_url }}"
                                         alt="{{ Auth::user()->name }}" />
                                </div>
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
                                    onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <!-- Fallback: Initials -->
                <div class="ml-3 relative">
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
                                    onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif
        </div>
    </div>
</header>
