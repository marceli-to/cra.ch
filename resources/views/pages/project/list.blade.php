@extends('layout.web')
@section('seo_title', 'Werkliste')
@section('seo_description', )
@section('content')
@if ($projects)
  <x-project.worklist-sm :projects="$projects" />
@endif
@endsection