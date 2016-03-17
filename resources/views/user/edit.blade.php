@extends('web')

@section('content')
    <h1 align="left">Edit User</h1>

    {!! Form::model($user, ['method' => 'PUT', 'route' => ['user.update', $user->id]]) !!}
        @include('user.form', ['submitButtonText' => 'Update'])
    {!! Form::close() !!}

    {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id]]) !!}
    {!! Form::submit('Deactivate', ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}

    @include('errors.list')
@stop
