<header class="site-header">
  <div>
    <a href="{{ route('page.home') }}" class="logo" title="{{ __('Home') }}">
      <x-icons.logo />
    </a>
    <a href="javascript:;" class="btn-menu" data-menu-btn title="Menu anzeigen/verbergen">
      <x-icons.burger class="icon-menu-burger" />
      <x-icons.cross class="icon-menu-cross" />
    </a> 
    @include('layout.partials.menu')
   </div>
</header>
