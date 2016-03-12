<div class="form-group">
    @if ($invoice->invoice_items->count() > 0)
    <div class="container">
        <table class="table-condensed" id="invoiceItemTable">
            <thead>
                <tr>
                    <td><h4>Ready</h4></td>
                    <td><h4>Qty</h4></td>
                    <td><h4>Description</h4></td>
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
                        <input type="checkbox" name="chkReady" iiid="{{ $invoice_item->id
                            }}"{{ $invoice_item->ready ? ' checked' : '' }}>
                    </td>
                    <td>{{ $invoice_item->quantity }}</td>
                    <td>
                        @if(Gate::check('admin'))
                        <a href='{{ action('InvoiceItemController@show', $invoice_item->id) }}'>{{
                            $invoice_item->description }}</a>
                        @else
                            {{ $invoice_item->description }}
                        @endif
                    </td>
                    <td>{{ $invoice_item->price }}</td>
                    @if(Gate::check('admin'))
                    <td>
                        <a id='anchorURL' target='_blank' href='{{ $invoice_item->url }}'>View URL</a>
                    </td>
                    @endif
                </tr>
            @endforeach
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
