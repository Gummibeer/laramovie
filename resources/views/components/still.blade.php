@props(['src' => null, 'alt' => null, 'model' => null])

@isset($model)
    @php($src = $model->still())
    @php($alt = $model->name)
@endisset

<div {{ $attributes->merge(['class' => 'aspect-w-16 aspect-h-9 bg-white']) }}>
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        loading="lazy"
        {{ $attributes->merge(['class' => 'w-full h-full object-center object-contain overflow-hidden']) }}
    />
</div>