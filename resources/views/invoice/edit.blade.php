@extends('master')

@section('content')
    <h1 align="left">Edit Invoice</h1>

    {!! Form::model($invoice, ['method' => 'PUT', 'url' => 'invoice/'.$invoice->id]) !!}
        @include('invoice.form', ['submitButtonText' => 'Update', 'invoice_id' => $invoice->id])
    {!! Form::close() !!}

    @include('errors.list')

    @include('/invoiceitem/index')

@stop

@section('footer')
<script language="javascript">

$('#invoiceItemTable').on('mouseover', 'tbody tr', function(event) {
    if ($(this).parent()[0].tagName == 'TBODY') {
        $(this).addClass('bg-primary').siblings().removeClass('bg-primary');
    }
});

</script>
@stop
