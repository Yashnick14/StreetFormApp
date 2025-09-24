<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- ORDER SUMMARY -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4">ORDER SUMMARY</h2>

            <div class="border-b pb-4 mb-4">
                @forelse($items as $item)
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $item->product && $item->product->image 
                                        ? asset('storage/'.$item->product->image) 
                                        : asset('assets/images/default.jpg') }}"
                                 class="w-16 h-16 object-cover rounded-md" alt="Product">
                            <div>
                                <p class="font-semibold">{{ $item->product->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">Size: {{ $item->size ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm">Qty: {{ $item->quantity }}</p>
                            <p class="font-semibold">${{ number_format($item->unitprice * $item->quantity, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No items in cart.</p>
                @endforelse
            </div>

            <div class="flex justify-between font-bold text-lg">
                <span>Total</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>
        </div>

        <!-- CHECKOUT FORM -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form wire:submit.prevent="placeOrder" class="space-y-6">
                <h3 class="font-bold mb-3">DELIVERY DETAILS</h3>

                <input type="text" wire:model="firstname" placeholder="First Name" class="w-full mb-3 rounded-md border-gray-300">
                <input type="text" wire:model="lastname" placeholder="Last Name" class="w-full mb-3 rounded-md border-gray-300">
                <input type="email" wire:model="email" placeholder="Email" class="w-full mb-3 rounded-md border-gray-300">
                <input type="text" wire:model="phone" placeholder="Phone Number" class="w-full mb-3 rounded-md border-gray-300">
                <input type="text" wire:model="house_number" placeholder="House Number" class="w-full mb-3 rounded-md border-gray-300">
                <input type="text" wire:model="street" placeholder="Street" class="w-full mb-3 rounded-md border-gray-300">
                <input type="text" wire:model="city" placeholder="City" class="w-full mb-3 rounded-md border-gray-300">
                <input type="text" wire:model="postal_code" placeholder="Postal Code" class="w-full mb-3 rounded-md border-gray-300">

                <div>
                    <h3 class="font-bold mb-3">PAYMENT METHOD</h3>
                    <label class="flex items-center mb-2">
                        <input type="radio" wire:model="payment_method" value="cod" class="text-black border-gray-300">
                        <span class="ml-2">Cash on Delivery</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model="payment_method" value="card" class="text-black border-gray-300">
                        <span class="ml-2">Card Payment</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                    PLACE ORDER
                </button>
            </form>
        </div>
    </div>

    <!-- CONFIRMATION MODAL -->
    <div x-data="{ open: @entangle('showConfirmModal') }">
        <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
                <h2 class="text-xl font-bold mb-4">Confirm Your Order</h2>

                <div class="mb-3">
                    <p><strong>Name:</strong> {{ $firstname }} {{ $lastname }}</p>
                    <p><strong>Email:</strong> {{ $email }}</p>
                    <p><strong>Phone:</strong> {{ $phone }}</p>
                    <p><strong>Address:</strong> {{ $house_number }}, {{ $street }}, {{ $city }} - {{ $postal_code }}</p>
                </div>

                <!-- Order Items in Modal -->
                <div class="mb-3">
                    <h4 class="font-semibold">Items:</h4>
                    <ul class="list-disc list-inside text-sm text-gray-700">
                        @foreach($items as $item)
                            <li>{{ $item->product->name ?? 'Unknown' }} (x{{ $item->quantity }})</li>
                        @endforeach
                    </ul>
                </div>

                <p class="mt-2 font-bold text-lg">Total: ${{ number_format($total, 2) }}</p>

                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="open=false" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                    <button wire:click="saveOrder" class="px-4 py-2 bg-black text-white rounded">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>
