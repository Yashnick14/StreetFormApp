<div x-data="{ open: false }"
     x-on:open-filter.window="open = true"
     x-show="open"
     class="fixed inset-0 z-40 flex justify-end"
     style="display: none;">

    <!-- Overlay (starts below navbar) -->
    <div class="fixed top-[64px] left-0 right-0 bottom-0" 
         @click="open = false"></div>

    <!-- Sidebar (also starts below navbar) -->
    <div class="relative w-80 bg-white shadow-lg h-[calc(100vh-64px)] mt-[64px] p-6 overflow-y-auto">

        <div class="flex items-center border-b border-gray-300 pb-2 mb-6">
            <h3 class="text-slate-900 text-lg font-semibold">Filter</h3>
            <button type="button" wire:click="clear"
                    class="text-sm text-red-500 font-semibold ml-auto cursor-pointer">
                Clear all
            </button>
        </div>

        <!-- ✅ Category (show only on /all page, hide on /men & /women) -->
        @if(!Route::is('men.products') && !Route::is('women.products'))
            <div class="mb-6">
                <h6 class="text-slate-900 text-sm font-semibold">Category</h6>
                <ul class="mt-4 space-y-3">
                    @foreach($categoryOptions as $label => $id)
                        <li class="flex items-center gap-3">
                            <input type="checkbox"
                                id="category_{{ $id }}"
                                value="{{ $id }}"
                                wire:model.live="selectedCategories"
                                class="w-4 h-4 cursor-pointer">
                            <label for="category_{{ $id }}" class="text-slate-600 text-sm font-medium cursor-pointer">
                                {{ $label }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
            <hr class="my-6 border-gray-300" />
        @endif

        <!-- Product Type -->
        <div>
            <h6 class="text-slate-900 text-sm font-semibold">Product Type</h6>
            <ul class="mt-4 space-y-3">
                @foreach($typeOptions as $type)
                    <li class="flex items-center gap-3">
                        <input type="checkbox"
                               id="type_{{ $type }}"
                               value="{{ $type }}"
                               wire:model.live="selectedTypes"
                               class="w-4 h-4 cursor-pointer">
                        <label for="type_{{ $type }}" class="text-slate-600 text-sm font-medium cursor-pointer">
                            {{ $type }}
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>

        <hr class="my-6 border-gray-300" />

        <!-- Size -->
        <div>
            <h6 class="text-slate-900 text-sm font-semibold">Size</h6>
            <div class="flex flex-wrap gap-3 mt-4">
                @foreach($sizeOptions as $size)
                    <label class="cursor-pointer">
                        <input type="checkbox"
                               value="{{ $size }}"
                               wire:model.live="selectedSizes"
                               class="hidden peer">
                        <span class="border border-gray-300 rounded-md text-sm text-slate-600 py-1 px-3
                                     peer-checked:border-blue-600 peer-checked:text-blue-700">
                            {{ $size }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <hr class="my-6 border-gray-300" />

        <!-- Price -->
        <div x-data="{ 
                min: @entangle('minPrice'), 
                max: @entangle('maxPrice'), 
                floor: {{ $floor }}, 
                ceil: {{ $ceil }}
            }">
            <h6 class="text-slate-900 text-sm font-semibold">Price</h6>

            <div class="relative mt-6">
                <div class="h-1.5 bg-gray-300 relative rounded-full overflow-hidden">
                    <div class="absolute h-1.5 bg-pink-500 rounded-full"
                         :style="{
                            left: ((min - floor) / (ceil - floor) * 100) + '%',
                            width: ((max - min) / (ceil - floor) * 100) + '%'
                         }"></div>
                </div>

                <input type="range" min="{{ $floor }}" max="{{ $ceil }}"
                       x-model.number="min"
                       wire:model.lazy="minPrice"
                       class="absolute top-0 w-full h-1.5 bg-transparent appearance-none cursor-pointer
                              [&::-webkit-slider-thumb]:appearance-none
                              [&::-webkit-slider-thumb]:w-5 
                              [&::-webkit-slider-thumb]:h-5 
                              [&::-webkit-slider-thumb]:bg-pink-500 
                              [&::-webkit-slider-thumb]:rounded-full" />

                <input type="range" min="{{ $floor }}" max="{{ $ceil }}"
                       x-model.number="max"
                       wire:model.lazy="maxPrice"
                       class="absolute top-0 w-full h-1.5 bg-transparent appearance-none cursor-pointer
                              [&::-webkit-slider-thumb]:appearance-none
                              [&::-webkit-slider-thumb]:w-5 
                              [&::-webkit-slider-thumb]:h-5 
                              [&::-webkit-slider-thumb]:bg-pink-500 
                              [&::-webkit-slider-thumb]:rounded-full" />

                <div class="flex justify-between text-slate-600 font-medium text-sm mt-10">
                    <span>${{ $minPrice }}</span>
                    <span>${{ $maxPrice }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
