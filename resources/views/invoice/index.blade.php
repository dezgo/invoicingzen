@extends('web')

@section('content')
    @include('/includes/flash_message_content')
    <h1>Show Invoices</h1>

    <div class="input-group"> <span class="input-group-addon">Filter</span>
        <input id="filter" type="text" class="form-control" placeholder="Type here...">
    </div>
    <br>

    <div>
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tabUnpaid" aria-controls="tabUnpaid" role="tab" data-toggle="tab">Unpaid</a></li>
        <li role="presentation"><a href="#tabQuotes" aria-controls="tabQuotes" role="tab" data-toggle="tab">Quotes</a></li>
        <li role="presentation"><a href="#tabReceipts" aria-controls="tabReceipts" role="tab" data-toggle="tab">Receipts</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tabUnpaid">

            <table class="table-condensed" id="invoiceTable">
                <thead>
                    <tr>
                        @if (count($unpaid) > 0)
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
                <tbody class="searchable">

                @foreach($unpaid as $invoice)
                    <tr id='{{ $invoice->id }}'>
                        <td>{{ $invoice->description }}</td>
                        <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                        <td class="text-right">${{ App\Money::getFormatted($invoice->total) }}</td>
                        <td class="text-right">${{ App\Money::getFormatted($invoice->owing) }}</td>
                        <td>{{ ucfirst($invoice->type) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

        <div role="tabpanel" class="tab-pane" id="tabQuotes">

            <table class="table-condensed" id="invoiceTable">
                <thead>
                    <tr>
                        @if (count($quotes) > 0)
                        <td><h4>Inv Num / Client</h4></td>
                        <td><h4>Date</h4></td>
                        <td><h4>Total</h4></td>
                        <td><h4>Owing</h4></td>
                        <td><h4>Type</h4></td>
                        @else
                        <td colspan="6">
                            {{ trans('invoice.no-quotes') }}
                        </td>
                        @endif
                    </tr>
                </thead>
                <tbody class="searchable">

                @foreach($quotes as $invoice)
                    <tr id='{{ $invoice->id }}'>
                        <td>{{ $invoice->description }}</td>
                        <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                        <td class="text-right">${{ App\Money::getFormatted($invoice->total) }}</td>
                        <td class="text-right">${{ App\Money::getFormatted($invoice->owing) }}</td>
                        <td>{{ ucfirst($invoice->type) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

        <div role="tabpanel" class="tab-pane" id="tabReceipts">

            <table class="table-condensed" id="invoiceTable">
                <thead>
                    <tr>
                        @if (count($receipts) > 0)
                        <td><h4>Inv Num / Client</h4></td>
                        <td><h4>Date</h4></td>
                        <td><h4>Total</h4></td>
                        <td><h4>Owing</h4></td>
                        <td><h4>Type</h4></td>
                        @else
                        <td colspan="6">
                            {{ trans('invoice.no-receipts') }}
                        </td>
                        @endif
                    </tr>
                </thead>
                <tbody class="searchable">

                @foreach($receipts as $invoice)
                    <tr id='{{ $invoice->id }}'>
                        <td>{{ $invoice->description }}</td>
                        <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                        <td class="text-right">${{ App\Money::getFormatted($invoice->total) }}</td>
                        <td class="text-right">${{ App\Money::getFormatted($invoice->owing) }}</td>
                        <td>{{ ucfirst($invoice->type) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
      </div>

    </div>

    <hr />

    @if(Gate::check('admin'))
    <a href="/invoice/create" class="btn btn-success">Create</a>
    @endif
@stop

@section('footer')

<script language="javascript">

$(document).ready (function(){
    $('#invoiceTable').on('mouseover', 'tr', function(event) {
        if ($(this).parent()[0].tagName == 'TBODY') {
            $(this).addClass('bg-primary').siblings().removeClass('bg-primary');
        }
    });

    $('tr').click(function(event) {
        console.log(event);
        location.href= '/invoice/'+event.currentTarget.id+'/print';
    });

    $('tr').css('cursor', 'pointer');

    // thanks to http://jsfiddle.net/giorgitbs/52ak9/1/
    (function ($) {

        $('#filter').keyup(function () {

            var rex = new RegExp($(this).val(), 'i');
            $('.searchable tr').hide();
            $('.searchable tr').filter(function () {
                return rex.test($(this).text());
            }).show();

        })

    }(jQuery));
});

</script>
@stop
