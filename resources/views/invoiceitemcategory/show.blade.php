@extends('web')

@section('content')
    <h1 align="left">Show Invoice Item Category</h1>

    {!! Form::model($invoice_item_category, ['method' => 'GET', 'route' => ['invoice_item_category.edit', $invoice_item_category->id]]) !!}
        <?php $options['disabled'] = 'true'; ?>
        @include('invoiceitemcategory.form', ['submitButtonText' => 'Edit'])
    {!! Form::close() !!}
@stop
