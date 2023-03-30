@props(['items'])
<div class="grid-area-a aspect-ratio-e">
  @if ($items[0] && $items[0]->isImage)
    <x-image :maxSizes="[1024 => 1600, 0 => 1200]" :image="$items[0]->image" width="1600" height="1080" />
  @endif
</div>
<div class="grid-area-b aspect-ratio-c">
  @if ($items[1] && $items[1]->isImage)
    <x-image :maxSizes="[1024 => 800, 0 => 1200]" :image="$items[1]->image" width="1600" height="1080" />
  @endif
</div>
<div class="grid-area-c aspect-ratio-c">
  @if ($items[2] && $items[2]->isImage)
    <x-image :maxSizes="[1024 => 800, 0 => 1200]" :image="$items[2]->image" width="1600" height="1080" />
  @endif
</div>