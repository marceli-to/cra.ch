@props(['items'])
<div class="grid-area-a aspect-ratio-g">
  @if ($items[0] && $items[0]->isImage)
    <x-image :maxSizes="[1024 => 1000, 0 => 1600]" :image="$items[0]->image" width="1600" height="1080" />
  @endif
</div>
<div class="grid-area-b aspect-ratio-g">
  @if ($items[1] && $items[1]->isImage)
    <x-image :maxSizes="[1024 => 1000, 0 => 1600]" :image="$items[1]->image" width="1080" height="1600" />
  @endif
</div>
<div class="grid-area-c aspect-ratio-g">
  @if ($items[1] && $items[1]->isImage)
    <x-image :maxSizes="[1024 => 1000, 0 => 1600]" :image="$items[1]->image" width="1080" height="1600" />
  @endif
</div>