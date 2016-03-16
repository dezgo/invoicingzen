@extends('master')

@section('topbar')
@if(Gate::check('authenticated'))
    @include('topbar')
@endif
@stop

@section('bodytag')
@if(Gate::check('authenticated'))
<body id="app-layout">
@else
<body id="app-layout" class="introimage">
@endif
@stop

@section('content')
    @yield('content')
@stop

@section('footer-web')
<br /><Br /><Br /><Br />
<nav class="navbar navbar-default navbar-fixed-bottom">
    <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li>
                <a href="#"><span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span><small>Invoicing Zen</small></a>
            </li>
            <li>
                <a href="/release-notes">Release Notes</a>
            </li>
        </ul>
    </div>
</nav>
@stop
