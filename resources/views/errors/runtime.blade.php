@extends('errors.errors')

@section('title')
    Captain, we have a problem
@stop

@section('message')
<b>We had some issues with that last request. Here's what the server has to say:<br /></b>
<br />
<i>{{ $message }}</i>
@stop
