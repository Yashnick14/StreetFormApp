<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6">
        <!-- Page Title -->
        <h2 class="text-3xl font-bold mb-2 text-center">Order History</h2>
        <p class="text-gray-600 mb-6 text-center">View and manage your past orders</p>

        @forelse($orders as $order)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <!-- Order Header -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-semibold text-lg flex items-center gap-2">
                            Order #{{ $order->order_number ?? $order->_id }}
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded 
                                {{ in_array(strtolower($order->orderstatus), ['paid','completed','delivered'])
                                    ? 'bg-green-100 text-green-700'
                                    : (strtolower($order->orderstatus) === 'cancelled'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($order->orderstatus) }}
                            </span>
                        </h3>
                        <p class="text-sm text-gray-500">Placed on {{ $order->orderdate }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold">${{ number_format($order->totalprice, 2) }}</p>
                        <p class="text-sm text-gray-500">{{ ($order->itemsData ?? collect())->count() }} items</p>
                    </div>
                </div>

                <hr class="mb-4 border-gray-200">

                <!-- Items Preview -->
                <div class="flex gap-6 overflow-x-auto pb-4">
                    @foreach(($order->itemsData ?? collect()) as $item)
                        @php
                            $product = $order->productsData[$item->product_id] ?? null;
                        @endphp
                        <div class="flex flex-col items-center min-w-[120px]">
                            @if($product && $product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name ?? 'Product' }}"
                                     class="w-16 h-16 object-cover rounded-lg border border-gray-200 mb-2">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <p class="text-sm font-medium truncate w-24 text-center">{{ $product->name ?? 'Deleted Product' }}</p>
                            <p class="text-xs text-gray-500">Qty: {{ $item->orderquantity }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Actions -->
                <div class="flex gap-3 mt-4">
                    <a href="{{ route('orders.tracking', $order->_id) }}" 
                    class="px-4 py-2 text-sm border border-gray-300 rounded-md flex items-center gap-2 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Details
                    </a>

                    @if(in_array(strtolower($order->orderstatus), ['pending', 'paid']))
                        <button onclick="cancelOrder('{{ $order->_id }}')"
                            class="px-4 py-2 text-sm border border-red-500 text-red-600 rounded-md flex items-center gap-2 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-gray-900 mb-1">No orders yet</h3>
                <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('products.view', 1) }}" 
                   class="inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Start Shopping
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="flex justify-end mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    @vite('resources/js/customer/order.js')
</x-app-layout>
