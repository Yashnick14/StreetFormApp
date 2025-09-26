<x-app-layout>
    <div class="max-w-5xl mx-auto py-12 px-6 text-center">
        <!-- Centered Heading -->
        <h1 class="text-2xl font-bold mb-6">My Wishlist</h1>

        <!-- Wishlist grid -->
        <div id="wishlist-container" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 justify-center">
            <!-- Wishlist products load here -->
        </div>

        <!-- Empty state -->
        <div id="wishlist-empty" class="hidden py-12 text-gray-500">
            <p>Your wishlist is empty.</p>
        </div>

        <!-- 🔹 Card template -->
        <template id="wishlist-card-template">
            <div class="w-full h-full">
                <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition flex flex-col h-full">
                    <a class="product-link" href="#">
                        <!-- Image -->
                        <img class="product-image w-full h-72 object-cover" alt="">
                    </a>

                    <!-- Info -->
                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="product-name text-sm font-semibold text-gray-900 truncate"></h3>
                        <p class="product-description text-sm text-gray-500 mt-1 flex-1"></p>
                        <p class="product-price mt-2 text-lg font-bold text-gray-900"></p>

                        <!-- Remove button (added below original styling) -->
                        <button class="remove-btn mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    @vite('resources/js/customer/wishlist.js')
</x-app-layout>
