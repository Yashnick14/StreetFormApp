<x-app-layout>
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 px-6">
            
            <!-- LEFT: Product Images -->
            <div class="flex gap-6"
                 x-data="{ selectedImage: '{{ Storage::url($product->image) }}' }">

                <!-- Thumbnails -->
                <div class="flex flex-col gap-4 flex-shrink-0">
                    <img src="{{ Storage::url($product->image) }}"
                         @click="selectedImage = '{{ Storage::url($product->image) }}'"
                         :class="selectedImage === '{{ Storage::url($product->image) }}' ? 'ring-2 ring-black' : ''"
                         class="w-20 h-24 object-cover rounded-lg border cursor-pointer hover:ring-2 hover:ring-gray-400 transition">

                    @if($product->image2)
                        <img src="{{ Storage::url($product->image2) }}"
                             @click="selectedImage = '{{ Storage::url($product->image2) }}'"
                             :class="selectedImage === '{{ Storage::url($product->image2) }}' ? 'ring-2 ring-black' : ''"
                             class="w-20 h-24 object-cover rounded-lg border cursor-pointer hover:ring-2 hover:ring-gray-400 transition">
                    @endif

                    @if($product->image3)
                        <img src="{{ Storage::url($product->image3) }}"
                             @click="selectedImage = '{{ Storage::url($product->image3) }}'"
                             :class="selectedImage === '{{ Storage::url($product->image3) }}' ? 'ring-2 ring-black' : ''"
                             class="w-20 h-24 object-cover rounded-lg border cursor-pointer hover:ring-2 hover:ring-gray-400 transition">
                    @endif

                    @if($product->image4)
                        <img src="{{ Storage::url($product->image4) }}"
                             @click="selectedImage = '{{ Storage::url($product->image4) }}'"
                             :class="selectedImage === '{{ Storage::url($product->image4) }}' ? 'ring-1 ring-gray-700' : ''"
                             class="w-20 h-24 object-cover rounded-lg border cursor-pointer hover:ring-1 hover:ring-gray-400 transition">
                    @endif
                </div>

                <!-- Main Image -->
                <div class="flex-1">
                    <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden shadow-md border">
                        <img :src="selectedImage"
                             alt="{{ $product->name }}"
                             class="absolute inset-0 w-full h-full object-cover object-center transition-all duration-300">
                    </div>
                </div>
            </div>

            <!-- RIGHT: Product Info -->
            <div class="flex flex-col justify-start py-6"
                x-data="{
                    selectedSize: null, 
                    stock: null, 
                    quantity: 1,
                    isLoading: false,

                    async addToCart() {
                        if (!this.selectedSize) {
                            this.sendToast('error', 'Please select a size');
                            return;
                        }
                        
                        this.isLoading = true;
                        try {
                            const response = await fetch('/api/cart', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': 'Bearer ' + document.querySelector('meta[name=api-token]').content,
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                },
                                body: JSON.stringify({
                                    product_id: {{ $product->id }},
                                    quantity: this.quantity,
                                    size: this.selectedSize
                                })
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                this.sendToast('success', 'Product added to cart successfully!');
                                this.updateCartCount();
                            } else {
                                this.sendToast('error', data.message || 'Failed to add to cart');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            this.sendToast('error', 'Something went wrong. Please try again.');
                        } finally {
                            this.isLoading = false;
                        }
                    },

                    async addToWishlist() {
                        try {
                            const response = await fetch('/api/wishlist', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': 'Bearer ' + document.querySelector('meta[name=api-token]').content,
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                },
                                body: JSON.stringify({
                                    product_id: {{ $product->id }}
                                })
                            });

                            const data = await response.json();
                            if (data.success) {
                                this.sendToast('success', 'Product added to wishlist!');
                            } else {
                                this.sendToast('error', data.message || 'Failed to add to wishlist');
                            }
                        } catch (error) {
                            this.sendToast('error', 'Something went wrong while adding to wishlist.');
                        }
                    },

                    sendToast(type, message) {
                        window.dispatchEvent(new CustomEvent('toast', { 
                            detail: { type, message } 
                        }));
                    },

                    async updateCartCount() {
                        try {
                            const response = await fetch('/api/cart/count', {
                                headers: {
                                    'Authorization': 'Bearer ' + document.querySelector('meta[name=api-token]').content
                                }
                            });
                            const data = await response.json();
                            const cartCountElement = document.getElementById('cart-count');
                            if (cartCountElement) {
                                cartCountElement.textContent = data.count;
                                cartCountElement.style.display = data.count > 0 ? 'flex' : 'none';
                            }
                        } catch (error) {
                            console.log('Could not update cart count');
                        }
                    }
                }">

                <div class="space-y-6">

                    <!-- Product Title & Category -->
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                        <p class="text-sm text-gray-500 mt-1">{{ $product->type }}</p>
                    </div>

                    <!-- Price (NO borders now) -->
                    <div class="py-2">
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                    </div>

                    <!-- Sizes -->
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Choose a Size</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($product->stockquantity as $size => $qty)
                                @if($qty > 0)
                                    <button type="button"
                                        @click="selectedSize='{{ $size }}'; stock={{ $qty }}; quantity=1"
                                        :class="selectedSize === '{{ $size }}' 
                                                ? 'border-gray-900 bg-gray-100' 
                                                : 'border-gray-300 hover:border-gray-400'"
                                        class="w-12 h-12 rounded-md border flex items-center justify-center 
                                            text-sm font-medium text-gray-900 cursor-pointer transition">
                                        {{ $size }}
                                    </button>
                                @else
                                    <button type="button" disabled
                                        title="Out of Stock"
                                        class="w-12 h-12 border border-gray-200 text-gray-400 rounded-md 
                                            flex items-center text-sm font-medium justify-center opacity-60 cursor-not-allowed">
                                        {{ $size }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" x-model="quantity" min="1" :max="stock ?? 10"
                            class="w-24 px-3 py-2 border border-gray-300 rounded-md 
                                    focus:outline-none focus:ring-1 focus:ring-gray-200">
                        <template x-if="selectedSize && stock !== null">
                            <p class="text-xs text-gray-500 mt-1" x-text="'Available: ' + stock"></p>
                        </template>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <!-- Wishlist -->
                        <button type="button"
                                onclick="addToWishlist({{ $product->id }})"
                                class="w-full px-6 py-3 border-2 border-gray-900 bg-white text-red-500 hover:bg-gray-100
                                    font-semibold rounded-md transition flex items-center justify-center gap-2">
                            <span class="text-gray-900">Wishlist</span>
                        </button>

                        <!-- Add to Cart -->
                        <button type="button"
                                @click="addToCart()"
                                :disabled="isLoading || !selectedSize"
                                :class="isLoading ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full px-6 py-3 border-2 border-gray-900 bg-black hover:bg-gray-800 
                                    text-white font-semibold rounded-md transition">
                            <span x-show="!isLoading">Add to Cart</span>
                            <span x-show="isLoading">Adding...</span>
                        </button>
                    </div>

                    <!-- Description -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Product Description</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/customer/wishlist.js')
</x-app-layout>
