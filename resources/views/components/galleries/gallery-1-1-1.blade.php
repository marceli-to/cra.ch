@props(['items', 'view'])
<div class="grid-area-a aspect-ratio-f">
  @if ($items[0] && $items[0]->isImage)
  @php $caption = $view == 'home' && $items[0]->isProject ? $items[0]->project->title : $items[0]->image->caption; @endphp
  <x-galleries.gallery-link :item="$items[0]" :view="$view">
    <x-image 
      :maxSizes="[1024 => 1200, 0 => 1600]" 
      :image="$items[0]->image" 
      width="1080" 
      height="1600"
      :caption="$caption" />
  </x-galleries.gallery-link>
  @endif
</div>
<div class="grid-area-b aspect-ratio-f">
  @if ($items[1] && $items[1]->isImage)
    @php $caption = $view == 'home' && $items[1]->isProject ? $items[1]->project->title : $items[1]->image->caption; @endphp
    <x-galleries.gallery-link :item="$items[1]" :view="$view" :caption="$caption">
      <x-image 
        :maxSizes="[1024 => 1200, 0 => 1600]" 
        :image="$items[1]->image" 
        width="1080" 
        height="1600"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>
<div class="grid-area-c aspect-ratio-f">
  @if ($items[2] && $items[2]->isImage)
    @php $caption = $view == 'home' && $items[2]->isProject ? $items[2]->project->title : $items[2]->image->caption; @endphp
    <x-galleries.gallery-link :item="$items[2]" :view="$view" :caption="$caption">
      <x-image 
        :maxSizes="[1024 => 1200, 0 => 1600]" 
        :image="$items[2]->image" 
        width="1080" 
        height="1600"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>