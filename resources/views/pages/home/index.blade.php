@extends('layout.web')
@section('seo_description', 'Wir entwickeln, gestalten und begleiten Projekte und Illusionen. Wir hören zu, recherchieren, wägen ab und denken voraus. Wir planen sorgfältig und setzen mit Hingabe um. Ob Zimmer, Haus, Aussenraum, Brücke, Dorf oder Stadt. Unsere Architektur entsteht im Einklang mit Mensch, Raum und Umwelt – stets funktional und inspirierend.')
@section('content')
@if ($grid)
  <section>
    @foreach($grid->grids as $grid)
      <x-galleries.gallery :layout="$grid->layout" :items="$grid->gridItems" :view="'home'" />
    @endforeach
  </section>
@endif
@endsection