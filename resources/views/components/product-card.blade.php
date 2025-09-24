@props(['product'])

<div class="w-full h-full">
    <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition flex flex-col h-full">
        <a href="{{ route('products.view', $product->id) }}">
            <!-- Image -->
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-72 object-cover">
            @else
                <div class="w-full h-72 flex items-center justify-center bg-gray-100 text-gray-400">
                    No Image
                </div>
            @endif
        </a>

        <!-- Info -->
        <div class="p-4 flex flex-col flex-1">
            <h3 class="text-sm font-semibold text-gray-900 truncate">
                {{ $product->name }}
            </h3>
            <p class="text-sm text-gray-500 mt-1 flex-1">
                {{ Str::limit($product->description, 50) }}
            </p>
            <p class="mt-2 text-lg font-bold text-gray-900">
                ${{ number_format($product->price, 2) }}
            </p>
        </div>
    </div>
</div>
