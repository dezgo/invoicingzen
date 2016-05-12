@extends('web')

@section('content')
@include('user.sidebar')
<table class="table">
    <Tr>
        <Td>Date</Td>
        <Td>Description</Td>
        <Td>Amount</Td>
        <Td>Start</Td>
        <Td>End</Td>
        <Td>Total</Td>
        <Td>Paid</Td>
    </Tr>
@foreach ($invoices as $invoice)
    <?php $first = true ?>
    @foreach ($invoice->lines['data'] as $invoice_line)
    <tr>
        <Td>{{ $first ? App\ZenDateTime::formatLong($invoice->date) : '' }}</Td>
        <Td>{{ $invoice_line->description !== null ? $invoice_line->description : $invoice_line->plan->name.'&nbsp;'.$invoice_line->plan->object.'&nbsp;'.$invoice_line->plan->interval_count.'&nbsp;'.$invoice_line->plan->interval }}</td>
        <Td>{{ \Laravel\Cashier\Cashier::formatAmount($invoice_line->amount) }}</td>
        <td>{{ App\ZenDateTime::formatLong($invoice_line->period->start) }}</td>
        <td>{{ App\ZenDateTime::formatLong($invoice_line->period->end) }}</td>
        <Td>{{ $first ? \Laravel\Cashier\Cashier::formatAmount($invoice->total) : '' }}</Td>
        <Td>{{ $first ? ($invoice->paid ? 'Yes' : 'No') : '' }}</Td>
    </Tr>
    <?php $first = false ?>
    @endforeach
@endforeach
</table>
@stop
