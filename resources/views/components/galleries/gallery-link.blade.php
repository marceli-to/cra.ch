@props(['item', 'view', 'caption' => null])
@if ($view == 'home')
  <x-galleries.gallery-link-project :project="$item->project">
    {{ $slot }}
  </x-galleries.gallery-link-project>
@else
  <x-galleries.gallery-link-image :image="$item->image" :caption="$caption">
    {{ $slot }}
  </x-galleries.gallery-link-image>
@endif
