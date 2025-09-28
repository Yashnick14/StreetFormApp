<x-app-layout>
    <div class="max-w-5xl mx-auto py-12 px-6">
        <!-- Page Title -->
        <h1 class="text-3xl font-bold mb-10 text-center">My Wishlist</h1>

        <!-- Wishlist Items -->
        <div id="wishlist-container" class="space-y-6">
            <!-- Wishlist items will be loaded here -->
        </div>

        <!-- Empty state -->
        <div id="wishlist-empty" class="hidden py-20 text-center text-gray-500">
            <p class="text-lg">Your wishlist is empty.</p>
        </div>

        <!-- 🔹 Row Template -->
        <template id="wishlist-card-template">
            <div class="flex items-center justify-between border-b pb-4">
                <!-- Product Info -->
                <div class="flex items-center space-x-4">
                    <img class="product-image w-20 h-20 object-cover rounded-md" alt="Product">

                    <div>
                        <h3 class="product-name font-semibold text-lg"></h3>
                        <p class="product-description text-sm text-gray-500"></p>
                        <p class="product-price text-sm text-gray-700 font-medium"></p>
                    </div>
                </div>

                <!-- Remove Button -->
                <div>
                    <button class="remove-btn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm">
                        Remove
                    </button>
                </div>
            </div>
        </template>
    </div>

    @vite('resources/js/customer/wishlist.js')
</x-app-layout>
