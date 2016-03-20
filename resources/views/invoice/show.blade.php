@extends('web')

@section('content')
    @include('includes.flash_message_content')

    <h1 align="left">Show Invoice</h1>

    {!! Form::model($invoice, ['method' => 'GET', 'url' => 'invoice/'.$invoice->id.'/edit']) !!}
        <?php $options['disabled'] = 'true'; ?>
        @include('invoice.form', ['submitButtonText' => 'Edit', 'invoice_id' => $invoice->id])
        <?php unset($options['disabled']) ?>
    {!! Form::close() !!}

    @include('/invoiceitem/index')
<br><br>
    <a id='btnViewInvoice' class="btn btn-info" href="{{ url('/invoice/'.$invoice->id.'/print') }}">
        View
    </a>
    @if(Gate::check('admin'))
    <a class="btn btn-info" href="{{ url('/invoice/'.$invoice->id.'/email') }}">
        Email
    </a>
    @endif
    <br /><br />

    @if ($invoice -> owing > 0)

    {!! Form::open(['method' => 'POST', 'url' => url('/invoice/'.$invoice->id.'/pay')]) !!}
      <script
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="{{ env('STRIPE_KEY') }}"
        data-amount="{{ $invoice->owing*100 }}"
        data-name="Invoicing Zen"
        data-description="{{ $invoice->description }}"
        data-image="{{ url('/images/logo.jpg') }}"
        data-locale="auto">
      </script>
      {!! Form::close() !!}

      @endif

@stop

@section('footer')
    <!-- @include('includes.flash_message_footer') -->

<script language="javascript">

$('#invoiceItemTable').on('mouseover', 'tbody tr', function(event) {
    if ($(this).parent()[0].tagName == 'TBODY') {
        $(this).addClass('bg-primary').siblings().removeClass('bg-primary');
    }
});

</script>
@stop
