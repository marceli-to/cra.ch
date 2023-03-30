@props(['items'])
<div class="grid-area-a aspect-ratio-f">
  @if ($items[0] && $items[0]->isImage)
    <x-image :maxSizes="[0 => 1200]" :image="$items[0]->image" width="1080" height="1600" />
  @endif
</div>
<div class="grid-area-b aspect-ratio-e">
  @if ($items[1] && $items[1]->isImage)
    <x-image :maxSizes="[0 => 1600]" :image="$items[1]->image" width="1600" height="1080" />
  @endif
</div>