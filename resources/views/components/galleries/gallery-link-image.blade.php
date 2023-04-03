@props(['image', 'caption'])
<a href="/img/cache/{{ $image->name }}/1600/{{ $image->coords }}" data-fancybox="gallery" data-caption="{{$caption}}">
  {{ $slot }}
</a>