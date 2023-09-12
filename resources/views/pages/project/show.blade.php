@extends('layout.web')
@section('seo_title', $project->title)
@section('seo_description', $project->abstract)
@if (isset($og_image->name))
  @section('og_image', env('APP_URL') . '/img/cache/'. $og_image->name .'/1500/' .$og_image->coords)
@endif
@section('content')
@if ($project)
  <div class="flex direction-column md:grid md:grid-cols-6 md:grid-gap project">
    <div class="order-2 md:order-1 md:span-4">
    @foreach($project->grids as $g)
      <x-galleries.gallery :layout="$g->layout" :items="$g->gridItems" :view="'project'" />
    @endforeach
    </div>
    <div class="order-1 md:order-2 md:span-2 md:pl-25x project-content">
      <nav class="project-browse" data-browse>
        <div>
          <span data-browse-preview></span>
          @if ($browse['prev'])
            <a href="{{ route('page.project.show', ['project' => $browse['prev']]) }}" title="{{ $browse['prev']->title }}" data-browse-btn>
              <x-icons.chevron-prev-small />
            </a>
          @endif
          @if ($browse['next'])
            <a href="{{ route('page.project.show', ['project' => $browse['next']]) }}" title="{{ $browse['next']->title }}" data-browse-btn>
              <x-icons.chevron-next-small />
            </a>
          @endif
        </div>
      </nav>
      <article class="stacked-item stacked-item--border stacked-item--indent pb-0 md:pb-0">
        <div>
          <header>
            <a href="javascript:;" title="Projektbeschreibung anzeigen" class="flex justify-between" data-btn-project-info>
              <h1>{{ $project->title }}</h1>
              <x-icons.chevron-down class="icon-chevron-down" />
              <x-icons.cross class="icon-cross" />
            </a>
          </header>
        </div>
        <section data-project-info>
          <div class="stacked-item stacked-item--indent">
            <div class="project-content__text">
              @if (Str::wordCount($project->text) > 100)
                <x-truncated-text preview="{!! Str::words($project->text, 100, '') !!}">
                  {!! $project->text !!}
                </x-truncated-text>
              @else
                {!! $project->text !!}
              @endif
            </div>
          </div>
          <div class="stacked-item stacked-item--border stacked-item--indent">
            <div>
              <h2>Leistungen</h2>
              {!! $project->text_services !!}
            </div>
          </div>
          <div class="stacked-item stacked-item--border stacked-item--indent">
            <div>
              {!! $project->text_info !!}
            </div>
          </div>
        </section>
      </article>
    </div>
  </div>
@endif
@endsection