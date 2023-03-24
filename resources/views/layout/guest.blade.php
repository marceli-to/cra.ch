@include('layout.partials.head')
<body class="auth">
<main role="main" class="site">
  <div>
    <section class="mx-auto">
      @include('partials.logo')
      @yield('content')
    </section>
  </div>
</main>
<script src="{{ mix('assets/js/app.js') }}" type="text/javascript"></script>
</body>
<!-- made with ❤ by bivgrafik.ch & marceli.to -->
</html>