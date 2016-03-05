@extends('master')

@section('content')
    <h1 align="left">Delete Invoice</h1>

    {!! Form::model($invoice, ['method' => 'DELETE', 'url' => 'invoice/'.$invoice->id]) !!}

    <?php
    $options['disabled'] = 'true';
    ?>
    @include('invoice.form', ['submitButtonText' => 'Delete'])
    {!! Form::close() !!}
@stop
