@props(['project', 'caption'])
@if ($project)
<a href="{{ route('page.project.show', ['project' => $project]) }}" title="{{ $caption }}">
  {{ $slot }}
</a>
@endif