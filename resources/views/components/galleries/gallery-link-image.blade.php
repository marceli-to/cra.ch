@props(['image', 'caption'])
@if (isset($image->name))
<a href="{{ $image->url(2000, in_array('webp', \App\Support\ImageSupport::modernFormats()) ? 'webp' : null) }}" data-fancybox="gallery" data-caption="{{ $caption }}" title="{{ $caption }}">
  {{ $slot }}
</a>
@endif
