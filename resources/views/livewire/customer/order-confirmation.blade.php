<div class="bg-gray-100 min-h-screen flex items-center justify-center p-3 sm:p-6">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden w-full max-w-6xl">

        <!-- Header -->
        <div class="bg-indigo-600 px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="text-base sm:text-lg font-semibold text-white text-center sm:text-left">
                Order Confirmation
            </h2>
            <span class="bg-white/20 text-white text-[10px] sm:text-xs font-medium px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-center">
                {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Card Payment' }}
            </span>
        </div>

        <!-- Body -->
        <div class="p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            
            <!-- Left: Order + Shipping -->
            <div class="space-y-6">
                <!-- Order Info -->
                <div class="bg-gray-50 rounded-xl p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base font-medium text-slate-900 mb-3 sm:mb-4">Order Details</h3>
                    <div class="grid grid-cols-1 xs:grid-cols-2 gap-4">
                        <div>
                            <p class="text-slate-500 text-xs sm:text-sm font-medium">Order Number</p>
                            <p class="text-slate-900 text-xs sm:text-sm font-medium mt-1 sm:mt-2">{{ $order->_id }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs sm:text-sm font-medium">Date</p>
                            <p class="text-slate-900 text-xs sm:text-sm font-medium mt-1 sm:mt-2">
                                {{ \Carbon\Carbon::parse($order->orderdate)->format('M d, Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs sm:text-sm font-medium">Total</p>
                            <p class="text-indigo-700 text-xs sm:text-sm font-semibold mt-1 sm:mt-2">
                                ${{ number_format($order->totalprice, 2) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="bg-gray-50 rounded-xl p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base font-medium text-slate-900 mb-3 sm:mb-4">Shipping Information</h3>
                    <div class="grid sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <p class="text-slate-500 text-xs sm:text-sm font-medium">Customer</p>
                            <p class="text-slate-900 text-xs sm:text-sm font-medium mt-1 sm:mt-2">{{ $order->firstname }} {{ $order->lastname }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs sm:text-sm font-medium">Phone</p>
                            <p class="text-slate-900 text-xs sm:text-sm font-medium mt-1 sm:mt-2">{{ $order->phone }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-slate-500 text-xs sm:text-sm font-medium">Address</p>
                            <p class="text-slate-900 text-xs sm:text-sm font-medium mt-1 sm:mt-2">
                                {{ $order->house_number }}, {{ $order->street }}, {{ $order->city }} - {{ $order->postal_code }}
                            </p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-slate-500 text-xs sm:text-sm font-medium">Email</p>
                            <p class="text-slate-900 text-xs sm:text-sm font-medium mt-1 sm:mt-2">{{ $order->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Items + Summary -->
            <div class="space-y-6">
                <!-- Items -->
                <div class="bg-gray-50 rounded-xl p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base font-medium text-slate-900 mb-3 sm:mb-4">Order Items ({{ count($items) }})</h3>
                    <div class="space-y-4">
                        @foreach($items as $item)
                            @php
                                $product = $products->get($item->product_id);
                            @endphp
                            <div class="flex items-start gap-3 sm:gap-4">
                                <div class="w-[60px] h-[60px] sm:w-[70px] sm:h-[70px] bg-gray-200 rounded-lg flex items-center justify-center">
                                    <img src="{{ $product && $product->image 
                                                ? asset('storage/'.$product->image) 
                                                : asset('assets/images/default.jpg') }}"
                                         alt="Product"
                                         class="w-12 h-12 sm:w-14 sm:h-14 object-contain rounded-sm" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xs sm:text-sm font-medium text-slate-900">{{ $product->name ?? 'Unknown' }}</h4>
                                    <p class="text-slate-500 text-[11px] sm:text-xs mt-1 sm:mt-2">Size: {{ $item->ordersize ?? 'N/A' }}</p>
                                    <p class="text-slate-500 text-[11px] sm:text-xs mt-1">Qty: {{ $item->orderquantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-slate-900 text-xs sm:text-sm font-semibold">
                                        ${{ number_format(($product->price ?? 0) * $item->orderquantity, 2) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-gray-50 rounded-xl p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base font-medium text-slate-900 mb-3 sm:mb-4">Order Summary</h3>
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex justify-between text-xs sm:text-sm">
                            <p class="text-slate-500">Subtotal</p>
                            <p class="text-slate-900 font-semibold">${{ number_format($order->totalprice, 2) }}</p>
                        </div>
                        <div class="flex justify-between text-xs sm:text-sm">
                            <p class="text-slate-500">Shipping</p>
                            <p class="text-slate-900 font-semibold">$0.00</p>
                        </div>
                        <div class="flex justify-between border-t border-gray-300 pt-2 sm:pt-3 text-xs sm:text-sm">
                            <p class="text-slate-900 font-semibold">Total</p>
                            <p class="text-indigo-700 font-semibold">${{ number_format($order->totalprice, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-100 px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4">
            <p class="text-slate-500 text-xs sm:text-sm text-center sm:text-left">
                Need help? <a href="javascript:void(0)" class="text-indigo-700 hover:underline">Contact us</a>
            </p>
            <a href="{{ route('all.products') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-lg">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
