@extends('layout.web')
@section('seo_title', 'Team • Über uns')
@section('seo_description', 'Seit 2012 bin ich als Architektin selbständig tätig. Mit meiner langjährigen Erfahrung als Leiterin komplexer Projekte umfassen meine heutigen Aufgaben die Planung und Realisierung von Neu- und Umbauten, Machbarkeitsstudien unter Berücksichtigung baurechtlicher und ortsbezogener Rahmenbedingungen sowie die Vorstandsarbeit im Forum Architektur Winterthur.')
@section('og_image', asset('assets/img/og/cristina-rutz-architekten-og-team.jpg'))
@section('content')
  <div class="flex direction-column md:grid md:grid-cols-6 md:grid-gap about">
    @if ($about->description || $about->column_two)
      <div class="order-1 md:span-2">
        @if ($about->description)
          <article class="stacked-item stacked-item--border stacked-item--indent">
            <div class="md:pr-16x">{!! $about->description !!}</div>
          </article>
        @endif
        @if ($about->membership)
          <article class="stacked-item stacked-item--border stacked-item--indent">
            <div class="md:pr-16x">{!! $about->membership !!}</div>
          </article>
        @endif
      </div>
    @endif
    @if ($resume)
      <div class="order-3 md:order-2 md:span-2">
        <article class="stacked-item stacked-item--border stacked-item--indent">
          <div class="md:pr-16x">
            <h2 class="about__title">{!! $about->title !!}</h2>
            <x-truncated-text class="mt-0">
              <div class="grid grid-cols-6 grid-gap grid-row-gap mt-16x">
                @foreach($resume as $r)
                  <div class="span-2">{{ $r->periode }}</div>
                  <div class="span-4">{{ $r->description }}</div>
                @endforeach
              </div>
            </x-truncated-text>
          </div>
        </article>

        @if ($about->employees)
          <article class="stacked-item stacked-item--border stacked-item--indent">
            <div class="md:pr-16x">
              {!! $about->employees !!}
            </div>
          </article>
        @endif
        
        @if ($about->cooperation)
          <article class="stacked-item stacked-item--border stacked-item--indent">
            <div class="md:pr-16x">{!! $about->cooperation !!}</div>
          </article>
        @endif
      </div>
    @endif
    @if ($about->publishedImages)
      <div class="order-2 md:order-3 mb-20x md:mb-0 md:span-2">
        @foreach($about->publishedImages as $image)
          <x-image 
            classes="is-responsive is-{{ $image->orientation }} mb-8x md:mb-16x"
            :maxSizes="[0 => 1200]" 
            :image="$image" 
            width="1200" 
            height="800"
            :caption="$image->caption" />
        @endforeach
      </div>
    @endif
  </div>
@endsection