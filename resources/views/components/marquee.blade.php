@props(['cards'])

<div 
    x-data="{ stopScroll: false }" 
    class="overflow-hidden w-full relative max-w-6xl mx-auto"
    @mouseenter="stopScroll = true"
    @mouseleave="stopScroll = false"
>
    <!-- Left gradient fade -->
    <div class="absolute left-0 top-0 h-full w-20 z-10 pointer-events-none bg-gradient-to-r from-white to-transparent"></div>

    <!-- Scrolling wrapper -->
    <div 
        class="marquee-inner flex w-fit [animation-duration:{{ count($cards) * 2200 }}ms]"
        :class="stopScroll ? '[animation-play-state:paused]' : '[animation-play-state:running]'"
    >
        <div class="flex">
            @foreach (array_merge($cards, $cards, $cards) as $card) <!-- 3x duplication -->
                <div class="w-56 mx-2 h-[20rem] relative group transition-all duration-300">
                    <img src="{{ $card['image'] }}" alt="card" class="w-full h-full object-cover" />
                    <div class="flex items-center justify-center px-4 opacity-0 group-hover:opacity-100 transition-all duration-300 absolute bottom-0 backdrop-blur-sm left-0 w-full h-full bg-black/20">
                        <p class="text-white text-lg font-semibold text-center">
                            {{ $card['title'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Right gradient fade -->
    <div class="absolute right-0 top-0 h-full w-20 md:w-40 z-10 pointer-events-none bg-gradient-to-l from-white to-transparent"></div>
</div>
