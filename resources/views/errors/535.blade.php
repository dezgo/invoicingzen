@extends('errors.errors')

@section('title')
    Email Error
@stop

@section('message')
<b>Something went wrong with your email. Please check your settings to ensure they
are correct. Here's the error message in case it helps:<br /></b>
<br />
<i>{{ $message }}</i>
@stop
