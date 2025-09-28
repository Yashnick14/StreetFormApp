<div>
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Order Management</h2>
            </div>
        </div>
    </div>

    <!-- Search + Top Buttons -->
    <div class="px-4 sm:px-6 lg:px-8 mt-6 flex justify-between items-center">
        <!-- Search -->
        <div class="max-w-md flex-1 relative">
            <input wire:model.live.debounce.500ms="search"
                   type="text"
                   placeholder="Search orders..."
                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
                          ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
                          focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm pr-10">

            <!-- Inline Spinner -->
            <div wire:loading wire:target="search"
                 class="absolute right-3 top-1/2 transform -translate-y-1/2">
                <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 
                             5.291A7.962 7.962 0 014 12H0c0 3.042 
                             1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <button wire:click="editSelected"
                    :disabled="!@this.get('selectedOrderId')"
                    class="inline-flex items-center justify-center rounded-md w-20 h-10
                        text-sm font-semibold text-white shadow-sm
                        bg-black hover:bg-gray-800
                        disabled:bg-gray-500 disabled:cursor-not-allowed">
                Edit
            </button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="px-4 sm:px-6 lg:px-8 mt-6">
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3"></th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 @if($selectedOrderId == $order->_id) bg-gray-50 @endif">
                            <!-- Checkbox -->
                            <td class="px-6 py-4">
                                <input type="radio" value="{{ $order->_id }}" wire:model="selectedOrderId"
                                       class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            </td>

                            <!-- Order Info -->
                            <td class="px-6 py-4 font-semibold">{{ $order->order_number ?? $order->_id }}</td>
                            <td class="px-6 py-4">{{ $order->firstname }} {{ $order->lastname }}</td>
                            <td class="px-6 py-4">{{ $order->email }}</td>
                            <td class="px-6 py-4">${{ number_format($order->totalprice, 2) }}</td>
                            <td class="px-6 py-4">{{ $order->orderdate->format('Y-m-d') }}</td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-md
                                    @if($order->orderstatus === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($order->orderstatus === 'paid') bg-green-100 text-green-700
                                    @elseif($order->orderstatus === 'confirmed') bg-blue-100 text-blue-700
                                    @elseif($order->orderstatus === 'out for delivery') bg-blue-100 text-blue-700
                                    @elseif($order->orderstatus === 'delivered') bg-green-100 text-green-700
                                    @elseif($order->orderstatus === 'cancelled') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ ucfirst($order->orderstatus) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{ open: @entangle('showStatusModal') }">
        <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-bold mb-4">Update Order Status</h2>

                @php
                    $order = $orders->firstWhere('_id', $selectedOrderId);
                    $flow = [
                        'pending' => ['confirmed', 'cancelled'],
                        'confirmed' => ['out for delivery', 'cancelled'],
                        'out for delivery' => ['delivered', 'cancelled'],
                        'delivered' => [],
                        'cancelled' => []
                    ];
                    $allowed = $order ? ($flow[$order->orderstatus] ?? []) : [];
                @endphp

                <select wire:model="statusUpdates.{{ $selectedOrderId }}" class="w-full border rounded px-3 py-2 mb-4">
                    <option value="">-- Select Status --</option>
                    @foreach($allowed as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>

                <div class="flex justify-end gap-3">
                    <button @click="open=false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>
                    <button wire:click="changeStatus" @click="open=false"
                            class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">
                        Change
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
