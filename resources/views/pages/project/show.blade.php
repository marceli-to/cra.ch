@extends('layout.web')
@section('seo_title', $project->title)
@section('seo_description', $project->abstract)
@section('content')
@if ($project)
  <div class="flex direction-column md:grid md:grid-cols-6 grid-gap project">
    <div class="order-2 md:order-1 md:span-4">
    @foreach($project->grids as $g)
      <x-galleries.gallery :layout="$g->layout" :items="$g->gridItems" />
    @endforeach
    </div>
    <div class="order-1 md:order-2 md:span-2 md:pl-25x project-info">
      <article class="stacked-item">
        <header>
          <a href="javascript:;" title="Projektbeschreibung anzeigen" class="flex justify-between" data-btn-project-info>
            <h1>{{ $project->title }}</h1>
            <x-icons.chevron-down class="icon-chevron-down" />
            <x-icons.cross class="icon-cross" />
          </a>
        </header>
        <section data-project-info>
          <div class="pb-30x">{!! $project->text !!}</div>
          <div class="stacked-item pb-30x">
            {!! $project->text_services !!}
          </div>
          <div class="stacked-item pb-30x">
            {!! $project->text_info !!}
          </div>
        </section>
      </article>
    </div>
  </div>
@endif
@endsection