@props(['image', 'size' => null])

<div {{ $attributes->merge(['class' => 'aspect-w-2 aspect-h-3']) }}>
    <img
        src="{{ $image->size($size)->url() ?? $image->fallback() }}"
        width="{{ $image->width() }}"
        height="{{ $image->height() }}"
        loading="lazy"
        {{ $attributes->merge(['class' => 'w-full h-full object-center object-cover overflow-hidden']) }}
    />
</div>
