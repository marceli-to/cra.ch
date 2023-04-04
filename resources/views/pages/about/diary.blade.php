@extends('layout.web')
@section('seo_title', 'Tagebuch • Über uns')
@section('seo_description', '')
@section('content')
@if ($diary)
  <section>
    @foreach($diary->grids as $g)
      <x-galleries.gallery :layout="$g->layout" :items="$g->gridItems" :article="$grid->articleContent ? $grid->articleContent : null" :view="'diary'" />
    @endforeach
  </section>
@endif
@endsection