<div>
    <div class="min-h-screen bg-gray-100 p-4 sm:p-6">
        <!-- Page Header -->
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 sm:mb-8 text-center sm:text-left">
            Admin Dashboard
        </h1>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Users -->
            <div class="p-4 sm:p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-indigo-500 to-blue-600 text-white">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 bg-white/20 rounded-lg"></div>
                    <div class="ml-3 sm:ml-4">
                        <h3 class="text-xs sm:text-sm text-white/80">Users</h3>
                        <p class="text-xl sm:text-2xl font-bold">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="p-4 sm:p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-green-500 to-emerald-600 text-white">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 bg-white/20 rounded-lg"></div>
                    <div class="ml-3 sm:ml-4">
                        <h3 class="text-xs sm:text-sm text-white/80">Products</h3>
                        <p class="text-xl sm:text-2xl font-bold">{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>

            <!-- Orders -->
            <div class="p-4 sm:p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-yellow-400 to-orange-500 text-white">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 bg-white/20 rounded-lg"></div>
                    <div class="ml-3 sm:ml-4">
                        <h3 class="text-xs sm:text-sm text-white/80">Orders</h3>
                        <p class="text-xl sm:text-2xl font-bold">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="p-4 sm:p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-pink-500 to-red-600 text-white">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 bg-white/20 rounded-lg"></div>
                    <div class="ml-3 sm:ml-4">
                        <h3 class="text-xs sm:text-sm text-white/80">Revenue</h3>
                        <p class="text-xl sm:text-2xl font-bold">${{ number_format($revenue, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="mt-8 sm:mt-10 bg-white rounded-xl shadow p-4 sm:p-6">
            <x-order-revenue-chart />
        </div>
    </div>
</div>
