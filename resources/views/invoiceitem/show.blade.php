@extends('master')

@section('content')
    <h1 align="left">Show Invoice Item for invoice {{ $invoice_item->invoice->description }}</h1>

    {!! Form::model($invoice_item, ['method' => 'GET', 'url' => 'invoice_item/'.$invoice_item->id.'/edit']) !!}
        <?php $options['disabled'] = 'true'; ?>
        @include('invoiceitem.form', ['submitButtonText' => 'Edit', 'invoice_id' => $invoice_item->invoice->id])
    {!! Form::close() !!}

@stop
