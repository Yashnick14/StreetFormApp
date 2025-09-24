<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4 sm:px-0">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>

    <!-- Modal Content -->
    <div class="flex flex-col items-center bg-white shadow-md rounded-xl py-6 px-5 md:w-[460px] w-[370px] border border-gray-200 relative z-10">
        <div class="flex items-center justify-center p-4 bg-red-100 rounded-full">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M2.875 5.75h1.917m0 0h15.333m-15.333 0v13.417a1.917 1.917 0 0 0 1.916 1.916h9.584a1.917 1.917 0 0 0 1.916-1.916V5.75m-10.541 0V3.833a1.917 1.917 0 0 1 1.916-1.916h3.834a1.917 1.917 0 0 1 1.916 1.916V5.75m-5.75 4.792v5.75m3.834-5.75v5.75"
                    stroke="#DC2626" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2 class="text-gray-900 font-semibold mt-4 text-xl">Are you sure?</h2>
        <p class="text-sm text-gray-600 mt-2 text-center">
            Do you really want to delete this product?<br>This action cannot be undone.
        </p>
        <div class="flex items-center justify-center gap-4 mt-5 w-full">
            <button type="button" onclick="closeDeleteModal()"
                class="w-full md:w-36 h-10 rounded-md border border-gray-300 bg-white text-gray-600 font-medium text-sm hover:bg-gray-100 active:scale-95 transition">
                Cancel
            </button>
            <button type="button" onclick="confirmDeleteProduct()"
                class="w-full md:w-36 h-10 rounded-md text-white bg-red-600 font-medium text-sm hover:bg-red-700 active:scale-95 transition">
                Confirm
            </button>
        </div>
    </div>
</div>
