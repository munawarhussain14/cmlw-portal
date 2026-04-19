<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="favicon.png" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Give Away Tips') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('assets/plugins/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet"/>
    @stack("styles")
</head>
<body>
  <div class="container">
    @yield('content')
  </div>  

    <script src="{{asset('assets/plugins/bootstrap-5.0.2/js/bootstrap.js')}}"></script>
    @stack("scripts")
</body>
</html>
