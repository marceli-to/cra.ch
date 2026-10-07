@if ($maxSizes && $image)
  @php $formats = \App\Support\ImageSupport::modernFormats(); @endphp
  <picture class="{{ $classes }}">
    @foreach($maxSizes as $minWidth => $maxSize)
      {{-- Per breakpoint: the modern formats first, the browser takes the first it supports --}}
      @foreach ($formats as $format)
        <source @if ($minWidth > 0) media="(min-width: {{ $minWidth }}px)" @endif type="image/{{ $format }}" data-srcset="{{ $image->url($maxSize, $format) }}">
      @endforeach
      @if ($minWidth > 0)
        <source media="(min-width: {{ $minWidth }}px)" data-srcset="{{ $image->url($maxSize) }}">
      @else
        <img 
          src="/assets/img/placeholder.png"
          data-src="{{ $image->url($maxSize) }}"
          width="{{ $width }}" 
          height="{{ $height }}"
          title="{{ $image->caption }}"
          alt="{{ $image->caption }}"
          class="lazy">
      @endif
    @endforeach
    
    <figcaption>
      @if ($caption)
        <div>{{ $caption }}</div>
      @endif
    </figcaption>
  </picture>
@endif
