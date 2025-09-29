<div>
    <div class="py-10 sm:py-12 px-4 sm:px-6 md:px-12 relative">
        <!-- Header -->
        <div class="text-center relative">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold uppercase tracking-wide text-black">
                All Products
            </h2>
            <p class="text-xs sm:text-sm md:text-base text-slate-700 mt-2 max-w-lg mx-auto">
                Explore the complete collection.
            </p>

            <!-- Filter Button -->
            <button class="px-3 sm:px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition 
                           absolute right-4 sm:right-0 -top-8 sm:-top-10 text-sm sm:text-base"
                    x-data
                    x-on:click="$dispatch('open-filter')">
                Filter
            </button>
        </div>

        <!-- Product Grid -->
        <div class="mt-8 sm:mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 
                    gap-6 sm:gap-8 max-w-6xl mx-auto">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 text-center text-gray-500 mt-10 sm:mt-20 text-sm sm:text-base">
                    No products found.
                </p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="max-w-6xl mx-auto mt-6 sm:mt-8 px-2 sm:px-0">
            {{ $products->onEachSide(1)->links() }}
        </div>
    </div>

    <!-- Filter Sidebar -->
    @include('partials.filter-sidebar')
</div>
