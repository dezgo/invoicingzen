@extends('web')

@section('content')
<div class="panel">
    <h1 align="left">
@yield('title')
    </h1>

    <p>
@yield('message')
    </p>
</div>
@stop
