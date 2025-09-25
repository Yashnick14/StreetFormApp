<div>
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900">Order Management</h2>
                <input wire:model.live="search"
                       type="text"
                       placeholder="Search orders..."
                       class="block w-64 rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm
                              ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
                              focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="p-6 overflow-x-auto">
        <table class="w-full text-sm text-left border border-gray-300 bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 cursor-pointer" wire:click="sortBy('order_number')">#</th>
                    <th class="p-3">Customer</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Date</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b">
                        <td class="p-3 font-semibold">{{ $order->order_number ?? $order->_id }}</td>
                        <td class="p-3">{{ $order->firstname }} {{ $order->lastname }}</td>
                        <td class="p-3">{{ $order->email }}</td>
                        <td class="p-3">${{ number_format($order->totalprice, 2) }}</td>

                        <!-- Status Column -->
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-bold
                                @if($order->orderstatus === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($order->orderstatus === 'confirmed') bg-blue-100 text-blue-700
                                @elseif($order->orderstatus === 'processing') bg-indigo-100 text-indigo-700
                                @elseif($order->orderstatus === 'shipped') bg-purple-100 text-purple-700
                                @elseif($order->orderstatus === 'delivered') bg-green-100 text-green-700
                                @elseif($order->orderstatus === 'cancelled') bg-red-100 text-red-700
                                @elseif($order->orderstatus === 'paid') bg-emerald-100 text-emerald-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($order->orderstatus) }}
                            </span>
                        </td>

                        <!-- Date -->
                        <td class="p-3">{{ $order->orderdate->format('Y-m-d H:i') }}</td>

                        <!-- Actions Column -->
                        <td class="p-3">
                            <select wire:model="statusUpdates.{{ $order->_id }}"
                                    wire:change="updateStatus('{{ $order->_id }}')"
                                    class="border rounded px-2 py-1 text-sm"
                                    onchange="this.selectedIndex = 0">
                                <option value="" selected>Status</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
