@extends('master')

@section('content')
<div class="panel">
    <h1 align="left">
@yield('title')
    </h1>

    <p>
@yield('message')
    </p>
    <p>
        You could always try the <u><a href='/'>homepage</a></u>.
    </p>
</div>
@stop
