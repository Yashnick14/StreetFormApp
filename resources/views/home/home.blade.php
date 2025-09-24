<x-app-layout>
    <div class="bg-white">

        <!-- Hero Section -->
        <section class="relative h-screen flex items-center justify-center text-center bg-cover"
            style="background-image: url('{{ asset('assets/images/bg4.jpg') }}'); background-position: center top 20%;">
            <!-- Darker premium overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-10"></div>

            <div class="relative z-10 px-6">
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-wide uppercase text-white drop-shadow-lg">
                    Premium Streetwear For <span class="text-white">Men</span> & <span class="text-white">Women</span>
                </h1>
                <p class="mt-6 text-base md:text-lg text-gray-200 max-w-2xl mx-auto leading-relaxed">
                    Bold styles, urban vibes, and timeless pieces for the culture. Step into the world of premium streetwear.
                </p>

                <!-- Premium Buttons -->
                <div class="mt-10 flex justify-center space-x-6">
                    <a href="#"
                        class="bg-black text-white px-8 py-3 rounded-md text-lg font-semibold shadow-lg border border-white 
                        hover:bg-gray-900 hover:scale-105 transition transform duration-300">
                        Shop Men
                    </a>

                    <a href="#"
                        class="bg-white text-black px-8 py-3 rounded-md text-lg font-semibold shadow-lg border border-black 
                        hover:bg-gray-100 hover:scale-105 transition transform duration-300">
                        Shop Women
                    </a>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="py-16 px-6 md:px-12">
            <h2 class="text-3xl md:text-4xl font-bold text-center uppercase tracking-wide text-black">Explore Categories</h2>
            <p class="text-sm text-slate-700 text-center mt-2 max-w-lg mx-auto">
                Discover our premium range of styles crafted with comfort, durability, and streetwear vibes.
            </p>

            <!-- Flex-based expanding cards -->
            <div class="flex items-stretch gap-6 h-[400px] w-full max-w-6xl mt-10 mx-auto">

                <!-- Hoodies -->
                <div
                    class="relative group flex-[1] transition-all duration-500 ease-in-out hover:flex-[3] rounded-xl overflow-hidden">
                    <img class="h-full w-full object-cover object-center"
                        src="{{ asset('assets/images/Hoodie3.jpg') }}" alt="Hoodies">
                    <div
                        class="absolute inset-0 flex flex-col justify-end p-8 text-white bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <h1 class="text-2xl font-bold">Hoodies</h1>
                        <p class="text-sm">Cozy and stylish layers for any season.</p>
                        <a href="#"
                            class="mt-3 inline-block bg-white text-black px-4 py-2 rounded-md font-semibold hover:bg-gray-200">Shop
                            Now</a>
                    </div>
                </div>

                <!-- T-Shirts -->
                <div
                    class="relative group flex-[1] transition-all duration-500 ease-in-out hover:flex-[3] rounded-xl overflow-hidden">
                    <img class="h-full w-full object-cover object-center"
                        src="{{ asset('assets/images/Tshirts.jpg') }}" alt="T-Shirts">
                    <div
                        class="absolute inset-0 flex flex-col justify-end p-8 text-white bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <h1 class="text-2xl font-bold">T-Shirts</h1>
                        <p class="text-sm">Essential tees for everyday comfort and style.</p>
                        <a href="#"
                            class="mt-3 inline-block bg-white text-black px-4 py-2 rounded-md font-semibold hover:bg-gray-200">Shop
                            Now</a>
                    </div>
                </div>

                <!-- Sweatshirts -->
                <div
                    class="relative group flex-[1] transition-all duration-500 ease-in-out hover:flex-[3] rounded-xl overflow-hidden">
                    <img class="h-full w-full object-cover object-center"
                        src="{{ asset('assets/images/Sweatshirt.png') }}" alt="Sweatshirts">
                    <div
                        class="absolute inset-0 flex flex-col justify-end p-8 text-white bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <h1 class="text-2xl font-bold">Sweatshirts</h1>
                        <p class="text-sm">Classic fits with modern streetwear vibes.</p>
                        <a href="#"
                            class="mt-3 inline-block bg-white text-black px-4 py-2 rounded-md font-semibold hover:bg-gray-200">Shop
                            Now</a>
                    </div>
                </div>

                <!-- Cargo Pants -->
                <div
                    class="relative group flex-[1] transition-all duration-500 ease-in-out hover:flex-[3] rounded-xl overflow-hidden">
                    <img class="h-full w-full object-cover object-center"
                        src="{{ asset('assets/images/Cargo.jpg') }}" alt="Cargo Pants">
                    <div
                        class="absolute inset-0 flex flex-col justify-end p-8 text-white bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <h1 class="text-2xl font-bold">Cargo Pants</h1>
                        <p class="text-sm">Utility meets comfort for everyday wear.</p>
                        <a href="#"
                            class="mt-3 inline-block bg-white text-black px-4 py-2 rounded-md font-semibold hover:bg-gray-200">Shop
                            Now</a>
                    </div>
                </div>

                <!-- Jackets -->
                <div
                    class="relative group flex-[1] transition-all duration-500 ease-in-out hover:flex-[3] rounded-xl overflow-hidden">
                    <img class="h-full w-full object-cover object-center"
                        src="{{ asset('assets/images/Jacket2.jpg') }}" alt="Jackets">
                    <div
                        class="absolute inset-0 flex flex-col justify-end p-8 text-white bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <h1 class="text-2xl font-bold">Jackets</h1>
                        <p class="text-sm">Layer up with premium jackets for every vibe.</p>
                        <a href="#"
                            class="mt-3 inline-block bg-white text-black px-4 py-2 rounded-md font-semibold hover:bg-gray-200">Shop
                            Now</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="py-16 px-6 md:px-12">
            <h2 class="text-3xl md:text-4xl font-bold text-center uppercase tracking-wide text-black">
                Featured Products
            </h2>
            <p class="text-sm text-slate-700 text-center mt-2 max-w-lg mx-auto">
                Handpicked styles from our latest collection.
            </p>

        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 
                    gap-8 max-w-6xl mx-auto">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-4 text-center text-gray-500">No products found.</p>
            @endforelse
        </div>
        </section>


 <!-- Marquee Section (replacing Newsletter) -->
        <section class="py-16 px-6 md:px-12">
            <h2 class="text-3xl md:text-4xl font-bold text-center uppercase tracking-wide text-black mb-10">
                Trending Now
            </h2>

           @php
            $cards = [
                ['title' => 'Supreme', 'image' => asset('assets/images/Supreme2.jpg')],
                ['title' => 'Bathing Ape', 'image' => asset('assets/images/Bape2.jpg')],
                ['title' => 'Converse', 'image' => asset('assets/images/Converse2.jpg')],
                ['title' => 'Stussy', 'image' => asset('assets/images/Stussy.jpg')],
            ];
           @endphp


            <x-marquee :cards="$cards" />
        </section>

        <!-- Footer -->
        <footer class="bg-black py-8 text-center text-gray-400 text-sm">
            © {{ date('Y') }} StreetStyle. All rights reserved.
        </footer>
    </div>
</x-app-layout>
