@include('layout.partials.head')
<body>
@include('partials.header')
@include('partials.menu')
<main role="main" class="site">
  <div>@yield('content')</div>
</main>
<script src="{{ mix('assets/js/app.js') }}" type="text/javascript"></script>
@if (request()->routeIs('page.member'))
<script src="{{ mix('assets/js/member.js') }}" type="text/javascript"></script>
@endif
</body>
<!-- made with ❤ by bivgrafik.ch & marceli.to -->
</html>