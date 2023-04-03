@props(['article'])
<a href="{{ $article->link }}" title="{{ $article->title }}">
  {{ $slot }}
</a>