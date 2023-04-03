@extends('layout.web')
@section('content')
@if ($grid)
  <section>
    @foreach($grid->grids as $grid)
      <x-galleries.gallery :layout="$grid->layout" :items="$grid->gridItems" :view="'home'" />
    @endforeach
  </section>
@endif
@endsection