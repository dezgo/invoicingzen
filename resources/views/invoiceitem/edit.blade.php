@extends('web')

@section('content')
    <h1 align="left">Edit Invoice Item for invoice {{ $invoice_item->invoice->description }}</h1>

    {!! Form::model($invoice_item, ['method' => 'PUT', 'url' => 'invoice_item/'.$invoice_item->id]) !!}
        @include('invoiceitem.form', ['submitButtonText' => 'Update', 'invoice_id' => $invoice_item->invoice->id])
    {!! Form::close() !!}

    @include('errors.list')
@stop
