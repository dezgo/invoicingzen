@extends('web')

@section('content')

@can('admin')
@if ($user->id == Auth::user()->id)
@include('user.sidebar')
@endif
@endcan

    {!! Form::model($user, ['method' => 'PUT', 'route' => ['user.update', $user->id]]) !!}
        @include('user.form', ['submitButtonText' => 'Update'])
    {!! Form::close() !!}

    <Br />
    <div class="panel panel-default">
      <div class="panel-body">
        To change your password, logout and select the 'forgot your password' link
      </div>
    </div>

    @include('errors.list')
@stop
