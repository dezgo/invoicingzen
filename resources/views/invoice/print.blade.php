@extends('print')
@section('content')
<table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
        <td width="8.33%">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="6" align="left" valign="top">
            <img class="left-block" src="{{ url('/images/logo.jpg') }}" />
        </td>
        <td colspan="6" align="right">
            <br><Br>
            <h2 class="cred text-uppercase">
                {{ strtoupper($invoice->type) }}
            </h2>
            <br>
            <br>
            ABN:&nbsp;26&nbsp;537&nbsp;857&nbsp;341<br />
            <br />
        </td>
    </tr>

    <tr>
      <td colspan="7">
        <div class="cred">Customer Details:</div>
      </td>
      <td colspan="2">
        <div class="cred">{{ $invoice->type }}&nbsp;Number:</div>
      </td>
      <td colspan="3" align="right">
          {{ $invoice->invoice_number }}
      </td>
    </tr>

    <tr>
      <td colspan="7">
          {{ $invoice->user->full_name }}
      </td>
      <td colspan="2">
        <div class="cred">{{ $invoice->type }}&nbsp;Date:</div>
      </td>
      <td colspan="3" align="right">
          {{ $invoice->invoice_date }}
      </td>
    </tr>

    <tr>
      <td colspan="7">
        {!! $invoice->user->address_multi !!}
      </td>
      @if ($invoice->owing > 0)
      <td colspan="2" valign="top">
        <div class="cred">Payment&nbsp;Terms:</div>
      </td>
      <td colspan="3" valign="top" align="right">
        7 Days
      </td>
      @endif
    </tr>
    <tr><td colspan="12"><br /><br /></td></tr>

    <tr>
      <td colspan="1"><b>Qty</b></td>
      <td colspan="7"><b>Description</b></td>
      <td colspan="2" align="right"><b>Unit&nbsp;Price</b></td>
      <td colspan="2" align="right"><b>Total</b></td>
  </tr>

  @foreach($invoice->invoice_items as $invoice_item)
    <tr>
      <td colspan="1">{{ (int) $invoice_item->quantity }}</td>
      <td colspan="7">{{ $invoice_item->description }}</td>
      <td colspan="2" align="right">{{ $invoice_item->price }}</td>
      <td colspan="2" align="right">{{ number_format($invoice_item->total, 2) }}</td>
    </tr>
  @endforeach
  <tr><td><br /></td></tr>
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="3"><b>Grand-total:</b></td>
  <td colspan="2" align="right">{{ number_format($invoice->total, 2) }}</td>
</tr>
@if ($invoice->is_quote == '')
    <tr>
      <td colspan="7">&nbsp;</td>
      <td colspan="3"><b>Amount&nbsp;paid:</b></td>
      <td colspan="2" align="right">{{ number_format($invoice->paid, 2) }}</td>
    </tr>
    <tr>
      <td colspan="7">&nbsp;</td>
      <td colspan="3"><b>Balance&nbsp;owing:</b></td>
      <td colspan="2" align="right">{{ number_format($invoice->owing, 2) }}</td>
    </tr>
@endif
<tr><td><br /></td></tr>
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="5">No GST has been included</td>
</tr>
<tr><td><br /><hr /></td></tr>
<tr>
  <td colspan="4"><h4 class="cred">Enquiries</h4></td>
  @if ($invoice->is_quote == '')
  <td colspan="8"><h4 class="cred">How to Pay</h4></td>
  @endif
</tr>
<tr>
  <td colspan="4">Phone: (02) 6112 8025</td>
  @if ($invoice->is_quote == '')
  <td colspan="4">Payment by EFT</td>
  <td colspan="4">Payment by Cheque</td>
  @endif
</tr>
<tr>
  <td colspan="4">E mail@computerwhiz.com.au</td>
  @if ($invoice->is_quote == '')
  <td colspan="1">BSB:</td>
  <td colspan="3">082-902</td>
  <td colspan="4">Mail Cheques to</td>
  @endif
</tr>
<tr>
  <td colspan="4">W www.computerwhiz.com.au</td>
  @if ($invoice->is_quote == '')
  <td colspan="1">Account:</td>
  <td colspan="3">115287822</td>
  <td colspan="4">238 La Perouse St</td>
  @endif
</tr>
@if ($invoice->is_quote == '')
<tr>
  <td colspan="4">&nbsp;</td>
  <td colspan="1">Reference:</td>
  <td colspan="3">Inv{{ $invoice->invoice_number}}</td>
  <td colspan="4">Red Hill ACT 2603</td>
</tr>
@endif
</table>

@stop
