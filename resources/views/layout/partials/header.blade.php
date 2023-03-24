<header class="site-header">
  <a href="javascript:;" class="btn-menu js-menu-btn">
    {{-- 
      <x-icon type="burger" />
      <x-icon type="cross" />
    --}}
  </a>
  <a href="{{ route('page.home') }}" class="logo" title="{{ __('Home') }}">
    <x-icons.logo />
  </a>
  @include('menu.global')
</header>
