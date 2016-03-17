@extends('web')

@section('content')
    <h1 align="left">Delete Invoice Item</h1>

    {!! Form::model($invoice_item, ['method' => 'DELETE', 'url' => 'invoice_item/'.$invoice_item->id]) !!}

    <?php $options['disabled'] = 'true'; ?>
    @include('invoiceitem.form', ['submitButtonText' => 'Delete', 'invoice_id' => $invoice_item->invoice->id])
    {!! Form::close() !!}
@stop
