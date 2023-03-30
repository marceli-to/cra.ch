@props(['items'])
<div class="grid-area-a aspect-ratio-a">
  @if ($items[0] && $items[0]->isImage)
    <x-image :maxSizes="[1440 => 2600, 1024 => 2000, 0 => 1200]" :image="$items[0]->image" width="1600" height="1080" />
  @endif
</div>