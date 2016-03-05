@extends('master')

@section('content')
    <h1>Show Invoice Item Categories</h1>

    <div class="container">
    <div class="row">
        <h4 class="col-md-2">Description</h4>
        <h4 class="col-md-4">Actions</h4>
    </div>
    @foreach($invoice_item_categories as $invoice_item_category)
        <div class="row">
            <div class="col-md-2">
                {{ $invoice_item_category->description }}
            </div>
            <div class="col-md-4">
                <a class="btn btn-success" href="{{ action('InvoiceItemCategoryController@edit', [$invoice_item_category->id]) }}">
                    Edit
                </a>
                <a class="btn btn-primary" href="{{ action('InvoiceItemCategoryController@show', [$invoice_item_category->id]) }}">
                    Details
                </a>
                <a class="btn btn-danger" href="{{ url('/invoice_item_category/'.$invoice_item_category->id.'/delete') }}">
                    Delete
                </a>
            </div>
        </div>
        <!-- add this empty row to get a 1 pixel separator between buttons on each row -->
        <div class="row"><div class="col-md-6"></div></div>
    @endforeach
    </div>

    <button onclick="location.href='{{ action('InvoiceItemCategoryController@create') }}'" class="btn btn-success">
        Create
    </button>

@stop
