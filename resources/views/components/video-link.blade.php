@props(['video'])
<?php /** @var \Google\Service\Drive\DriveFile $video */ ?>

<a
    href="{{ $video->getWebViewLink() }}"
    target="_blank"
    title="{{ $video->getName() }}"
    {{ $attributes }}
>
    ‣
    @if($video->getVideoMediaMetadata())
        ({{ $video->getVideoMediaMetadata()->getWidth() }}x{{ $video->getVideoMediaMetadata()->getHeight() }})
    @endif
</a>