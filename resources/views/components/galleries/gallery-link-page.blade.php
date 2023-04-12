@props(['page'])
<a href="{{ route('page.' . $page) }}">
  {{ $slot }}
</a>