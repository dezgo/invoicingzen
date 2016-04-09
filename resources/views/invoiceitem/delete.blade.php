@extends('web')

@section('content')
    <h1 align="left">Delete Invoice Item</h1>

    WARNING: The following Invoice Item will be permanently deleted.<br />
    <br />
    <b>Category:</b> {{ $invoice_item->category->description }}<br />
    <b>Description:</b> {{ $invoice_item->description }}<br />
    <b>Quantity:</b> {{ $invoice_item->quantity }}<br />
    <b>Price:</b> {{ $invoice_item->price }}<br />
    <br />
    <form method="POST" action='/invoice_item/{{ $invoice_item->id }}'>
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="DELETE" />
        <input type="submit" name="btnConfirmDelete" value="CONFIRM DELETE" class="btn btn-danger" />
    </form>

@stop
