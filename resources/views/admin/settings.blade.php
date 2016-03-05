@extends('master')

@section('content')
@if (Session::has('status'))
<div class="alert alert-success alert-dismissible" role="alert" id="success-alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ Session::get('status') }}
</div>
@endif

    <h1 align="left">{{ trans('settings.title') }}</h1>

    {!! Form::open(['route' => 'settings.update']) !!}

    <div class="form-group">
        {!! Form::label('next_invoice_number', trans('settings.next_invoice_number'), ['class' => 'control-label']) !!}
        {{ Form::text('next_invoice_number', Setting::get('next_invoice_number', '1'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('markup', trans('settings.markup'), ['class' => 'control-label']) !!}
        {{ Form::text('markup', Setting::get('markup'), ['class' => 'form-control']) }}
    </div>

    {!! Form::submit('Update', ['class' => 'btn btn-primary', 'id' => 'btnSubmit']) !!}

    {!! Form::close() !!}

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
