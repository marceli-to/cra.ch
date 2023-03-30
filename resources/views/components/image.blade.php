@if ($maxSizes && $image)
  <picture class="{{ $classes }}">
    @foreach($maxSizes as $minWidth => $maxSize)
      @if ($minWidth > 0)
        <source media="(min-width: {{ $minWidth }}px)" data-srcset="/img/cache/{{ $image->name }}/{{ $maxSize}}/{{ $image->coords }}{{ $ratio ? '/' . $image->ratio : '' }}">
      @else
        <img 
          src="/assets/img/placeholder.png"
          data-src="/img/cache/{{ $image->name }}/{{ $maxSize }}/{{ $image->coords }}{{ $ratio ? '/' . $image->ratio : '' }}"
          width="{{ $width }}" 
          height="{{ $height }}"
          title="{{ $image->caption }}"
          alt="{{ $image->caption }}"
          class="lazy">
      @endif
    @endforeach
    @if ($image->caption)
      <figcaption>{{ $image->caption }}</figcaption>
    @endif
  </picture>
@endif

