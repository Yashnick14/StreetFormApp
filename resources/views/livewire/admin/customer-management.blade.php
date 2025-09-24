<div>
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <h2 class="text-2xl font-bold text-gray-900">Customer Management</h2>
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="rounded-md bg-green-50 p-4 mx-4 mt-4">
            <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    <!-- Search + Status Button -->
    <div class="px-4 sm:px-6 lg:px-8 mt-6 flex justify-between items-center">
        <div class="max-w-md flex-1">
            <input wire:model.live="search"
                   type="text"
                   placeholder="Search customers..."
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <!-- Fixed Status Toggle Button -->
        <div>
            <button wire:click="toggleSelectedStatus"
                    @disabled(!$selectedCustomer)
                    class="px-4 py-2 rounded-md font-semibold shadow text-white 
                        {{ $selectedCustomer && $this->selectedCustomerModel && $this->selectedCustomerModel->user->status === 'inactive' 
                            ? 'bg-green-600 hover:bg-green-700' 
                            : 'bg-yellow-600 hover:bg-yellow-700' }}">
                @if ($selectedCustomer && $this->selectedCustomerModel)
                    {{ $this->selectedCustomerModel->user->status === 'active' ? 'Deactivate' : 'Activate' }}
                @else
                    Deactivate
                @endif
            </button>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="px-4 sm:px-6 lg:px-8 mt-6">
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Select</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <input type="radio" value="{{ $customer->id }}" wire:model="selectedCustomer"
                                       class="text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $customer->user->username }}</div>
                                <div class="text-sm text-gray-500">{{ $customer->user->firstname }} {{ $customer->user->lastname }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $customer->user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $customer->user->phones->first()->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $customer->user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($customer->user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex justify-center space-x-4">
                                <button wire:click="edit({{ $customer->id }})"
                                        class="text-gray-600 hover:text-gray-800 inline-flex items-center space-x-1">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    <span>Edit</span>
                                </button>
                                <button wire:click="delete({{ $customer->id }})"
                                        wire:confirm="Are you sure you want to delete this customer?"
                                        class="text-red-600 hover:text-red-800 inline-flex items-center space-x-1">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                    <span>Delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
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
                            <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
