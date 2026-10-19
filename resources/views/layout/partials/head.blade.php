@php
// The short section form escapes its value, and the tags below escape again
$yield = fn (string $section) => trim(html_entity_decode($__env->yieldContent($section), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
$seoTitle = $yield('seo_title') ? $yield('seo_title') . ' • ' . config('seo.title') : config('seo.title');
$seoDescription = \App\Support\Seo::description($yield('seo_description') ?: config('seo.description'));
$ogImage = $yield('og_image');
$ogImageAlt = $yield('og_image_alt') ?: config('seo.image_alt');
$canonical = url()->current();
$organization = config('seo.organization');
$jsonLd = [
  '@context' => 'https://schema.org',
  '@type' => 'ProfessionalService',
  '@id' => url('/') . '/#organization',
  'name' => $organization['name'],
  'url' => url('/') . '/',
  'logo' => asset('android-chrome-512x512.png'),
  'image' => asset(config('seo.image')),
  'telephone' => $organization['phone'],
  'email' => $organization['email'],
  'address' => [
    '@type' => 'PostalAddress',
    'streetAddress' => $organization['street'],
    'postalCode' => $organization['postal_code'],
    'addressLocality' => $organization['city'],
    'addressCountry' => $organization['country'],
  ],
];
@endphp
<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
@hasSection('noindex')
<meta name="robots" content="noindex">
@else
<link rel="canonical" href="{{ $canonical }}">
@endif
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('seo.title') }}">
<meta property="og:locale" content="de_CH">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
@if ($ogImage)
<meta property="og:image" content="{{ $ogImage }}">
@else
<meta property="og:image" content="{{ asset(config('seo.image')) }}">
@endif
@if (!$ogImage || $yield('og_image_1200x630'))
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
@endif
<meta property="og:image:alt" content="{{ $ogImageAlt }}">
<meta name="twitter:card" content="summary_large_image">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ffffff">
<link rel="sitemap" type="application/xml" href="{{ route('sitemap') }}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="format-detection" content="telephone=no">
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@vite('resources/sass/web/app.scss')
@stack('head')
</head>
<body>