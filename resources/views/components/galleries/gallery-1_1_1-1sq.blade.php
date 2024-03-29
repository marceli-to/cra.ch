@props(['items', 'view'])
<div class="grid-area-a aspect-ratio-c">
  @if ($items[0] && $items[0]->isImage)
    @php $caption = \AppHelper::caption($items[0], $view); @endphp
    <x-galleries.gallery-link :item="$items[0]" :view="$view" :caption="$caption">
      <x-image 
        :maxSizes="[1024 => 1200, 0 => 1600]" 
        :image="$items[0]->image" 
        width="1600" 
        height="1080"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>
<div class="grid-area-b aspect-ratio-c">
  @if ($items[1] && $items[1]->isImage)
    @php $caption = \AppHelper::caption($items[1], $view); @endphp
    <x-galleries.gallery-link :item="$items[1]" :view="$view" :caption="$caption">
      <x-image 
        :maxSizes="[1024 => 1200, 0 => 1600]" 
        :image="$items[1]->image" 
        width="1600" 
        height="1080"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>
<div class="grid-area-c aspect-ratio-c">
  @if ($items[2] && $items[2]->isImage)
    @php $caption = \AppHelper::caption($items[2], $view); @endphp
    <x-galleries.gallery-link :item="$items[2]" :view="$view" :caption="$caption">
      <x-image 
        :maxSizes="[1024 => 1600, 0 => 1200]" 
        :image="$items[2]->image" 
        width="1600" 
        height="1080"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>
<div class="grid-area-d aspect-ratio-h">
  @if ($items[2] && $items[2]->isImage)
    @php $caption = \AppHelper::caption($items[2], $view); @endphp
    <x-galleries.gallery-link :item="$items[2]" :view="$view" :caption="$caption">
      <x-image 
        :maxSizes="[1024 => 1600, 0 => 1200]" 
        :image="$items[2]->image" 
        width="1080" 
        height="1080"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>