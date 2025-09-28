<div>
    <div 
        x-show="cartOpen"
        x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in-out duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-16 right-0 w-full sm:w-96 h-[calc(100%-4rem)] bg-white shadow-xl z-50 flex flex-col"
        style="display: none;"
    >
        <!-- ✅ Always visible header -->
        <div class="px-6 py-3 bg-white border-b sticky top-0 z-20">
            <h2 class="text-lg font-bold text-gray-900">My Cart</h2>
        </div>

        <!-- Cart Items -->
        <div class="p-6 overflow-y-auto flex-1">
            @if(!$cart || count($items) === 0)
                <p class="text-gray-600 text-center mt-10">Your cart is empty.</p>
            @else
                <div class="space-y-6">
                    @foreach($items as $item)
                        @php
                            $availableStock = $item->product && $item->size
                                ? ($item->product->stockquantity[$item->size] ?? 0)
                                : 0;
                        @endphp

                        <div class="flex items-center justify-between border-b pb-4">
                            <!-- Product Info -->
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item->product && $item->product->image 
                                            ? Storage::url($item->product->image) 
                                            : asset('assets/images/default.jpg') }}"
                                     class="w-20 h-20 object-cover rounded-md" alt="Product">

                                <div>
                                    <h3 class="font-semibold text-sm">{{ $item->product->name ?? 'Product not available' }}</h3>
                                    <p class="text-xs text-gray-500">Size: {{ $item->size ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Price: ${{ number_format($item->unitprice, 2) }}</p>
                                </div>
                            </div>

                            <!-- Quantity + Actions -->
                            <div class="flex items-center space-x-4">
                                <input 
                                    type="number"
                                    min="1"
                                    max="{{ $availableStock }}"
                                    value="{{ $item->quantity }}"
                                    @if($availableStock === 0) disabled @endif
                                    class="w-14 h-8 border border-gray-300 rounded-md text-gray-900 text-center text-sm focus:ring-0 focus:border-gray-400"
                                    wire:model.lazy="items.{{ $loop->index }}.quantity"
                                    wire:change="updateQuantity('{{ $item->_id }}', $event.target.value)"
                                >
                                <button wire:click="removeItem('{{ $item->_id }}')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Footer (Order Summary) -->
        @if($cart && count($items) > 0)
            <div class="bg-gray-50 border-t p-6">
                @php
                    $total = collect($items)->sum(fn($i) => $i->quantity * $i->unitprice);
                @endphp
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Sub Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between text-base font-semibold">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('checkout') }}"
                   class="mt-6 block w-full bg-black text-white py-3 rounded-lg font-semibold text-center hover:bg-gray-800">
                    Checkout
                </a>
            </div>
        @endif
    </div>

    <!-- Overlay -->
    <div 
        x-show="cartOpen" 
        @click="cartOpen = false" 
        class="fixed inset-0 bg-black bg-opacity-50 z-40"
        style="display: none;">
    </div>
</div>
