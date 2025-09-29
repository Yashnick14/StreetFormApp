@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="overflow-x-hidden">
    <!-- Header -->
    <div>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Product Management</h2>
            </div>
        </div>
    </div>

    <!-- Error Box -->
    <div id="error-box" class="hidden bg-red-100 text-red-700 p-3 rounded mx-4 mt-4"></div>

    <!-- Search + Top Buttons -->
    <div class="px-4 sm:px-6 lg:px-8 mt-6 flex flex-col sm:flex-row gap-4 sm:gap-0 sm:justify-between sm:items-center">
        <div class="w-full sm:max-w-md relative">
            <input wire:model.live.debounce.500ms="search"
                   type="text"
                   placeholder="Search products..."
                   class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm
                          ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
                          focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm pr-10">

            <!-- Search Loading Spinner -->
            <div wire:loading wire:target="search" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 flex-wrap sm:flex-nowrap">
            <button onclick="openModal()"
                    class="inline-flex items-center justify-center rounded-md bg-black w-full sm:w-20 h-10
                        text-sm font-semibold text-white shadow-sm hover:bg-gray-800">
                Add
            </button>

            <button onclick="editSelectedProduct()"
                    class="inline-flex items-center justify-center rounded-md bg-black w-full sm:w-20 h-10
                        text-sm font-semibold text-white shadow-sm hover:bg-gray-800 disabled:bg-gray-500"
                    id="edit-btn" disabled>
                Edit
            </button>

            <button onclick="openDeleteModalForSelected()"
                    class="inline-flex items-center justify-center rounded-md bg-red-600 w-full sm:w-20 h-10
                        text-sm font-semibold text-white shadow-sm hover:bg-red-700 disabled:bg-gray-500"
                    id="delete-btn" disabled>
                Delete
            </button>
        </div>
    </div>

    <!-- Table (Responsive wrapper for mobile) -->
    <div class="px-4 sm:px-6 lg:px-8 mt-6 relative">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <!-- Select All Checkbox -->
                                <th class="px-3 sm:px-6 py-3">
                                    <input type="checkbox" id="select-all"
                                           class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </th>
                                <th wire:click="sortBy('name')"
                                    class="cursor-pointer px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">
                                    Name
                                </th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Image</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Stock</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Type</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Category</th>
                                <th wire:click="sortBy('price')"
                                    class="cursor-pointer px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">
                                    Price
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @forelse($products as $p)
                                <tr class="hover:bg-gray-50">
                                    <!-- Row Checkbox -->
                                    <td class="px-3 sm:px-6 py-4">
                                        <input type="checkbox" class="product-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                               value="{{ $p->id }}">
                                    </td>

                                    <!-- Name -->
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $p->name }}</div>
                                        <div class="text-gray-500 text-xs sm:text-sm">{{ Str::limit($p->description,50) }}</div>
                                    </td>

                                    <!-- Image -->
                                    <td class="px-3 sm:px-6 py-4">
                                        @if($p->image)
                                            <img src="{{ Storage::url($p->image) }}" class="h-10 w-10 rounded-lg object-cover">
                                        @else
                                            <div class="h-10 w-10 bg-gray-300 rounded-lg"></div>
                                        @endif
                                    </td>

                                    <!-- Stock Quantities -->
                                    <td class="px-3 sm:px-6 py-4">
                                        @php
                                            $sizesArr = is_array($p->stockquantity) ? $p->stockquantity : [];
                                            $totalStock = array_sum(array_values($sizesArr));
                                        @endphp

                                        @if(!empty($sizesArr) && $totalStock > 0)
                                            <div class="flex flex-wrap gap-2 mb-2">
                                                @foreach(['XS','S','M','L','XL'] as $size)
                                                    @php $qty = $sizesArr[$size] ?? 0; @endphp
                                                    <div class="relative">
                                                        @if((int)$qty > 0)
                                                            <span class="inline-flex items-center justify-center w-8 sm:w-10 h-8 text-xs sm:text-sm font-semibold rounded-lg
                                                                        {{ (int)$qty < 5 
                                                                            ? 'bg-red-100 text-red-800 border border-red-200' 
                                                                            : 'bg-green-100 text-green-800 border border-green-200' }}">
                                                                {{ $size }}
                                                            </span>
                                                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-4 h-4 
                                                                        text-[10px] font-bold text-gray-100 bg-gray-600 rounded-full shadow">
                                                                {{ (int)$qty }}
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center justify-center w-8 sm:w-10 h-8 text-xs sm:text-sm font-semibold rounded-lg 
                                                                        bg-gray-100 text-gray-400 border border-gray-200 opacity-50">
                                                                {{ $size }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            @if($totalStock <= 5)
                                                <div class="text-xs text-red-500 font-medium">Low Stock!</div>
                                            @endif

                                        @else
                                            <div class="text-center">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Out of Stock
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Type -->
                                    <td class="px-3 sm:px-6 py-4 text-gray-500">
                                        {{ $p->type ?? '-' }}
                                    </td>

                                    <!-- Category -->
                                    <td class="px-3 sm:px-6 py-4 text-gray-500">
                                        {{ $p->category->name ?? '-' }}
                                    </td>

                                    <!-- Price -->
                                    <td class="px-3 sm:px-6 py-4 text-gray-500">
                                        ${{ number_format($p->price,2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-6 text-gray-500">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Modal -->
    @include('partials.product-modal')

    <!-- Delete Confirmation Modal -->
    @include('partials.delete-confirmation-modal')
</div>

@vite('resources/js/admin/products.js')
