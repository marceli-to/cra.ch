@props(['items'])
<div class="grid-area-a aspect-ratio-c">
  <figure>
  </figure>
</div>
<div class="grid-area-b aspect-ratio-c">
  <figure>
  </figure>
</div>
<div class="grid-area-c aspect-ratio-e">
  @if ($items[0] && $items[0]->isImage)
    <figure>
      <x-image 
        :maxSizes="[1600 => 2000, 1200 => 1500, 900 => 1200, 0 => 900]" 
        width="1600"
        height="900"
        :image="$items[0]->image"
      />
    </figure>
  @endif
</div>
