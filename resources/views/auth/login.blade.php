@extends('layout.guest')
@section('seo_title', 'Login')
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