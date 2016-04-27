@extends('web')

@section('content')
{!! Form::model($email, ['route' => 'email.send']) !!}

{{ Form::hidden('receiver_id') }}
{{ Form::hidden('invoice_id') }}

<div class="form-group">
    {!! Form::label('from', 'From:', ['class' => 'control-label']) !!}
    {{ Form::text('from', null, ['class' => 'form-control', 'disabled' => 'true']) }}
</div>

<div class="form-group">
    {!! Form::label('to', 'To:', ['class' => 'control-label']) !!}
    {{ Form::text('to', null, ['class' => 'form-control', 'disabled' => 'true']) }}
</div>

<div class="form-group">
    {!! Form::label('cc', 'Cc:', ['class' => 'control-label']) !!}
    {{ Form::text('cc', null, ['class' => 'form-control']) }}
</div>

<div class="form-group">
    {!! Form::label('bcc', 'Bcc:', ['class' => 'control-label']) !!}
    {{ Form::text('bcc', null, ['class' => 'form-control']) }}
</div>

<div class="form-group">
    {!! Form::label('subject', 'Subject:', ['class' => 'control-label']) !!}
    {{ Form::text('subject', null, ['class' => 'form-control', 'autofocus' => 'true']) }}
</div>

<div class="form-group">
    {!! Form::label('body', 'Body:', ['class' => 'control-label']) !!}
    {{ Form::textarea('body', null, ['class' => 'form-control']) }}
</div>

{!! Form::submit('Send', ['id' => 'btnSend', 'class' => 'btn btn-success']) !!}

{!! Form::close() !!}

@include('errors.list')

@stop

@section('footer')
<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'body' );
    </script>
@stop
