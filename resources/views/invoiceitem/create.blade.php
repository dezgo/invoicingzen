@extends('master')

@section('content')
    <h1 align="left">Create Invoice Item for invoice {{ $invoice_item->invoice->description }}</h1>

    {!! Form::model($invoice_item, ['route' => ['invoice_item.store', $invoice_item]]) !!}
        @include('invoiceitem.form', ['submitButtonText' => 'Save', 'invoice_id' => $invoice_item->invoice->id])
    {!! Form::close() !!}

    @include('errors.list')
@stop
