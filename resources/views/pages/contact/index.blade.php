@extends('layout.web')
@section('seo_title', 'Kontakt')
@section('seo_description', 'Unser Architekturbüro liegt am Lagerplatz im lebendigen Sulzerareal Stadtmitte, angrenzend an die Lokstadt, in Fussdistanz zum Hauptbahnhof Winterthur. Von unseren Räumlichkeiten aus im 1. Stock des Gebäudes 190 blicken wir direkt auf die beeindruckende, historische Halle 53.')
@section('og_image', asset('assets/img/og/cristina-rutz-architekten-og-kontakt.jpg'))
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
            <div class="is-hidden pt-11x sm:pt-12x sm:pb-20x" data-imprint>
              {!! $contact->imprint !!}
            </div>
          @endif

        </div>
      </article>
    </div>
    @if ($contact->publishedImages->count() > 0)
      <div class="md:span-4">
        <div class="md:grid md:grid-cols-6 grid-gallery-1-1_1">
            @if (isset($contact->publishedImages[0]))
              <div class="grid-area-a aspect-ratio-b">
                <x-galleries.gallery-link-image :image="$contact->publishedImages[0]" :caption="''">
                  <x-image 
                  :maxSizes="[1024 => 900, 0 => 1200]" 
                  :image="$contact->publishedImages[0]" 
                  width="1600" 
                  height="1080"
                  :caption="''" />
                </x-galleries.gallery-link-image>
              </div>
            @endif
            @if (isset($contact->publishedImages[1]))
              <div class="grid-area-b aspect-ratio-c">
                <x-galleries.gallery-link-image :image="$contact->publishedImages[1]" :caption="''">
                  <x-image 
                  :maxSizes="[1024 => 900, 0 => 1200]" 
                  :image="$contact->publishedImages[1]" 
                  width="1600" 
                  height="1080"
                  :caption="''" />
                </x-galleries.gallery-link-image>
              </div>
            @endif
            @if (isset($contact->publishedImages[2]))
              <div class="grid-area-c aspect-ratio-c">
                <x-galleries.gallery-link-image :image="$contact->publishedImages[2]" :caption="''">
                  <x-image 
                  :maxSizes="[1024 => 900, 0 => 1200]" 
                  :image="$contact->publishedImages[2]" 
                  width="1600" 
                  height="1080"
                  :caption="''" />
                </x-galleries.gallery-link-image>
              </div>
            @endif
        </div>
      </div>
    @endif
  </div>
@endif
@endsection