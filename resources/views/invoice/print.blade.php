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
            <img class="left-block" src="{{ url('/images/'.Auth::user()->logo_filename) }}" />
        </td>
        <td colspan="6" align="right">
            <br><Br>
            <h2 class="cred text-uppercase">
                {{ strtoupper($invoice->type) }}
            </h2>
            <br>
            <br>
            {{ trans('settings.abn').': '.$settings->get('abn') }}<br />
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
      @if ($invoice->is_quote == '' and $invoice->owing > 0)
      <td colspan="2" valign="top">
        <div class="cred">{{ trans('settings.payment_terms') }}:</div>
      </td>
      <td colspan="3" valign="top" align="right">
        {{ $settings->get('payment_terms') }}
      </td>
      @endif
    </tr>
    <tr><td colspan="12"><br /><br /></td></tr>

    <tr>
      <td colspan="8"><b>Item</b></td>
      <td colspan="2" align="right"><b>Unit&nbsp;Price</b></td>
      <td colspan="2" align="right"><b>Total</b></td>
  </tr>

  @foreach($invoice->invoice_items as $invoice_item)
    <tr>
      <td colspan="8">{{ (int) $invoice_item->quantity }} x {{ $invoice_item->description }}</td>
      <td colspan="2" align="right">{{ $invoice_item->price }}</td>
      <td colspan="2" align="right">{{ number_format($invoice_item->total, 2) }}</td>
    </tr>
  @endforeach
  <tr><td><br /></td></tr>
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="3"><b>{{ trans('invoice.grand-total') }}:</b></td>
  <td colspan="2" align="right">{{ number_format($invoice->total, 2) }}</td>
</tr>
@if ($invoice->type != 'Quote')
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="3"><b>{{ trans('invoice.amount-paid') }}:</b></td>
  <td colspan="2" align="right">{{ number_format($invoice->paid, 2) }}</td>
</tr>
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="3"><b>{{ trans('invoice.balance-due') }}:</b></td>
  <td colspan="2" align="right">{{ number_format($invoice->owing, 2) }}</td>
</tr>
@endif
<tr><td><br /></td></tr>
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="5">{{ trans('invoice.no-gst') }}</td>
</tr>
<tr><td><br /><hr /></td></tr>
<tr>
  <td colspan="4"><h4 class="cred">Enquiries</h4></td>
  @if ($invoice->is_quote == '' and $invoice->owing > 0)
  <td colspan="8"><h4 class="cred">How to Pay</h4></td>
  @endif
</tr>
<tr>
  <td colspan="4">{{ trans('settings.enquiries_phone') }}:&nbsp;{{ $settings->get('enquiries_phone') }}</td>
  @if ($invoice->is_quote == '' and $invoice->owing > 0)
  <td colspan="4">Payment by EFT</td>
  <td colspan="4">Payment by Cheque</td>
  @endif
</tr>
<tr>
  <td colspan="4">{{ trans('settings.enquiries_email') }}:&nbsp;{{ $settings->get('enquiries_email') }}</td>
  @if ($invoice->is_quote == '' and $invoice->owing > 0)
  <td colspan="1">{{ trans('settings.bsb') }}:</td>
  <td colspan="3">{{ $settings->get('bsb') }}</td>
  <td colspan="4">Mail Cheques to</td>
  @endif
</tr>
<tr>
  <td colspan="4">{{ trans('settings.enquiries_web') }}:&nbsp;{{ $settings->get('enquiries_web') }}</td>
  @if ($invoice->is_quote == '' and $invoice->owing > 0)
  <td colspan="1">Account:</td>
  <td colspan="3">{{ $settings->get('bank_account_number') }}</td>
  <td colspan="4">{{ $settings->get('mailing_address_line_1') }}</td>
  @endif
</tr>
@if ($invoice->is_quote == '' and $invoice->owing > 0)
<tr>
  <td colspan="4">&nbsp;</td>
  <td colspan="1">Reference:</td>
  <td colspan="3">Inv{{ $invoice->invoice_number}}</td>
  <td colspan="4">{{ $settings->get('mailing_address_line_2') }}</td>
</tr>
<tr>
  <td colspan="8">&nbsp;</td>
  <td colspan="4">{{ $settings->get('mailing_address_line_3') }}</td>
</tr>
@endif
</table>

@stop
