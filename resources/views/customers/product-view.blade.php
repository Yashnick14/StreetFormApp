<x-app-layout>
    <div class="bg-white py-8 sm:py-12">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 px-4 sm:px-6">
            
            <!-- LEFT: Product Images -->
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6"
                 x-data="{ selectedImage: '{{ Storage::url($product->image) }}' }">

                <!-- Thumbnails -->
                <div class="flex sm:flex-col gap-3 sm:gap-4 flex-shrink-0 order-2 sm:order-1 mt-4 sm:mt-0">
                    <img src="{{ Storage::url($product->image) }}"
                         @click="selectedImage = '{{ Storage::url($product->image) }}'"
                         :class="selectedImage === '{{ Storage::url($product->image) }}' ? 'ring-2 ring-black' : ''"
                         class="w-16 h-20 sm:w-20 sm:h-24 object-cover rounded-lg border cursor-pointer hover:ring-2 hover:ring-gray-400 transition">

                    @if($product->image2)
                        <img src="{{ Storage::url($product->image2) }}"
                             @click="selectedImage = '{{ Storage::url($product->image2) }}'"
                             :class="selectedImage === '{{ Storage::url($product->image2) }}' ? 'ring-2 ring-black' : ''"
                             class="w-16 h-20 sm:w-20 sm:h-24 object-cover rounded-lg border cursor-pointer hover:ring-2 hover:ring-gray-400 transition">
                    @endif

                    @if($product->image3)
                        <img src="{{ Storage::url($product->image3) }}"
                             @click="selectedImage = '{{ Storage::url($product->image3) }}'"
                             :class="selectedImage === '{{ Storage::url($product->image3) }}' ? 'ring-2 ring-black' : ''"
                             class="w-16 h-20 sm:w-20 sm:h-24 object-cover rounded-lg border cursor-pointer hover:ring-2 hover:ring-gray-400 transition">
                    @endif

                    @if($product->image4)
                        <img src="{{ Storage::url($product->image4) }}"
                             @click="selectedImage = '{{ Storage::url($product->image4) }}'"
                             :class="selectedImage === '{{ Storage::url($product->image4) }}' ? 'ring-2 ring-black' : ''"
                             class="w-16 h-20 sm:w-20 sm:h-24 object-cover rounded-lg border cursor-pointer hover:ring-2 hover:ring-gray-400 transition">
                    @endif
                </div>

                <!-- Main Image -->
                <div class="flex-1 order-1 sm:order-2">
                    <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden shadow-md border">
                        <img :src="selectedImage"
                             alt="{{ $product->name }}"
                             class="absolute inset-0 w-full h-full object-cover object-center transition-all duration-300">
                    </div>
                </div>
            </div>

            <!-- RIGHT: Product Info -->
            <div class="flex flex-col justify-start py-6 px-1 sm:px-0">
                <div class="space-y-6">
                    <!-- Product Title & Category -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $product->type }}</p>
                    </div>

                    <!-- Price -->
                    <div class="py-2">
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                    </div>

                    <!-- Sizes -->
                    <div>
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-2">Choose a Size</h3>
                        <div class="flex flex-wrap gap-2 sm:gap-3">
                            @foreach ($product->stockquantity as $size => $qty)
                                @if($qty > 0)
                                    <button type="button"
                                        class="size-btn w-10 h-10 sm:w-12 sm:h-12 rounded-md border flex items-center justify-center 
                                            text-xs sm:text-sm font-medium text-gray-900 cursor-pointer transition"
                                        data-size="{{ $size }}"
                                        data-stock="{{ $qty }}">
                                        {{ $size }}
                                    </button>
                                @else
                                    <button type="button" disabled
                                        title="Out of Stock"
                                        class="w-10 h-10 sm:w-12 sm:h-12 border border-gray-200 text-gray-400 rounded-md 
                                            flex items-center justify-center text-xs sm:text-sm font-medium opacity-60 cursor-not-allowed">
                                        {{ $size }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" id="quantity-input" min="1" value="1"
                            class="w-20 sm:w-24 px-2 sm:px-3 py-2 border border-gray-300 rounded-md 
                                    focus:outline-none focus:ring-1 focus:ring-gray-200 text-sm">
                        <p id="stock-info" class="text-xs text-gray-500 mt-1 hidden"></p>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <!-- Wishlist -->
                        <button type="button"
                                class="wishlist-btn w-full px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-900 bg-white text-red-500 hover:bg-gray-100
                                    font-semibold rounded-md transition flex items-center justify-center gap-2 text-sm sm:text-base"
                                data-product-id="{{ $product->id }}">
                            <span class="text-gray-900">Wishlist</span>
                        </button>

                        <!-- Add to Cart -->
                        <button type="button"
                                class="cart-btn w-full px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-900 bg-black hover:bg-gray-800 
                                    text-white font-semibold rounded-md transition text-sm sm:text-base"
                                data-product-id="{{ $product->id }}">
                            Add to Cart
                        </button>
                    </div>

                    <!-- Description -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-2">Product Description</h3>
                        <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/customer/wishlist.js')
    @vite('resources/js/customer/cart.js')
</x-app-layout>
