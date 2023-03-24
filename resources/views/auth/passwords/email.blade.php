@extends('layout.guest')
@section('seo_title', 'Passwort vergessen')
@section('content')
<h1>Passwort vergessen</h1>
<div class="mb-6x">{{__('Halb so wild - alles was wir brauchen ist Ihre E-Mail und Sie erhalten einen Link um das Passwort zurücksetzen zu können.')}}</div>
@if ($errors->any())
  <x-alert type="danger" message="{{__('messages.Bitte alle markierten Felder prüfen!')}}" />
@endif
@if (session('status'))
  <x-alert type="success" message="{{ session('status') }}" />
@endif
<form method="POST" action="{{ route('password.email') }}">
  @csrf
  <x-text-field type="email" name="email" placeholder="mail@beispiel.ch" required />
  <div class="form-action">
    <x-button label="Senden" name="register" btnClass="btn-primary" type="submit" />
    <a href="{{ route('login') }}" class="form-helper">Zurück</a>
  </div>
</form>
@endsection