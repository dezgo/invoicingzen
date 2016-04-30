@extends('web')

@section('content')
@include('user.sidebar')
<div class="form-group">
    {!! Form::label('subscription', 'Subscription:', ['class' => 'control-label']) !!}
    {{ Form::text('subscription', null, $options) }}
</div>
@stop
