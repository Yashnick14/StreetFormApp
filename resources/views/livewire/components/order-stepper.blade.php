@php
    $statuses = [
        'pending' => 'Order placed',
        'confirmed' => 'Confirmed',
        'out for delivery' => 'Out for delivery',
        'delivered' => 'Delivered',
    ];

    $current = strtolower($order->orderstatus);
    $keys = array_keys($statuses);
    $currentIndex = array_search($current, $keys);

    // Special cancelled case
    $isCancelled = $current === 'cancelled';
@endphp

<div class="flex items-start max-w-screen-lg mx-auto mt-12">
    @foreach($statuses as $key => $label)
        @php
            $index = array_search($key, $keys);

            if ($isCancelled) {
                // Treat everything as completed
                $isCompleted = true;
                $isCurrent = $loop->last; // highlight the last step
            } else {
                // Normal flow
                if ($index === 0) {
                    $isCompleted = true;
                } else {
                    $isCompleted = $index < $currentIndex;
                }
                $isCurrent = $index === $currentIndex;
            }
        @endphp

        <div class="w-full">
            <!-- Circle + Line -->
            <div class="flex items-center w-full">
                <div class="w-7 h-7 shrink-0 mx-[-1px] 
                    {{ $isCancelled ? 'bg-red-600' : ($isCompleted || $isCurrent ? 'bg-blue-600' : 'bg-gray-300') }}
                    flex items-center justify-center rounded-full">
                    <span class="text-sm font-semibold 
                        {{ $isCancelled ? 'text-white' : ($isCompleted || $isCurrent ? 'text-white' : 'text-gray-500') }}">
                        {{ $index+1 }}
                    </span>
                </div>

                @if(!$loop->last)
                    <div class="w-full h-[3px] mx-4 rounded-lg 
                        {{ $isCancelled ? 'bg-red-600' : ($isCompleted ? 'bg-blue-600' : 'bg-gray-300') }}"></div>
                @endif
            </div>

            <!-- Labels -->
            <div class="mt-2 mr-4">
                <h6 class="text-sm font-semibold 
                    {{ $isCancelled ? 'text-red-600' : ($isCompleted || $isCurrent ? 'text-blue-600' : 'text-gray-500') }}">
                    {{ $isCancelled && $loop->last ? 'Cancelled' : $label }}
                </h6>
                <p class="text-xs text-gray-500">
                    @if($isCancelled && $loop->last)
                        Cancelled
                    @elseif($isCancelled && !$loop->last)
                        Pending
                    @elseif($isCompleted && $index !== $currentIndex)
                        Completed
                    @elseif($isCurrent && $loop->last)
                        Completed
                    @elseif($isCurrent)
                        In Progress
                    @else
                        Pending
                    @endif
                </p>
            </div>
        </div>
    @endforeach
</div>
