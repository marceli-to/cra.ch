@props(['page', 'caption'])
<a href="{{ route('page.' . $page) }}" title="{{ $caption }}">
  {{ $slot }}
</a>