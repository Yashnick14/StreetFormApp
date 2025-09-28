<x-app-layout>
    <div class="p-4">
        <div class="max-w-6xl mx-auto">

            <!-- Title -->
            <div class="mt-4 mb-4">
                <h2 class="text-2xl font-semibold text-center text-slate-900">Order Tracking</h2>
            </div>

            <!-- Header (3 column grid for ID, Date, Cancel Button) -->
            <div class="grid grid-cols-3 items-center border-b border-gray-300 pb-6">
                
                <!-- Left: Order ID & Date -->
                <div class="text-left">
                    <h4 class="text-base text-slate-600">Order ID: #{{ $order->_id }}</h4>
                    <p class="text-sm text-slate-500 mt-1">
                        Placed on {{ $order->orderdate->format('d M Y, H:i A') }}
                    </p>
                </div>

                <!-- Center: Empty for spacing -->
                <div></div>

            </div>

            <!-- Stepper (unchanged) -->
            @livewire('components.order-stepper', ['orderId' => $order->_id])

            <!-- Products + Delivery -->
            <div class="mt-12 grid lg:grid-cols-2 gap-12 items-start">
                
                <!-- Left Column: Products + Billing -->
                <div class="flex flex-col gap-8">
                    
                    <!-- Products -->
                    <div class="bg-white rounded-md p-6 border border-gray-200">
                        <h3 class="text-base font-semibold text-slate-900 border-b border-gray-300 pb-2">Products</h3>
                        <div class="space-y-4 mt-6">
                            @foreach ($order->itemsData as $item)
                                @php 
                                    $product = $order->productsData[$item->product_id] ?? null; 
                                    $unitPrice = $product->offer_price ?? $product->price ?? 0;
                                @endphp
                                <div class="flex items-center justify-between py-4 border-b border-gray-200">
                                    <div class="flex items-center gap-4">
                                        <div class="w-20 h-20 bg-gray-100 p-2 rounded-md">
                                            @if ($product && $product->image)
                                                <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-contain"/>
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">No Image</div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-[15px] font-medium text-slate-900">{{ $product->name ?? 'Unknown' }}</h4>
                                            <p class="text-xs text-slate-600 mt-1">Qty: {{ $item->orderquantity }}</p>
                                            @if($item->ordersize)
                                                <p class="text-xs text-slate-600">Size: {{ $item->ordersize }}</p>
                                            @endif
                                            <p class="text-xs text-slate-600">Unit: ${{ number_format($unitPrice, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <h4 class="text-[15px] font-medium text-slate-900">
                                            ${{ number_format($unitPrice * $item->orderquantity, 2) }}
                                        </h4>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Billing Details -->
                    <div class="bg-white rounded-md p-6 border border-gray-200">
                        <h3 class="text-base font-semibold text-slate-900 border-b border-gray-300 pb-2">Billing details</h3>
                        <ul class="font-medium mt-6 space-y-4">
                            <li class="flex justify-between text-slate-600 text-sm">
                                Subtotal 
                                <span class="text-slate-900 font-semibold">
                                    ${{ number_format($order->itemsData->sum(function($item) use ($order) {
                                        $product = $order->productsData[$item->product_id] ?? null;
                                        $unitPrice = $product->offer_price ?? $product->price ?? 0;
                                        return $unitPrice * $item->orderquantity;
                                    }), 2) }}
                                </span>
                            </li>
                            <li class="flex justify-between text-slate-600 text-sm">
                                Tax <span class="text-slate-900 font-semibold">$0.00</span>
                            </li>
                            <hr class="border-gray-300" />
                            <li class="flex justify-between text-[15px] font-semibold">
                                Total 
                                <span class="text-slate-900">
                                    ${{ number_format($order->itemsData->sum(function($item) use ($order) {
                                        $product = $order->productsData[$item->product_id] ?? null;
                                        $unitPrice = $product->offer_price ?? $product->price ?? 0;
                                        return $unitPrice * $item->orderquantity;
                                    }), 2) }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Right Column: Delivery Info -->
                <div class="bg-white rounded-md p-6 border border-gray-200 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 border-b border-gray-300 pb-2">Delivery information</h3>
                        <div class="mt-6 space-y-3 text-sm">
                            <p><span class="font-semibold text-slate-700">Customer</span><br> {{ $order->firstname }} {{ $order->lastname }}</p>
                            <p><span class="font-semibold text-slate-700">Shipping Method</span><br> Standard Delivery</p>
                            <p><span class="font-semibold text-slate-700">Address</span><br> {{ $order->house_number }} {{ $order->street }}, {{ $order->city }}, {{ $order->postal_code }}</p>
                            <p><span class="font-semibold text-slate-700">Phone</span><br> {{ $order->phone }}</p>
                            <p><span class="font-semibold text-slate-700">Email</span><br> {{ $order->email }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
