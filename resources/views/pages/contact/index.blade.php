@extends('layout.web')
@section('seo_title', 'Kontakt')
@section('seo_description', '')
@section('content')
@if ($contact)
  <div class="md:grid md:grid-cols-6 md:grid-gap contact">
    <div class="md:span-2">
      <article class="stacked-item stacked-item--border stacked-item--indent">
        <div>
          @if ($contact->address)
            {!! $contact->address !!}
          @endif
        </div>
      </article>
      <article class="stacked-item stacked-item--border stacked-item--indent">
        <div>

          @if ($contact->description)
            {!! $contact->description !!}
          @endif
          @if ($contact->maps_uri)
            <p>
              <a href="{{ $contact->maps_uri }}" target="_blank" rel="noopener noreferrer" class="link-external link-external--md">
                auf Google Maps anzeigen
              </a>
            </p>
          @endif

          @if ($contact->imprint)
            <a href="javascript:;" class="flex justify-start" data-btn-imprint>
              Impressum
              <x-icons.chevron-down class="icon-chevron-down mt-4x ml-4x" />
            </a>
            <div class="is-hidden pt-11x sm:pt-12x pb-20x" data-imprint>
              {!! $contact->imprint !!}
            </div>
          @endif

        </div>
      </article>
    </div>
    <div class="md:span-4 md:grid md:grid-cols-6 grid-gallery-2-1_1">
       <div class="grid-area-a aspect-ratio-e">
        <a href="/assets/media/cristina-ruth-situationsplan-winterthur.jpg" data-fancybox="gallery">
          <picture>
            <img src="/assets/media/cristina-ruth-situationsplan-winterthur.jpg" class="is-responsive">
          </picture>
        </a>
      </div>
      <div class="grid-area-b aspect-ratio-c">
        <a href="/assets/media/cristina-ruth-buero.jpg" data-fancybox="gallery">
          <picture>
            <img src="/assets/media/cristina-ruth-buero.jpg" class="is-responsive">
          </picture>
        </a>
      </div>
      <div class="grid-area-c aspect-ratio-c">
        <a href="/assets/media/cristina-ruth-situationsplan.jpg" data-fancybox="gallery">
          <picture>
            <img src="/assets/media/cristina-ruth-situationsplan.jpg" class="is-responsive">
          </picture>
        </a>
      </div>  
    </div>
  </div>
@endif
@endsection