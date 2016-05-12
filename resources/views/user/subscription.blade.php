@extends('web')

@section('content')
@include('user.sidebar')
@include('includes.flash_message_content')
<h1>Your Subscription</h1>
<a class='btn btn-primary' id='link1' href='/subscribe/{{ $next_action[0] }}'>{{ trans('subscription.'.$next_action[0]) }}</a>
@if (array_key_exists(1, $next_action))
&nbsp;&nbsp;&nbsp;
<a class='btn btn-success' id='link2' href='/subscribe/{{ $next_action[1] }}'>{{ trans('subscription.'.$next_action[1]) }}</a>
@endif
<Br /><Br />
<div class="form-group">
    <label for="status" class="control-label">Plan: </label>
    {{ $plan }}
    <br />
    <label for="status" class="control-label">Status: </label>
    {{ trans('subscription.'.$current_status) }}
</div>
@stop
