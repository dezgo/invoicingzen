<div class="form-group">
    @if ($invoice->invoice_items->count() > 0)
    <div class="container">
        <table class="table-condensed" id="invoiceItemTable">
            <thead>
                <tr>
                    <td><h4>Qty</h4></td>
                    <td><h4>Description</h4></td>
                    <td><h4>Price</h4></td>
                    <td><h4>URL</h4></td>
                </tr>
            </thead>

            <tbody>
            @foreach($invoice->invoice_items as $invoice_item)
                <tr>
                    <td>{{ $invoice_item->quantity }}</a></td>
                    <td>
                        @if(Gate::check('admin'))
                        <a href='{{ action('InvoiceItemController@show', $invoice_item->id) }}'>
                            {{ $invoice_item->description }}
                        </a>
                        @else
                            {{ $invoice_item->description }}
                        @endif
                    </td>
                    <td>{{ $invoice_item->price }}</td>
                    <td>
                        <a id='anchorURL' target='_blank' href='{{ $invoice_item->url }}'>View URL</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <hr />
    </div>
    @elseif(Gate::check('admin'))
    <h4>
        <div class="panel panel-default">
        Great, you have your invoice, now let's add
        some items to it. Click the 'Add Invoice Item' button
        below to get started.
        </div>
    </h4>
    @endif

    @if(Gate::check('admin'))
    <a name='btnCreateItem' href="{{ action('InvoiceItemController@create1', [$invoice->id]) }}">
        <button id='btnAddInvoiceItem' class="btn btn-primary">Add Invoice Item</button>
    </a>
    @endif
