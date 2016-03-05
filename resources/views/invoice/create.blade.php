@extends('master')

@section('content')
    <h1 align="left">Create Invoice</h1>

    {!! Form::model($invoice->toArray(), ['route' => 'invoice.store']) !!}
        @include('invoice.form', ['submitButtonText' => 'Save', 'invoice_id' => $invoice->id])
    {!! Form::close() !!}

    @include('errors.list')
@stop
