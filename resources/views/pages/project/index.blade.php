@extends('layout.web')
@section('seo_title', __('Projekte'))
@section('seo_description', '')
@section('content')
<section class="content content-grid content-grid--1:1">
  <div class="content-grid__item sm:pt-12x project-list">
    @if ($project_categories)
      <nav class="page">
        <ul>
          @foreach($project_categories as $category)
            <li>
              <a 
                href="{{ route('page.project.index', ['category' => $category->slug]) }}" 
                title="{{ $category->title }}"
                class="{{ $category->id == $projectActiveCategory->id ? 'is-active' : '' }}">
                {!! str_replace(' ', '&nbsp;', $category->title) !!}
              </a>
            </li>
          @endforeach
        </ul>
      </nav>
    @endif
    @if ($projects)
      <nav class="content">
        <ul>
          @foreach($projects as $project)
            @php 
              $categories = $project->categories->pluck('id');
            @endphp

            <li data-categories="{{ $categories->join(',') }}" 
                class="{{ !in_array($projectActiveCategory->id, $categories->toArray()) ? 'is-hidden' : 'is-visible' }}">

              <a href="{{ route('page.project.show', ['category' => $projectActiveCategory->slug, 'slug' => AppHelper::slug($project->title), 'project' => $project]) }}" 
                title="{{ $project->title }}" 
                data-project="{{ $project->id }}">
                {{ $project->title }}
              </a>

            </li>
          @endforeach
        </ul>
      </nav>
    @endif

  </div>
  <div class="content-grid__item is-fixed">
    @if (isset($projectImage) && $projectImage && $projectImage->publishedImage)
      <x-image 
        :maxSizes="[1200 => 1500, 900 => 1200, 0 => 900]" 
        width="1600"
        height="900"
        :image="$projectImage->publishedImage"
        :visible="true"
        :overlay="true"
        :project="'preview'"
      />
    @endif
    <x-project-preview-images :projects="$projectsByCategory" :previewImage="$projectImage" />
  </div>
</section>
@endsection