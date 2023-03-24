@extends('layout.web')
@section('seo_title', $project->title . ' • ' . __('Projekt'))
@if ($project->abstract)
@section('seo_description', $project->subtitle . ' - ' . trim(substr(strip_tags($project->abstract),0,255)))
@else
@section('seo_description', $project->subtitle . ' - ' . substr(strip_tags(str_replace('<br>', ' ', $project->text)),0,255))
@endif
@section('content')
<section class="content">
  <div class="project-info pb-3x js-project-info">
    <h2>{{ $project->subtitle }}</h2>
    @if ($project->abstract)
      <div class="project-info__abstract">
        {!! $project->abstract !!}
      </div>
    @endif
    @if ($project->text)
      <div class="project-info__text">
        {!! $project->text !!}
      </div>
    @endif
  </div>
  @foreach($project->grids as $grid)
    <div class="content-grid content-grid--{{ $grid->layout }}">
      @foreach($grid->gridItems as $gridItem)
        <figure class="content-grid__item">
          <x-image 
            :maxSizes="[1600 => 2000, 1200 => 1500, 900 => 1200, 0 => 900]" 
            width="1600"
            height="900"
            :image="$gridItem->image" 
            :ratio="$grid->layout == '1:w' ? $gridItem->image->ratio : null"
          />
        </figure>
      @endforeach
    </div>
  @endforeach
</section>
@endsection