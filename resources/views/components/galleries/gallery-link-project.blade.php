@props(['project'])
<a href="{{ route('page.project.show', ['project' => $project]) }}" title="{{ $project->title }}">
  {{ $slot }}
</a>