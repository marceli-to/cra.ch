@props(['project', 'caption'])
<a href="{{ route('page.project.show', ['project' => $project]) }}" title="{{ $caption }}">
  {{ $slot }}
</a>