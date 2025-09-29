<nav x-data="{ cartOpen: false, mobileMenuOpen: false }" class="text-white px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between bg-black relative">
    <!-- Logo -->
    <div class="flex items-center space-x-2">
        <a href="{{ route('home') }}">
            <img src="{{ asset('assets/images/Logo.png') }}" alt="StreetForm Logo" class="h-7 sm:h-8 w-auto">
        </a>
    </div>

    <!-- Desktop Menu -->
    <div class="hidden md:flex space-x-6 lg:space-x-8 text-sm font-medium">
        <a href="{{ route('men.products') }}" class="hover:text-gray-400">Men</a>
        <a href="{{ route('women.products') }}" class="hover:text-gray-400">Women</a>
        <a href="{{ route('all.products') }}" class="hover:text-gray-400">All</a>
    </div>

    <!-- Right Side Icons -->
    <div class="flex items-center space-x-4 sm:space-x-6 md:space-x-8 text-sm">
        <!-- Mobile Hamburger (only <768px) -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden focus:outline-none">
            <i class="bi bi-list text-2xl"></i>
        </button>

        <!-- Account Dropdown -->
        <div class="relative flex items-center" x-data="{ open: false }">
            <button @click="open = !open" class="hover:text-gray-400 focus:outline-none">
                <i class="fa-regular fa-user text-base sm:text-base"></i>
            </button>

            <!-- Dropdown -->
            <div x-show="open" 
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-32 w-40 sm:w-44 bg-white text-black rounded-md shadow-lg py-2 z-50 text-left">

                @auth
                    <div class="px-4 py-2 text-sm font-medium text-gray-700 border-b">
                        Hi, {{ auth()->user()->username }}
                    </div>
                @endauth

                <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">My Orders</a>
                <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">My Wishlist</a>

                @auth
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
            <i class="bi bi-bag text-base sm:text-base"></i>
        </button>

        <!-- Sidebar Cart -->
        @livewire('customer.cart')
    </div>

    <!-- Mobile Menu (only visible <768px) -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition
        @click.away="mobileMenuOpen = false"
        class="absolute top-full left-0 w-full bg-black text-white flex flex-col space-y-4 px-6 py-4 md:hidden z-40">

        <a href="{{ route('men.products') }}" class="hover:text-gray-400">Men</a>
        <a href="{{ route('women.products') }}" class="hover:text-gray-400">Women</a>
        <a href="{{ route('all.products') }}" class="hover:text-gray-400">All</a>
    </div>
</nav>
