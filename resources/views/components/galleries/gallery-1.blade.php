@props(['items', 'view'])
<div class="grid-area-a aspect-ratio-a">
  @if ($items[0] && $items[0]->isImage)
    @php $caption = \AppHelper::caption($items[0], $view); @endphp
    <x-galleries.gallery-link :item="$items[0]" :view="$view" :caption="$caption">
      <x-image 
        :maxSizes="[1440 => 2600, 1024 => 2000, 0 => 1200]" 
        :image="$items[0]->image" 
        width="1600" 
        height="1080"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>