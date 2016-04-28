@extends('web')
@section('content')
<style>
body {
    background-color: #EBEBEB;
}
</style>
<style type="text/css" media="print">
    @page
    {
        size: auto;   /* auto is the current printer page size */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }

    body
    {
        background-color:#FFFFFF;
        /*border: solid 1px black ;*/
        margin: 0px;  /* the margin on the content before printing */
   }
</style>
<br class="hidden-print" />
@include('includes.flash_message_content')
<table class="hidden-print" cellpadding="0" cellspacing="0" width="720" border="0" align="center">
    <Tr>
        <Td>
            @can ('edit-invoice', $invoice)
                &nbsp;<a class="btn btn-primary" href="{{ '/invoice/'.$invoice->id }}">Edit</a>
            @endcan
            <a class="btn btn-primary" href="{{ '/invoice/'.$invoice->id.'/pdf' }}" name="linkPDF">View As PDF</a>
            @if(Gate::check('admin'))
            <a class="btn btn-primary" href="{{ '/invoice/'.$invoice->id.'/email' }}">
                Email
            </a>
            <a name='btnMerge' class="btn btn-primary" href="{{ '/invoice/'.$invoice->id.'/merge' }}">
                Merge
            </a>
                @if ($invoice->owing > 0)
                <a name='btnPay' class="btn btn-primary" href="{{ '/invoice/'.$invoice->id.'/pay' }}">
                    Mark Paid
                </a>
                @else
                <a name='btnUnpay' class="btn btn-primary" href="{{ '/invoice/'.$invoice->id.'/unpay' }}">
                    Mark Unpaid
                </a>
                @endif
            @endif
            @if ($invoice->owing > 0)
<br /><br />
            {!! Form::open(['method' => 'POST', 'url' => url('/invoice/'.$invoice->id.'/pay')]) !!}
              <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="{{ env('STRIPE_KEY') }}"
                data-amount="{{ $invoice->owing*100 }}"
                data-name="Invoicing Zen"
                data-description="{{ $invoice->description }}"
                data-image="{{ url('/images/'.Auth::user()->company->logofilename) }}"
                data-locale="auto"
                data-currency="aud">
              </script>
              {!! Form::close() !!}

              @endif
        </Td>
        <td align="right">
            @if(Gate::check('admin'))
            <a name='btnDelete' class="btn btn-danger" href="{{ '/invoice/'.$invoice->id.'/delete' }}">
                Delete
            </a>
            @endif
        </td>
    </Tr>
</table>
<br />
<table cellpadding="0" cellspacing="0" width="720" border="1" align="center" style="background-color: white">
    <tr>
        <td>
            {!! $invoice_content !!}
        </td>
    </tr>
</table>
<br class="hidden-print" />
@stop
