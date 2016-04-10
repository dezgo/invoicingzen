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

    <h1 align="left">{{ trans('settings.title') }}</h1>

    {!! Form::open(['route' => 'settings.update', 'files' => true]) !!}

    <div class="form-group">
        {!! Form::label('next_invoice_number', trans('settings.next_invoice_number'), ['class' => 'control-label']) !!}
        {{ Form::text('next_invoice_number', Setting::get('next_invoice_number', '1'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('markup', trans('settings.markup'), ['class' => 'control-label']) !!}
        {{ Form::text('markup', Setting::get('markup'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('bsb', trans('settings.bsb'), ['class' => 'control-label']) !!}
        {{ Form::text('bsb', Setting::get('bsb'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('bank_account_number', trans('settings.bank_account_number'), ['class' => 'control-label']) !!}
        {{ Form::text('bank_account_number', Setting::get('bank_account_number'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('abn', trans('settings.abn'), ['class' => 'control-label']) !!}
        {{ Form::text('abn', Setting::get('abn'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('payment_terms', trans('settings.payment_terms'), ['class' => 'control-label']) !!}
        {{ Form::text('payment_terms', Setting::get('payment_terms'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('mailing_address_line_1', trans('settings.mailing_address_line_1'), ['class' => 'control-label']) !!}
        {{ Form::text('mailing_address_line_1', Setting::get('mailing_address_line_1'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('mailing_address_line_2', trans('settings.mailing_address_line_2'), ['class' => 'control-label']) !!}
        {{ Form::text('mailing_address_line_2', Setting::get('mailing_address_line_2'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('mailing_address_line_3', trans('settings.mailing_address_line_3'), ['class' => 'control-label']) !!}
        {{ Form::text('mailing_address_line_3', Setting::get('mailing_address_line_3'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('enquiries_phone', trans('settings.enquiries_phone'), ['class' => 'control-label']) !!}
        {{ Form::text('enquiries_phone', Setting::get('enquiries_phone'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('enquiries_email', trans('settings.enquiries_email'), ['class' => 'control-label']) !!}
        {{ Form::text('enquiries_email', Setting::get('enquiries_email'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('enquiries_web', trans('settings.enquiries_web'), ['class' => 'control-label']) !!}
        {{ Form::text('enquiries_web', Setting::get('enquiries_web'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('logo', trans('settings.logo'), ['class' => 'control-label']) !!}
        <small>Expecting JPEG format</small>
        {{ Form::file('logo') }}
    </div>

    <div class="form-group">
        {!! Form::label('email_signature', trans('settings.email_signature'), ['class' => 'control-label']) !!}
        {{ Form::textarea('email_signature', Setting::get('email_signature'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('email_host', trans('settings.email_host'), ['class' => 'control-label']) !!}
        {{ Form::text('email_host', Setting::get('email_host'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('email_port', trans('settings.email_port'), ['class' => 'control-label']) !!}
        {{ Form::text('email_port', Setting::get('email_port'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('email_username', trans('settings.email_username'), ['class' => 'control-label']) !!}
        {{ Form::text('email_username', Setting::get('email_username'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('email_password', trans('settings.email_password'), ['class' => 'control-label']) !!}
        <input type='password' id='email_password' name='email_password' value='{{ Setting::get('email_password') }}' class='form-control' />
    </div>

    <div class="form-group">
        {!! Form::label('email_encryption', trans('settings.email_encryption'), ['class' => 'control-label']) !!}
        {{ Form::text('email_encryption', Setting::get('email_encryption'), ['class' => 'form-control']) }}
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

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'email_signature' );
    </script>
@stop
