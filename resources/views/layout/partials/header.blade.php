<header class="site-header">
  <div>
    <a href="{{ route('page.home') }}" class="logo" title="{{ __('Home') }}">
      <x-icons.logo />
    </a>
    <a href="javascript:;" class="btn-menu" data-menu-btn>
      <x-icons.burger />
      <x-icons.cross />
    </a> 
    @include('layout.partials.menu')
 
  </div>
</header>
