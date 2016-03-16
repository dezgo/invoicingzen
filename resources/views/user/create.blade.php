@extends('web')

@section('content')
    <h1 align="left">Create User</h1>

    {!! Form::open(['route' => 'user.store']) !!}
        @include('user.form', ['submitButtonText' => 'Save'])
    {!! Form::close() !!}

    @include('errors.list')
@stop
