@props(['items', 'article' => null, 'view'])
<div class="grid-area-a aspect-ratio-e">
  @if ($items[0] && $items[0]->isImage)
    @php $caption = $view == 'home' && $items[0]->isProject ? $items[0]->project->title : $items[0]->image->caption; @endphp
    <x-galleries.gallery-link :item="$items[0]" :view="$view">
      <x-image 
        :maxSizes="[1024 => 1600, 0 => 1200]" 
        :image="$items[0]->image" 
        width="1600" 
        height="1080" 
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
</div>

<div class="grid-area-b aspect-ratio-c">
  @if ($items[1] && $items[1]->isImage)
    @php $caption = $view == 'home' && $items[1]->isProject ? $items[1]->project->title : $items[1]->image->caption; @endphp
    <x-galleries.gallery-link :item="$items[1]" :view="$view">
      <x-image 
        :maxSizes="[1024 => 900, 0 => 1200]" 
        :image="$items[1]->image" 
        width="1600" 
        height="1080"
        :caption="$caption" />
    </x-galleries.gallery-link>
  @endif
  @if ($items[1] && $items[1]->isArticle)
    <x-article :article="$items[1]->article" />
  @endif
</div>

<div class="grid-area-c aspect-ratio-c">
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
  @if ($items[2] && $items[2]->isArticle)
    <x-article :article="$items[2]->article->articleContent" />
  @endif
</div>