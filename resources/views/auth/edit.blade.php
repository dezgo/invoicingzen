@extends('web')

@section('content')
@if (Session::has('status'))
<div class="alert alert-success alert-dismissible" role="alert" id="success-alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ Session::get('status') }}
</div>
@endif
    <h1 align="left">Edit Profile</h1>
    {!! Form::model($user, ['method' => 'POST', 'url' => '/profile/edit']) !!}

        <div class="form-group">
            {{ Form::label('name', 'Name:', ['class' => 'control-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('email', 'Email Address:', ['class' => 'control-label']) }}
            {{ Form::text('email', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                Update
            </button>
        </div>

    {!! Form::close() !!}

    <div class="panel panel-default">
      <div class="panel-body">
        To change your password, logout and select the 'forgot your password' link
      </div>
    </div>

    @include('errors.list')
@stop

@section('footer')
<script language="Javascript">
$(document).ready (function(){
    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
        $("#success-alert").alert('close');
    });
});
</script>
@stop
