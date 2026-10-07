<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ config('seo.title') }} - Administration</title>
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="format-detection" content="telephone=no">
@vite(['resources/sass/cms/app.scss', 'resources/js/cms/app.js'])
</head>
<body>
<div id="app"></div>
</body>
</html>
