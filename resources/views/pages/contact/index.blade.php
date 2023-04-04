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
        </div>
      </article>
    </div>

    
    @if ($contact->publishedImages)
      <div class="md:span-4">
        @foreach($contact->publishedImages as $image)
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
@endif
@endsection