@props(['image', 'caption'])
@if (isset($image->name))
<a href="/img/cache/{{ $image->name }}/2000/{{ $image->coords }}" data-fancybox="gallery" data-caption="{{ $caption }}" title="{{ $caption }}">
  {{ $slot }}
</a>
@endif