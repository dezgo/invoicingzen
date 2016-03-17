<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Invoicing Zen</title>
    <link href="{{ url('/css/all.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Merriweather:300' rel='stylesheet' type='text/css'>
    <script src="{{ url('/js/all.js') }}"></script>
    @yield('head')
</head>

@yield('bodytag')
    @yield('topbar')
    <div class="container">
        @if(!Gate::check('authenticated'))
            <h1 class='freetext'>Invoicing Zen</h1>
        @endif
        @yield('content')
        @yield('footer')
        @yield('footer-web')
    </div>
</body>
</html>
