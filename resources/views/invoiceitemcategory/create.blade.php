@extends('web')

@section('content')
    <h1 align="left">Create Invoice Item Category</h1>

    {!! Form::open(['route' => 'invoice_item_category.store']) !!}
        @include('invoiceitemcategory.form', ['submitButtonText' => 'Save'])
    {!! Form::close() !!}

    @include('errors.list')
@stop
