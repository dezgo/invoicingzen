@extends('web')

@section('content')
    <h1>Show Invoices</h1>

        <table class="table-condensed" id="invoiceTable">
            <thead>
                <tr>
                    @if ($invoices->count() > 0)
                    <td><h4>Num</h4></td>
                    <td><h4>Date</h4></td>
                    <td><h4>Total</h4></td>
                    @else
                    <td colspan="3">
                        @if(Gate::check('admin'))
                        {{ trans('invoice.welcome-admin') }}
                        @else
                        {{ trans('invoice.welcome-user') }}
                        @endif
                    </td>
                    @endif
                </tr>
            </thead>
            <tbody>

    @foreach($invoices as $invoice)
    <tr>
        <td>
            <a href='{{ action('InvoiceController@show', $invoice->id) }}'>{{ $invoice->invoice_number }}</a>
        </td>
        <td>{{ $invoice->invoice_date }}</td>
        <td>{{ $invoice->total }}</td>
    </tr>
    @endforeach
    </tbody>
    </table>

    <hr />

    @if(Gate::check('admin'))
    <a href="{{ url('/invoice/create') }}" class="btn btn-success">Create</a>
    @endif
@stop

@section('footer')
@include('/includes/flash_message_footer')

<script language="javascript">

$(document).ready (function(){
    $('#invoiceTable').on('mouseover', 'tr', function(event) {
        if ($(this).parent()[0].tagName == 'TBODY') {
            $(this).addClass('bg-primary').siblings().removeClass('bg-primary');
        }
    });
});

</script>
@stop
