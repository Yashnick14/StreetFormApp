<div>
<div class="py-12 px-6 md:px-12 relative">
    <!-- Header -->
    <div class="text-center">
        <h2 class="text-3xl md:text-4xl font-bold uppercase tracking-wide text-black">
            Men’s Collection
        </h2>
        <p class="text-sm text-slate-700 mt-2 max-w-lg mx-auto">
            Discover the latest streetwear for Men.
        </p>
    </div>

    <!-- Filter Button (page-level top right corner) -->
    <button class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition 
                   absolute top-3 right-12"
            x-data
            x-on:click="$dispatch('open-filter')">
        Filter
    </button>

    <!-- Product Grid -->
    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 
                gap-8 max-w-6xl mx-auto">
        @forelse($products as $product)
            <x-product-card :product="$product" />
        @empty
            <p class="col-span-4 text-center text-gray-500 mt-20">
                No products found.
            </p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="max-w-6xl mx-auto mt-8">
        {{ $products->onEachSide(1)->links() }}
    </div>
</div>

<!-- ✅ Filter Sidebar Partial -->
@include('partials.filter-sidebar')

</div>