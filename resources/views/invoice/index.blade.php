@extends('web')

@section('content')
    <h1>Show Invoices</h1>

        <table class="table-condensed" id="invoiceTable">
            <thead>
                <tr>
                    @if ($invoices->count() > 0)
                    <td><h4>Inv Num / Client</h4></td>
                    <td><h4>Date</h4></td>
                    <td><h4>Total</h4></td>
                    <td><h4>Owing</h4></td>
                    <td><h4>Type</h4></td>
                    @else
                    <td colspan="6">
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
    <tr id='{{ $invoice->id }}'>
        <td>{{ $invoice->description }}</td>
        <td>{{ $invoice->invoice_date }}</td>
        <td class="text-right">${{ money_format('%i', $invoice->total) }}</td>
        <td class="text-right">${{ money_format('%i', $invoice->owing) }}</td>
        <td>{{ $invoice->type }}</td>
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

    $('tr').click(function(event) {
        console.log(event);
        location.href= '/invoice/'+event.currentTarget.id;
    });

    $('tr').css('cursor', 'pointer');
});

</script>
@stop
