<div {{ $attributes->class('relative w-full h-4 bg-gray-700 overflow-hidden') }}>
    <div
        @class([
            'h-full',
            'bg-red-700' => $percentage < 50,
            'bg-yellow-700' => $percentage >= 50 && $percentage < 100,
            'bg-green-700' => $percentage >= 100,
        ])
        style="width: {{ $percentage }}%"
    ></div>
    <div class="absolute inset-0 flex items-center justify-center">
        <span class="text-white text-xs">{{ $current }} / {{ $total }}</span>
    </div>
</div>
