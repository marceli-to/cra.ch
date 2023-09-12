@extends('layout.web')
@section('seo_title', 'Tagebuch • Über uns')
@section('seo_description', 'Erkennen und erfassen, spüren und wahrnehmen, beobachten und zuhören. Das tun wir immer wieder. Im Alltag und an besonderen Tagen. Das Vertraute und das Unbekannte, die Farben und die Schatten, die Intimität und die Weite, das Kleine und das Grosse, die Gegensätze und der Zusammenklang. In jeder Situation suchen wir nach der Essenz, der Authentizität und nach dem, was sie letztlich einzigartig macht.')
@section('og_image', asset('assets/img/og/cristina-rutz-architekten-og-tagebuch.jpg'))
@section('content')
@if ($diary)
  <section>
    @foreach($diary->grids as $g)
      <x-galleries.gallery :layout="$g->layout" :items="$g->gridItems" :article="$diary->articleContent ? $diary->articleContent : null" :view="'diary'" />
    @endforeach
  </section>
@endif
@endsection