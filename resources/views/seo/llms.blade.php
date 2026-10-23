{{-- Plain text, not HTML: raw output so "&" stays "&" --}}
@php($organization = config('seo.organization'))
# {!! config('seo.title') !!}

> {!! config('seo.description') !!}

{!! $organization['name'] !!}, {!! $organization['street'] !!}, {!! $organization['postal_code'] !!} {!! $organization['city'] !!}. Telefon {!! $organization['phone'] !!}, {!! $organization['email'] !!}.

## Seiten

- [Startseite]({!! route('page.home') !!}): Ausgewählte Projekte
- [Werkliste]({!! route('page.worklist') !!}): Alle Projekte mit Art, Ort und Zeitraum
- [Leistungen]({!! route('page.service') !!})
- [Team]({!! route('page.about.team') !!})
- [Tagebuch]({!! route('page.about.diary') !!})
- [Kontakt]({!! route('page.contact') !!})
@if ($projects->isNotEmpty())

## Projekte

@foreach ($projects as $project)
- [{!! html_entity_decode($project->title, ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}]({!! route('page.project.show', $project) !!}){!! collect([$project->type, $project->location, $project->periode])->filter()->whenNotEmpty(fn ($facts) => ': ' . $facts->implode(', '), fn () => '') !!}
@endforeach
@endif
