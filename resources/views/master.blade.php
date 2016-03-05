<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoicing Zen</title>
    <link href="{{ url('/css/all.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Merriweather:300' rel='stylesheet' type='text/css'>
    @yield('head')
</head>

<body id="app-layout">
    @if(Gate::check('authenticated'))
        @include('topbar')
    @endif
    <div class="container">
        @if(!Gate::check('authenticated'))
            <h1>Invoicing Zen</h1>
        @endif
        @yield('content')

        <div id="footer">
            <script src="/js/all.js"></script>
            @yield('footer')
            <!-- <br />
            <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span>
            <small>Invoicing Zen</small> -->
        </div>

    </div>
</body>
</html>
