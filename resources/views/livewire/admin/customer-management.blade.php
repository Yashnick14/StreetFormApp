<div class="overflow-x-hidden">
    <!-- Header -->
    <div>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Customer Management</h2>
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="rounded-md bg-green-50 p-4 mx-4 mt-4">
            <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    <!-- Search + Top Buttons -->
    <div class="px-4 sm:px-6 lg:px-8 mt-6 flex flex-col sm:flex-row gap-4 sm:gap-0 sm:justify-between sm:items-center">
        <!-- Search -->
        <div class="w-full sm:max-w-md relative">
            <input wire:model.live.debounce.500ms="search"
                   type="text"
                   placeholder="Search customers..."
                   class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm
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
        <div class="flex gap-3 flex-wrap sm:flex-nowrap">
            <button 
                wire:click="toggleSelectedStatus"
                :disabled="!@this.get('selectedCustomer')"
                class="inline-flex items-center justify-center rounded-md w-full sm:w-28 h-10
                    text-sm font-semibold text-white shadow-sm 
                    bg-black disabled:bg-gray-500">

                {{-- Correct label --}}
                @if ($selectedStatus === 'active')
                    Deactivate
                @elseif ($selectedStatus === 'inactive')
                    Activate
                @else
                    Deactivate
                @endif
            </button>

            <button wire:click="editSelected"
                    :disabled="!@this.get('selectedCustomer')"
                    class="inline-flex items-center justify-center rounded-md bg-black w-full sm:w-20 h-10
                        text-sm font-semibold text-white shadow-sm hover:bg-gray-800 disabled:bg-gray-500">
                Edit
            </button>

            <button wire:click="deleteSelected"
                    :disabled="!@this.get('selectedCustomer')"
                    wire:confirm="Are you sure you want to delete this customer?"
                    class="inline-flex items-center justify-center rounded-md bg-red-600 w-full sm:w-20 h-10
                        text-sm font-semibold text-white shadow-sm hover:bg-red-700 disabled:bg-gray-500">
                Delete
            </button>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="px-4 sm:px-6 lg:px-8 mt-6">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-3"></th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Name</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Email</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Phone</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-gray-50 @if($selectedCustomer == $customer->id) bg-indigo-50 @endif">
                                    <!-- Checkbox -->
                                    <td class="px-3 sm:px-6 py-4">
                                        <input type="radio" value="{{ $customer->id }}" wire:model="selectedCustomer"
                                               class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    </td>

                                    <!-- Name -->
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $customer->user->username }}</div>
                                        <div class="text-gray-500 text-xs sm:text-sm">{{ $customer->user->firstname }} {{ $customer->user->lastname }}</div>
                                    </td>

                                    <!-- Email -->
                                    <td class="px-3 sm:px-6 py-4 text-gray-900">{{ $customer->user->email }}</td>

                                    <!-- Phone -->
                                    <td class="px-3 sm:px-6 py-4 text-gray-900">{{ $customer->user->phones->first()->phone ?? 'N/A' }}</td>

                                    <!-- Status -->
                                    <td class="px-3 sm:px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-md
                                            {{ $customer->user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($customer->user->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No customers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 text-center">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form wire:submit.prevent="save">
                        <div class="bg-white px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Customer</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input wire:model="firstname" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('firstname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input wire:model="lastname" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('lastname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input wire:model="email" type="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input wire:model="phone" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 rounded-md bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
