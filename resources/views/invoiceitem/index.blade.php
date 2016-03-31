<div class="form-group">
    @if ($invoice->invoice_items->count() > 0)
    <div class="container">
        <table class="table-condensed" id="invoiceItemTable">
            <thead>
                <tr>
                    <td><h4>Ready</h4></td>
                    <td><h4>Item</h4></td>
                    <td><h4>Price</h4></td>
                    @if(Gate::check('admin'))
                    <td><h4>URL</h4></td>
                    @endif
                </tr>
            </thead>

            <tbody>
            @foreach($invoice->invoice_items as $invoice_item)
                <tr>
                    <td>
                        @if(Gate::check('admin'))
                        <input type="checkbox" name="chkReady" iiid="{{ $invoice_item->id
                            }}"{{ $invoice_item->ready ? ' checked' : '' }}>
                        @elseif ($invoice_item->ready)
                        <span class="fa fa-check"></span>
                        @else
                        <span class="fa fa-times"></span>
                        @endif
                    </td>
                    <td>
                        {{ $invoice_item->quantity }}&nbsp;x&nbsp;
                        @if(Gate::check('admin'))
                        <a href='{{ action('InvoiceItemController@show', $invoice_item->id) }}'>{{
                            $invoice_item->description }}</a>
                        @else
                            {{ $invoice_item->description }}
                        @endif
                    </td>
                    <td class="text-right">{{ $invoice_item->price }}</td>
                    @if(Gate::check('admin'))
                    <td>
                        @if ($invoice_item->url == '')
                        -
                        @else
                        <a id='anchorURL' target='_blank' href='{{ $invoice_item->url }}'>View URL</a>
                        @endif
                    </td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td><b>Total:</b></td>
                <td class="text-right">{{ money_format('%i', $invoice->total) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><b>Paid:</b></td>
                <td class="text-right">{{ money_format('%i', $invoice->paid) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><b>Owing:</b></td>
                <td class="text-right">{{ money_format('%i', $invoice->owing) }}</td>
            </tr>
            </tbody>
        </table>

        <hr />
    </div>
    @elseif(Gate::check('admin'))
    <h4>
        <div class="panel panel-default">
        {{ trans('invoice.add-invoice-items') }}
        </div>
    </h4>
    @endif

    @if(Gate::check('admin'))
    <a name='btnCreateItem' href="{{ action('InvoiceItemController@create1', [$invoice->id]) }}">
        <button id='btnAddInvoiceItem' class="btn btn-primary">Add Invoice Item</button>
    </a>

    <script type="text/javascript">
        $(document).ready( function() {
            $('input[type=checkbox]').click(function() {
                var id = $(this).attr('iiid');
                $.post('/invoice_item/'+id+'/ready', 'json');
            });
        });
    </script>

    @endif
