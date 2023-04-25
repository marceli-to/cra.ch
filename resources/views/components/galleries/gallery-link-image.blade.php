@props(['image', 'caption'])
<a href="/img/cache/{{ $image->name }}/2000/{{ $image->coords }}" data-fancybox="gallery" data-caption="{{$caption}}" title="{{$caption}}">
  {{ $slot }}
</a>