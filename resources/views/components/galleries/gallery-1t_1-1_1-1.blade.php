@props(['items', 'article', 'view'])
<div class="grid-area-a aspect-ratio-c">
  <x-article :article="$article" isDiary="true" />
</div>
<div class="grid-area-b aspect-ratio-c">
  @if ($items[0] && $items[0]->isImage)
    @php $caption = $view == 'home' && $items[0]->isProject ? $items[0]->project->title : $items[0]->image->caption; @endphp
    <x-galleries.gallery-link :item="$items[0]" :view="$view">
      <x-image 
        :maxSizes="[1024 => 900, 0 => 1200]" 
        :image="$items[0]->image" 
        width="1600" 
        height="1080"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>
<div class="grid-area-c aspect-ratio-c">
  @if ($items[1] && $items[1]->isImage)
    @php $caption = $view == 'home' && $items[1]->isProject ? $items[1]->project->title : $items[1]->image->caption; @endphp
    <x-galleries.gallery-link :item="$items[1]" :view="$view" :caption="$caption">
      <x-image 
        :maxSizes="[1024 => 900, 0 => 1200]" 
        :image="$items[1]->image" 
        width="1600" 
        height="1080"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>
<div class="grid-area-d aspect-ratio-c">
  @if ($items[2] && $items[2]->isImage)
    @php $caption = $view == 'home' && $items[2]->isProject ? $items[2]->project->title : $items[2]->image->caption; @endphp
    <x-galleries.gallery-link :item="$items[2]" :view="$view" :caption="$caption">
      <x-image 
        :maxSizes="[1024 => 900, 0 => 1200]" 
        :image="$items[2]->image" 
        width="1600" 
        height="1080"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>
<div class="grid-area-e aspect-ratio-b">
  @if ($items[3] && $items[3]->isImage)
    @php $caption = $view == 'home' && $items[3]->isProject ? $items[3]->project->title : $items[3]->image->caption; @endphp
    <x-galleries.gallery-link :item="$items[3]" :view="$view" :caption="$caption">
      <x-image 
        :maxSizes="[0 => 1200]" 
        :image="$items[3]->image" 
        width="1080" 
        height="1600"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>