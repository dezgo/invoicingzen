@extends('web')

@section('content')
    <h1 align="left">Deactivate Account</h1>

    WARNING: Continuing will permanently deactivate your account and you will no
    longer be able to login. To reverse this process, you will need to contact
    support.<br />
    <br />
    Note: You will not be able to create a new account with the same email address.<br />
    <br />
    {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id]]) !!}<br />
    {!! Form::submit('CONFIRM DEACTIVATION', ['id' => 'btnConfirmDeactivation', 'class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop
