@extends('web')
@section('content')
    <h1 align="left">Delete Invoice</h1>

    WARNING: The following Invoice and all it's items will be permanently deleted.
    Note, you cannot reuse an invoice number even after the associated invoice
    has been deleted. <br />
    <br />
    <b>Customer:</b> {{ $invoice->customer }}<br />
    <b>Number:</b> {{ $invoice->invoice_number }}<br />
    <b>Is Quote:</b> {{ $invoice->is_quote ? 'Yes' : 'No' }}<br />
    <b>Total:</b> {{ $invoice->total }}<br />
    <b>Paid:</b> {{ $invoice->paid }}<br />
    <b>Owing:</b> {{ $invoice->owing }}<br />
    <b>Invoice Date:</b> {{ $invoice->invoice_date }}<br />
    <b>Due Date:</b> {{ $invoice->due_date }}<br />
    <br />
    <form method="POST" action='/invoice/{{ $invoice->id }}'>
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="DELETE" />
        <input type="submit" name="btnConfirmDelete" value="CONFIRM DELETE" class="btn btn-danger" />
    </form>

@stop
