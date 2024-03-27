<div @class([
    'w-full py-0.5 text-center leading-none rounded-b text-xs text-white',
    'bg-gray-700' => $resolution === null,
    'bg-green-700' => in_array($resolution, ['2160p', '1080p']),
    'bg-yellow-700' => in_array($resolution, ['720p', '576p']),
    'bg-red-700' => in_array($resolution, ['480p', '360p']),
])>
    {{ $resolution ?? '-' }}
</div>
