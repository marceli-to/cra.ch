<nav class="site" data-menu>
  <ul>
    <li>
      <a href="javascript:;" title="Projekte" data-menu-parent>
        Projekte
      </a>
        @if ($menuProjects->count() > 0)
          <ul>
            @foreach ($menuProjects as $project)
              <li>
                <a href="" title="{{ $project->title }}">
                  {{ $project->title }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
    </li>
    <li>
      <a href="" title="Leistungen">
        Leistungen
      </a>
    </li>
    <li>
      <a href="" title="Werkliste">
        Werkliste
      </a>
    </li>
    <li>
      <a href="javascript:;" title="Über uns"  class="is-active is-current" data-menu-parent>
        Über uns
      </a>
      <ul>
        <li>
          <a href="" title="Team">
            Team
          </a>
        </li>
        <li>
          <a href="" title="Referenzen">
            Referenzen
          </a>
        </li>
      </ul>
    </li>
    <li>
      <a href="" title="Kontakt">
        Kontakt
      </a>
    </li>
  </ul>
</nav>
