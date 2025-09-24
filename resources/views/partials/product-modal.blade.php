<!-- Product Add/Edit Modal -->
<div id="product-modal" class="fixed inset-0 z-50 hidden justify-center items-center px-4 sm:px-0">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>

    <!-- Modal -->
    <div class="relative bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 w-full sm:max-w-4xl">
        <!-- Modal Loading Overlay -->
        <div id="modal-loading" class="absolute inset-0 bg-white bg-opacity-90 z-50 hidden flex items-center justify-center rounded-lg">
            <div class="flex flex-col items-center space-y-3">
                <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 
                             0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                <span id="modal-loading-text" class="text-sm text-gray-600">Loading...</span>
            </div>
        </div>

        <!-- Form -->
        <form id="product-form" class="p-6">
            <h3 id="modal-title" class="text-lg font-semibold text-gray-900 mb-6">Add New Product</h3>
            <input type="hidden" id="product-id">

            <div class="flex flex-col md:flex-row gap-6 items-start">
                <!-- Images -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Images</label>
                    <div class="relative flex flex-col items-center justify-center border-2 border-dashed 
                                border-gray-300 rounded-lg h-40 cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden">
                        <input type="file" id="image" accept="image/*" class="hidden"
                               onchange="previewImage(this, 'preview-image', 'placeholder-image')">
                        <label for="image" class="cursor-pointer flex flex-col items-center w-full h-full justify-center">
                            <img id="preview-image" class="hidden h-40 object-cover rounded-md">
                            <div id="placeholder-image" class="flex flex-col items-center text-gray-500">
                                <span class="text-xs">Upload Main Image</span>
                            </div>
                            <!-- Image Loading Spinner -->
                            <div id="image-loading" class="hidden flex flex-col items-center text-gray-500">
                                <svg class="animate-spin h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 
                                             0 12h4zm2 5.291A7.962 7.962 0 014 
                                             12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                <span class="text-xs">Processing...</span>
                            </div>
                        </label>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-4">
                        @foreach (['image2','image3','image4'] as $field)
                            <div class="relative flex flex-col items-center justify-center border-2 border-dashed 
                                        border-gray-300 rounded-lg h-24 cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden">
                                <input type="file" id="{{ $field }}" accept="image/*" class="hidden"
                                       onchange="previewImage(this, 'preview-{{ $field }}', 'placeholder-{{ $field }}')">
                                <label for="{{ $field }}" class="cursor-pointer flex flex-col items-center w-full h-full justify-center">
                                    <img id="preview-{{ $field }}" class="hidden h-24 object-cover rounded-md">
                                    <div id="placeholder-{{ $field }}" class="flex flex-col items-center text-gray-500">
                                        <span class="text-xs">Upload</span>
                                    </div>
                                    <!-- Image Loading Spinner -->
                                    <div id="{{ $field }}-loading" class="hidden flex flex-col items-center text-gray-500">
                                        <svg class="animate-spin h-4 w-4 mb-1" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 
                                                     0 12h4zm2 5.291A7.962 7.962 0 014 
                                                     12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                        </svg>
                                        <span class="text-[10px]">Loading...</span>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Inputs -->
                <div class="flex-1 space-y-4">
                    <input id="name" type="text" placeholder="Product Name *"
                           class="w-full rounded-md border-gray-300 shadow-sm">
                    <input id="description" type="text" placeholder="Description"
                           class="w-full rounded-md border-gray-300 shadow-sm">
                    <div class="flex">
                        <input id="price" type="number" step="0.01" placeholder="Price *"
                               class="flex-1 rounded-l-md border-gray-300 shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">USD</span>
                    </div>
                    <select id="category_id" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Select Category --</option>
                        <option value="1">Men</option>
                        <option value="2">Women</option>
                    </select>
                    <select id="type" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Select Type --</option>
                        <option value="Hoodies">Hoodies</option>
                        <option value="Cargo Pants">Cargo Pants</option>
                        <option value="Sweatshirts">Sweatshirts</option>
                        <option value="T-Shirts">T-Shirts</option>
                        <option value="Jackets">Jackets</option>
                    </select>
                </div>
            </div>

            <!-- Sizes -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sizes & Quantities</label>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach (['XS','S','M','L','XL'] as $sizeKey)
                        <div class="flex items-center justify-between border rounded-lg px-3 py-2 bg-gray-50">
                            <span class="text-sm font-semibold text-gray-700">{{ $sizeKey }}</span>
                            <input type="number" min="0" id="size-{{ $sizeKey }}" placeholder="0"
                                   class="w-16 text-sm rounded-md border-gray-300 shadow-sm">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 bg-white">
                    Cancel
                </button>
                <button type="submit" id="save-btn"
                        class="px-4 py-2 rounded-md bg-black text-white font-semibold shadow flex items-center">
                    <svg id="save-loading" class="hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 
                                 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    <span id="save-text">Save</span>
                </button>
            </div>
        </form>
    </div>
</div>
