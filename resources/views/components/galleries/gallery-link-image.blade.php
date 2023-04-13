@props(['image', 'caption'])
<a href="/img/lightbox/{{ $image->name }}" data-fancybox="gallery" data-caption="{{$caption}}">
  {{ $slot }}
</a>