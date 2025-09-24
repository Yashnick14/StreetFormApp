<aside class="w-64 bg-gradient-to-b from-black via-gray-900 to-gray-800 text-white min-h-screen shadow-lg">

    <nav class="flex flex-col p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}"
           class="px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white font-semibold shadow' : 'hover:bg-gray-900 text-white' }}">
            Dashboard
        </a>

        <a href="{{ route('admin.products') }}"
           class="px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.products') ? 'bg-gray-900 text-white font-semibold shadow' : 'hover:bg-gray-900 text-white' }}">
            Products
        </a>

        <a href="{{ route('admin.customers') }}"
           class="px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.customers') ? 'bg-gray-900 text-white font-semibold shadow' : 'hover:bg-gray-900 text-white' }}">
            Customers
        </a>

        <a href="#" class="px-4 py-2 rounded-lg transition hover:bg-gray-900 text-white">
            Orders
        </a>

        <a href="#" class="px-4 py-2 rounded-lg transition hover:bg-gray-900 text-white">
            Reports
        </a>
    </nav>
</aside>
