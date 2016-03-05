@extends('master')

@section('content')
    <h1 align="left">Edit Invoice</h1>

    {!! Form::model($invoice, ['method' => 'PUT', 'url' => 'invoice/'.$invoice->id]) !!}
        @include('invoice.form', ['submitButtonText' => 'Update', 'invoice_id' => $invoice->id])
    {!! Form::close() !!}

    @include('errors.list')

    @include('/invoiceitem/index')

@stop
