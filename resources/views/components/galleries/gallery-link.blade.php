@props(['item', 'view', 'caption' => null])
@if ($view == 'home')
  @if ($item->isProject)
    <x-galleries.gallery-link-project :project="$item->project" :caption="$caption">
      {{ $slot }}
    </x-galleries.gallery-link-project>
  @endif
  @if ($item->isPage)
    <x-galleries.gallery-link-page :page="$item->page" :caption="$caption">
      {{ $slot }}
    </x-galleries.gallery-link-page>
  @endif
@else
  <x-galleries.gallery-link-image :image="$item->image" :caption="$caption">
    {{ $slot }}
  </x-galleries.gallery-link-image>
@endif
