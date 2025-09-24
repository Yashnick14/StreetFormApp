<x-app-layout>
    <div class="py-12 px-6 md:px-12">
        <h2 class="text-3xl md:text-4xl font-bold text-center uppercase tracking-wide text-black">
            Men’s Collection
        </h2>
        <p class="text-sm text-slate-700 text-center mt-2 max-w-lg mx-auto">
            Discover the latest streetwear for men.
        </p>
        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 
                    gap-8 max-w-6xl mx-auto">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-4 text-center text-gray-500">No products found.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
