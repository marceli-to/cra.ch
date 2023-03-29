<nav class="site" data-menu>
  <ul>
    <li>
      <a href="" title="Projekte">
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
      <a href="" title="Über uns">
        Über uns
      </a>
    </li>
    <li>
      <a href="" title="Kontakt">
        Kontakt
      </a>
    </li>
  </ul>
</nav>
