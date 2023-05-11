@props(['projects'])
@foreach ($projects as $category)
  <div class="stacked-item {{ $loop->first ? 'stacked-item--border' : '' }} worklist-item is-mobile">
    @if ($projects->count() > 0)
      <h2>{{ str_replace("<br />", ", ", nl2br($category->title)) }}</h2>
    @endif
    @foreach ($category->projects as $project)
      <article class="stacked-item stacked-item--border">
        @if ($project->hasDetailPage)
          <a href="{{ route('page.project.show', ['project' => $project]) }}" title="{{ $project->title }}">
            <strong>{{ $project->title }} ({{ $project->type }}), {{ $project->location }}</strong><br>
          </a>
        @else
          <strong>{{ $project->title }} ({{ $project->type }}), {{ $project->location }}</strong><br>
        @endif
        {{ $project->periode }}, {{ $project->state->title }}
      </article>
    @endforeach
  </div>
@endforeach