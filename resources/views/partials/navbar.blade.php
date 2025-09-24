<nav class="text-white px-6 py-4 flex items-center justify-between bg-black">
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
        <a href="{{ route('home') }}" class="hover:text-gray-400">All</a>
    </div>

    <!-- Right Side Icons -->
    <div class="flex items-center space-x-6 text-sm">
        <!-- Username -->
        @auth
            <span class="text-green-400">{{ auth()->user()->username }}</span>
        @endauth

        <!-- Account -->
        <a href="#" class="hover:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round">
                <circle cx="12" cy="8" r="5"/>
                <path d="M20 21a8 8 0 0 0-16 0"/>
            </svg>
        </a>

<!-- Cart -->
<a href="{{ route('cart.index') }}" class="hover:text-gray-400 relative">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
         stroke-linejoin="round">
        <circle cx="8" cy="21" r="1"/>
        <circle cx="19" cy="21" r="1"/>
        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
    </svg>
    
    @if(auth()->check())
        @php
            $cart = \App\Models\Cart::where('customer_id', auth()->id())->first();
            $cartCount = $cart ? \App\Models\CartItem::where('cart_id', $cart->_id)->sum('quantity') : 0;
        @endphp
        
        @if($cartCount > 0)
            <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center font-medium">
                {{ $cartCount }}
            </span>
        @else
            <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center font-medium" style="display: none;">
                0
            </span>
        @endif
    @endif
</a>

        <!-- Logout -->
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="hover:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="m16 17 5-5-5-5"/>
                        <path d="M21 12H9"/>
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    </svg>
                </button>
            </form>
        @endauth
    </div>
</nav>
