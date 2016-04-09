@extends('web')

@section('content')
<h1>Merge Invoice</h1>

@if ($invoice->user->invoices->count() == 0)
    {{ trans('invoice.no-invoices-to-merge') }}
@else
    <p>
        Select the invoice to merge with Invoice {{ $invoice->description }}. This operation
        will create a new invoice with all the line items from this invoice, as well as
        the selected invoice below.
    </p>
    <p>
        Once completed, the two original invoices will be deleted.
    </p>
    <Br />
    <table class="table-condensed" id="invoiceTable">
        <thead>
            <tr>
                <td><h4>Description</h4></td>
                <td><h4>Date</h4></td>
                <td><h4>Total</h4></td>
                <td><h4>Owing</h4></td>
                <td><h4>Lines</h4></td>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td colspan=5>
                    <b>First invoice:</b>
                </td>
            </tr>
            <tr>
                <td>{{ $invoice->description }}</td>
                <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                <td class="text-right">${{ money_format('%i', $invoice->total) }}</td>
                <td class="text-right">${{ money_format('%i', $invoice->owing) }}</td>
                <td>{{ $invoice->invoice_items->count() }}</td>
            </tr>

            <tr>
                <td colspan=5>
                    <b>Select the second invoice from the list below:</b>
                </td>
            </tr>

    @foreach ($invoice->user->invoices as $customer_invoice)
        @if ($invoice->id != $customer_invoice->id)
        <tr>
            <td>
                <form method="POST" action="/invoice/merge">
                    {{ csrf_field() }}
                <input type='hidden' name='merge_invoice_1' value='{{ $customer_invoice->id }}' />
                <input type='hidden' name='merge_invoice_2' value='{{ $invoice->id }}' />
                {{ $customer_invoice->description }}
            </td>
            <td>{{ $customer_invoice->invoice_date->format('d-m-Y') }}</td>
            <td class="text-right">${{ money_format('%i', $customer_invoice->total) }}</td>
            <td class="text-right">${{ money_format('%i', $customer_invoice->owing) }}</td>
            <td>{{ $customer_invoice->invoice_items->count() }}</td>
            <td>
                <input type='submit' name='btnSubmit' value='Merge' class='btn btn-info' />
                </form>
            </td>
        </tr>
        @endif
    @endforeach
    </tbody>
    </table>
@endif

@stop
