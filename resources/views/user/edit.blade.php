@extends('web')

@section('content')
@include('user.sidebar')

    {!! Form::model($user, ['method' => 'PUT', 'route' => ['user.update', $user->id]]) !!}
        @include('user.form', ['submitButtonText' => 'Update'])
    {!! Form::close() !!}

    <Br />
    <a id="btnDeactivate" class="btn btn-danger" href="/user/{{ $user->id }}/delete">Deactivate</a><br />
    <Br />
    <div class="panel panel-default">
      <div class="panel-body">
        To change your password, logout and select the 'forgot your password' link
      </div>
    </div>

    @include('errors.list')
@stop
