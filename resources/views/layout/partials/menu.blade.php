<nav class="site" data-menu>
  <a href="{{ route('page.home') }}" class="logo" title="{{ __('Home') }}">
    <x-icons.logo />
  </a>
  <div>
    <ul>
      <li>
        <a 
          href="javascript:;" 
          title="Projekte" class="{{ request()->routeIs('page.project*') ? 'is-active is-current' : '' }}" 
          data-menu-parent>
          Projekte
        </a>
        @if ($menuProjects->count() > 0)
          <ul>
            @foreach ($menuProjects as $menuProject)
              <li>
                @if ($menuProject->hasFlag('hasDetailPage'))
                  <a 
                    href="{{ route('page.project.show', ['project' => $menuProject]) }}" 
                    title="{{ $menuProject->title }}"
                    class="{{ isset($project) && $project->id == $menuProject->id ? 'is-active' : '' }}">
                    {{ $menuProject->title }}
                  </a>
                @else
                  <span>{{ $menuProject->title }}</span>
                @endif
              </li>
            @endforeach
          </ul>
        @endif
      </li>
      <li>
        <a href="{{ route('page.service') }}" class="{{ request()->routeIs('page.service') ? 'is-active' : '' }}" title="Leistungen">
          Leistungen
        </a>
      </li>
      <li>
        <a href="{{ route('page.worklist') }}" class="{{ request()->routeIs('page.worklist') ? 'is-active' : '' }}" title="Werkliste">
          Werkliste
        </a>
      </li>
      <li>
        <a href="javascript:;" title="Über uns" class="{{ request()->routeIs('page.about*') ? 'is-active is-current' : '' }}" data-menu-parent>
          Über uns
        </a>
        <ul>
          <li>
            <a href="{{ route('page.about.team') }}" class="{{ request()->routeIs('page.about.team') ? 'is-active' : '' }}" title="Über uns • Team">
              Team
            </a>
          </li>
          @if ($hasDiary)
            <li>
              <a href="{{ route('page.about.diary') }}" class="{{ request()->routeIs('page.about.diary') ? 'is-active' : '' }}" title="Über uns • Tagebuch">
                Tagebuch
              </a>
            </li>
          @endif
        </ul>
      </li>
      <li>
        <a href="{{ route('page.contact') }}" class="{{ request()->routeIs('page.contact') ? 'is-active' : '' }}" title="Kontakt">
          Kontakt
        </a>
      </li>
    </ul>
  </div>
</nav>
