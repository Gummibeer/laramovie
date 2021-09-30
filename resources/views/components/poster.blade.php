@props(['src' => null, 'alt' => null, 'model' => null])

@isset($model)
    @php($src = $model->poster()->url())
    @php($alt = $model->name)
@endisset

<div {{ $attributes->merge(['class' => 'aspect-w-2 aspect-h-3']) }}>
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        loading="lazy"
        {{ $attributes->merge(['class' => 'w-full h-full object-center object-cover overflow-hidden']) }}
    />
</div>