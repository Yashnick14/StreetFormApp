<div class="min-h-screen bg-gray-100 p-6">
    <!-- Page Header -->
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Admin Dashboard</h1>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Users -->
        <div class="p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-indigo-500 to-blue-600 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                </div>
                <div class="ml-4">
                    <h3 class="text-white/80">Users</h3>
                    <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-green-500 to-emerald-600 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                </div>
                <div class="ml-4">
                    <h3 class="text-white/80">Products</h3>
                    <p class="text-2xl font-bold">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-yellow-400 to-orange-500 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                </div>
                <div class="ml-4">
                    <h3 class="text-white/80">Orders</h3>
                    <p class="text-2xl font-bold">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-pink-500 to-red-600 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg">
                </div>
                <div class="ml-4">
                    <h3 class="text-white/80">Revenue</h3>
                    <p class="text-2xl font-bold">Rs. {{ number_format($revenue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="mt-10 bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-700">Recent Orders</h2>
            <a href="#" class="text-sm text-indigo-600 hover:underline">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Order ID</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach(\App\Models\Order::latest()->take(5)->get() as $order)
                        <tr>
                            <td class="px-6 py-3">{{ $order->id }}</td>
                            <td class="px-6 py-3">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3">Rs. {{ number_format($order->totalprice, 2) }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded text-xs 
                                    @if($order->status === 'completed') bg-green-100 text-green-700
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
