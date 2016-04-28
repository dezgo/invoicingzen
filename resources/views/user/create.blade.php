@extends('web')

@section('content')
@include('user.sidebar')
<br />
            <h1 align="left">Create User</h1>

            {!! Form::open(['route' => 'user.store']) !!}
                @include('user.form', ['submitButtonText' => 'Save'])
            {!! Form::close() !!}

            @include('errors.list')
@stop
