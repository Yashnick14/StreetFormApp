<nav x-data="{ cartOpen: false }" class="text-white px-6 py-4 flex items-center justify-between bg-black">
    <!-- Logo -->
    <div class="flex items-center space-x-2">
        <a href="{{ route('home') }}">
            <img src="{{ asset('assets/images/Logo.png') }}" alt="StreetForm Logo" class="h-8 w-auto">
        </a>
    </div>

    <!-- Center Menu -->
    <div class="hidden md:flex space-x-8 text-sm font-medium">
        <a href="{{ route('men.products') }}" class="hover:text-gray-400">Men</a>
        <a href="{{ route('women.products') }}" class="hover:text-gray-400">Women</a>
        <a href="{{ route('all.products') }}" class="hover:text-gray-400">All</a>
    </div>

    <!-- Right Side Icons -->
    <div class="flex items-center space-x-8 text-sm">
        <!-- Account Dropdown -->
        <div class="relative flex items-center" x-data="{ open: false }">
            <button @click="open = !open" class="hover:text-gray-400 focus:outline-none">
                <i class="fa-regular fa-user text-l"></i> <!-- ⬅️ Bigger user icon -->
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-48 w-44 bg-white text-black rounded-md shadow-lg py-2 z-50 text-left">

                @auth
                    <!-- Greeting -->
                    <div class="px-4 py-2 text-sm font-medium text-gray-700 border-b">
                        Hi, {{ auth()->user()->username }}
                    </div>
                @endauth

                <!-- Links -->
                <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">My Orders</a>
                <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">My Wishlist</a>
                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Profile</a>
                
                @auth
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        <!-- Cart -->
        <button @click="cartOpen = true" class="hover:text-gray-400 relative flex items-center">
            <i class="bi bi-bag text-l"></i>
        </button>

        <!-- Sidebar Cart Component -->
        @livewire('customer.cart')
    </div>
</nav>
