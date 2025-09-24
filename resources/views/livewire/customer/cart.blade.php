<div class="max-w-5xl mx-auto py-10">
    <h2 class="text-3xl font-bold mb-6">Your Cart</h2>

    @if(!$cart || count($items) === 0)
        <p class="text-gray-600">Your cart is empty.</p>
    @else
        <div class="space-y-6">
            @foreach($items as $item)
                <div class="flex items-center justify-between border-b pb-4">
                    <!-- Product Info -->
                    <div class="flex items-center space-x-4">
                        <img src="{{ $item->product && $item->product->image 
                                    ? Storage::url($item->product->image) 
                                    : asset('assets/images/default.jpg') }}"
                             class="w-20 h-20 object-cover rounded-md" alt="Product">

                        <div>
                            <h3 class="font-semibold text-lg">
                                {{ $item->product->name ?? 'Product not available' }}
                            </h3>
                            <p class="text-sm text-gray-500">Size: {{ $item->size ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">
                                Price: ${{ number_format($item->unitprice, 2) }}
                            </p>
                        </div>
                    </div>

                    <!-- Quantity + Actions -->
                    <div class="flex items-center space-x-4">
                        @php
                            $availableStock = $item->product && $item->size
                                ? ($item->product->stockquantity[$item->size] ?? 0)
                                : 0;
                        @endphp

                        <input type="number" 
                            min="1" 
                            max="{{ $availableStock }}"
                            class="w-14 h-8 border border-gray-300 rounded-md text-center text-sm focus:ring-0 focus:border-gray-400"
                            wire:change="updateQuantity('{{ $item->_id }}', $event.target.value)"
                            value="{{ $item->quantity }}">

                        <button wire:click="removeItem('{{ $item->_id }}')"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm">
                            Remove
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div class="mt-8 p-6 bg-gray-100 rounded-lg">
            <h3 class="text-xl font-bold mb-4">Cart Summary</h3>
            @php
                $total = collect($items)->sum(fn($i) => $i->quantity * $i->unitprice);
            @endphp
            <p class="text-lg font-semibold">Total: ${{ number_format($total, 2) }}</p>
            
            <a href="{{ route('checkout') }}"
            class="mt-4 block w-full bg-black text-white py-3 rounded-lg font-semibold text-center hover:bg-gray-800">
                Proceed to Checkout
            </a>
        </div>
    @endif
</div>
