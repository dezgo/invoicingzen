@extends('master')

@section('content')
    <h1 align="left">Edit Invoice Item Category</h1>

    {!! Form::model($invoice_item_category, ['method' => 'PUT', 'route' =>
        ['invoice_item_category.update', $invoice_item_category->id]]) !!}
        @include('invoiceitemcategory.form', ['submitButtonText' => 'Update'])
    {!! Form::close() !!}

    @include('errors.list')
@stop
