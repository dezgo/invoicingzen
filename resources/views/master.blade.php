<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="Invoicing Zen is a simple Australian-based invoicing system for small to medium-sized businesses." />
    <title>Invoicing Zen - A simple Australian-based invoicing system</title>
    <link href="{{ secure_url('/css/all.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Merriweather:300' rel='stylesheet' type='text/css'>
    <script src="{{ secure_url('/js/all.js') }}"></script>
    @yield('head')
</head>

@yield('bodytag')
    @if(Gate::check('authenticated'))
        @include('topbar')
        <div class="container">
    @else
        <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class='freetext'>Invoicing Zen</h1>
                <br />
            </div>
        </div>
    @endif
        @yield('content')
        @yield('footer')
        @yield('footer-web')
    </div>
</body>
</html>
