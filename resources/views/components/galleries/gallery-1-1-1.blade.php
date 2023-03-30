@props(['items'])
<div class="grid-area-a aspect-ratio-f">
  @if ($items[0] && $items[0]->isImage)
    <x-image :maxSizes="[1024 => 1200, 0 => 1600]" :image="$items[0]->image" width="1080" height="1600" />
  @endif
</div>
<div class="grid-area-b aspect-ratio-f">
  @if ($items[1] && $items[1]->isImage)
    <x-image :maxSizes="[1024 => 1200, 0 => 1600]" :image="$items[1]->image" width="1080" height="1600" />
  @endif
</div>
<div class="grid-area-c aspect-ratio-f">
  @if ($items[2] && $items[2]->isImage)
    <x-image :maxSizes="[1024 => 1200, 0 => 1600]" :image="$items[2]->image" width="1080" height="1600" />
  @endif
</div>