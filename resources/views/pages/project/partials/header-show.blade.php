<header class="site-header site-header--project">
  <a href="{{ route('page.project.index') }}" title="{{ __('Projekte') }}" class="btn-page">
    <x-icon type="cross" />
    {{ __('Projekte') }}
  </a>
  <div>
    <a href="{{ route('page.home') }}" class="logo logo--project" title="{{ __('Home') }}">
      @include('icons.logo')
    </a>
    <h1 class="sm:hide">{{ $project->title }}</h1>
    <nav class="project">
      <h1 class="xs:hide">{{ $project->title }}</h1>
      <div class="project__meta">
        <a href="{{ route('page.project.index', ['category' => $category->slug]) }}" title="{{ $category->title }}" class="xs:hide">
          {{ $category->title }}
        </a>
        <div class="project__browse">
          @if ($browse['prev'])
            <a href="{{ route('page.project.show', ['category' => $category->slug, 'slug' => AppHelper::slug($browse['prev']->title), 'project' => $browse['prev']->id]) }}">
              <x-icon type="arrow-prev" />
            </a>
          @endif
          @if ($browse['next'])
            <a href="{{ route('page.project.show', ['category' => $category->slug, 'slug' => AppHelper::slug($browse['next']->title), 'project' => $browse['next']->id]) }}">
              <x-icon type="arrow-next" />
            </a>
          @endif
        </div>
        <a href="javascript:;" class="js-btn-project-info" title="{{ __('Info') }}">
          {{ __('Info') }}
        </a>
      </div>
    </nav>
  </div>
</header>