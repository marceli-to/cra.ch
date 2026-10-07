@extends('layout.guest')
@section('seo_title', 'Login')
@if ($splash)
@push('head')
@php
  // Plain url() first, for browsers without image-set() type()
  $background = function (int $size) use ($splash) {
    $set = collect(\App\Support\ImageSupport::modernFormats())
      ->map(fn ($format) => "url('{$splash->url($size, $format)}') type('image/{$format}')")
      ->push("url('{$splash->url($size)}')")
      ->implode(', ');

    return "background-image: url('{$splash->url($size)}'); background-image: image-set({$set});";
  };
@endphp
<style>
body.auth { {!! $background(2400) !!} background-position: center; background-size: cover; }
@media (max-width: 1200px) { body.auth { {!! $background(1200) !!} } }
</style>
@endpush
@endif
@section('content')
@if ($errors->any())
  <x-alert type="danger" message="{{__('Bitte alle markierten Felder prüfen!')}}" />
@endif
<form method="POST" action="{{ route('login') }}">
  @csrf
  <x-text-field type="email" name="email" required autocomplete="false" aria-autocomplete="false" label="E-Mail" />
  <x-text-field type="password" name="password" required autocomplete="false" label="Passwort" />
  <div class="form-action">
    <x-button label="Anmelden" name="register" btnClass="btn-primary" type="submit" />
    @if (Route::has('password.request'))
      <a href="{{ route('password.request') }}" class="form-helper">Passwort vergessen?</a>
    @endif
  </div>
</form>
@endsection