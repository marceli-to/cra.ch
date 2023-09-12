@extends('layout.web')
@section('seo_title', 'Werkliste')
@section('seo_description', 'Gewinnen Sie Einblicke in unsere faszinierenden Architekturprojekte – Von zeitgenössischen Wohngebäuden bis hin zu beeindruckenden öffentlichen Bauten. Entdecken Sie unsere Vielfalt und Expertise auf unserer Werkliste.')
@section('content')
@if ($projects)
  <x-project.worklist-sm :projects="$projects" />
  <x-project.worklist-lg :projects="$projects" />
@endif
@endsection