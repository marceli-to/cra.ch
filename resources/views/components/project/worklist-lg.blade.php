@props(['projects'])
@foreach ($projects as $category)
  <div class="stacked-item stacked-item--border worklist-item is-desktop">
    <div class="worklist-item__category">
      @if ($projects->count() > 0)
        <h2>{!! nl2br($category->title) !!}</h2>
      @endif
    </div>
    <div class="worklist-item__info">
      @foreach ($category->projects as $project)
        <article class="stacked-item {{ $loop->first ? '' : 'stacked-item--border' }}">
          <div class="grid grid-cols-12 grid-gap">
            <div class="span-5">
              @if ($project->hasDetailPage)
                <a href="{{ route('page.project.show', ['project' => $project]) }}" title="{{ $project->title }}">
                  <strong>{{ $project->title }}</strong>
                </a>
              @else
                <strong>{{ $project->title }}</strong>
              @endif
              <div class="text-sm">{{ $project->type }}</div>
            </div>
            <div class="span-2">
              {{ $project->location }}
            </div>
            <div class="span-2">
              {{ $project->periode }}
            </div>
            <div class="span-3 xl:span-2">
              {{ $project->state->title }}
            </div>
          </div>
        </article>
      @endforeach
    </div>
  </div>
@endforeach