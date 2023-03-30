@extends('layout.web')
@section('content')
@if ($grid)
  <section>
    @foreach($grid->grids as $grid)
      <x-galleries.gallery :layout="$grid->layout" :items="$grid->gridItems" />
    @endforeach
  </section>
@endif
@endsection