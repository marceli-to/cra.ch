@extends('layout.web')
@section('seo_title', __('Tagebuch'))
@section('seo_description', '')
@section('content')
@if ($grid)
  <section>
    @foreach($grid->grids as $g)
      <x-galleries.gallery :layout="$g->layout" :items="$g->gridItems" :article="$grid->article ? $grid->article : null" />
    @endforeach
  </section>
@endif
@endsection