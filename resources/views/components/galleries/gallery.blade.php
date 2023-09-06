@props(['layout', 'items', 'article' => null, 'view'])
<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-6 grid-gallery-' . $layout]) }}>
  @if ($layout == '1')
    <x-galleries.gallery-1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1-1')
    <x-galleries.gallery-1-1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1-1-1')
    <x-galleries.gallery-1-1-1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1w-1w')
    <x-galleries.gallery-1w-1w :items="$items" :view="$view" />
  @endif

  @if ($layout == '1w-1w-1w')
    <x-galleries.gallery-1w-1w-1w :items="$items" :view="$view" />
  @endif

  @if ($layout == '2-1')
    <x-galleries.gallery-2-1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1-2')
    <x-galleries.gallery-1-2 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1_1-2')
    <x-galleries.gallery-1_1-2 :items="$items" :view="$view" />
  @endif

  @if ($layout == '2-1_1')
    <x-galleries.gallery-2-1_1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1-1_1')
    <x-galleries.gallery-1-1_1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1_1-1')
    <x-galleries.gallery-1_1-1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1-1-1_1')
    <x-galleries.gallery-1-1-1_1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1_1-1-1')
    <x-galleries.gallery-1_1-1-1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1-1_1-1')
    <x-galleries.gallery-1-1_1-1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1_1-1_1-1')
    <x-galleries.gallery-1_1-1_1-1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1_1-1-1_1')
    <x-galleries.gallery-1_1-1-1_1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1-1_1-1_1')
    <x-galleries.gallery-1-1_1-1_1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1t_1-1_1-1')
    <x-galleries.gallery-1t_1-1_1-1 :items="$items" :view="$view" :article="$article" />
  @endif

  @if ($layout == '1sq-1sq')
    <x-galleries.gallery-1sq-1sq :items="$items" :view="$view" />
  @endif

  @if ($layout == '1sq-1')
    <x-galleries.gallery-1sq-1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1-1sq')
    <x-galleries.gallery-1-1sq :items="$items" :view="$view" />
  @endif

  @if ($layout == '1sq-1sq-1sq')
    <x-galleries.gallery-1sq-1sq-1sq :items="$items" :view="$view" />
  @endif

  @if ($layout == '1sq-1sq_1sq')
    <x-galleries.gallery-1sq-1sq_1sq :items="$items" :view="$view" />
  @endif

  @if ($layout == '1sq_1sq-1sq')
    <x-galleries.gallery-1sq_1sq-1sq :items="$items" :view="$view" />
  @endif

  @if ($layout == '1sq-1_1_1')
    <x-galleries.gallery-1sq-1_1_1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1_1_1-1sq')
    <x-galleries.gallery-1_1_1-1sq :items="$items" :view="$view" />
  @endif

  @if ($layout == '1sq-1_1')
    <x-galleries.gallery-1sq-1_1 :items="$items" :view="$view" />
  @endif

  @if ($layout == '1_1-1sq')
    <x-galleries.gallery-1_1-1sq :items="$items" :view="$view" />
  @endif

</div>
