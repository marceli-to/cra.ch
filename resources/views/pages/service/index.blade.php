@extends('layout.web')
@section('seo_title', 'Leistungen')
@section('seo_description', '')
@section('content')
@if ($service)
  <div class="md:grid md:grid-cols-6 md:grid-gap service">

    @if ($service->column_one)
      <div class="md:span-2">
        <article class="stacked-item stacked-item--border stacked-item--indent">
          <div>
            {!! $service->column_one !!}
          </div>
        </article>
      </div>
    @endif

    @if ($service->column_two)
      <div class="md:span-2">
        <article class="stacked-item stacked-item--border stacked-item--indent">
          <div>
            {!! $service->column_two !!}
          </div>
        </article>
      </div>
    @endif
    
    @if ($service->publishedImages)
      <div class="md:span-2">
        @foreach($service->publishedImages as $image)
          <x-image 
            classes="is-responsive mb-8x md:mb-16x"
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