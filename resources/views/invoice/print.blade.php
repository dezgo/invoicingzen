@extends('print')
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
<table class="hidden-print" cellpadding="0" cellspacing="0" width="720" border="0" align="center">
    <Tr>
        <Td>
            <br />
            <a class="btn btn-primary" href="{{ url('/invoice') }}">Show Invoices</a>
            @can ('edit-invoice', $invoice)
                &nbsp;<a class="btn btn-primary" href="{{ url('/invoice/'.$invoice->id) }}">Edit Invoice</a>
            @endcan
            <br />
        </Td>
    </Tr>
</table>
<br />
<table cellpadding="0" cellspacing="0" width="720" border="1" align="center" style="background-color: white">
    <tr>
        <td>

<table class="table-condensed" width="700" height="1018" align="center">
    <tr style="display:none; border-collapse">
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="6" align="left" valign="top">
            @if (Auth::user()->logo_filename != '' and env('APP_ENV') != 'testing')
                <img class="left-block" src="{{ url('/images/'.Auth::user()->logo_filename) }}" />
            @endif
        </td>
        <td colspan="6" align="right">
            <h3>{{ Auth::user()->company->company_name }}</h3>
            <br><Br>
            <h4 class="text-uppercase">
                {{ strtoupper($invoice->type) }}
            </h4>
        </td>
    </tr>

    <tr>
      <td colspan="8">
        <b>Customer Details:</b>
      </td>
      <td colspan="4" align="right">
          {{ trans('settings.abn').': '.$settings->get('abn') }}
      </td>
    </tr>
    <tr>
        <td colspan="7">
            {{ $invoice->user->full_name }}<br />
            {!! $invoice->user->address_multi !!}
        </td>
      <td colspan="3">
        <b>Number:</b><br />
        <b>Date:</b>
        @if ($invoice->is_quote == '' and $invoice->owing > 0)
        <Br /><b>{{ trans('settings.payment_terms') }}:</b>
        @endif
      </td>
      <td colspan="2" align="right">
          {{ $invoice->invoice_number }}<Br />
          {{ $invoice->invoice_date->format('d-m-Y') }}{!!
              ($invoice->owing > 0) ? '<br />'.$settings->get('payment_terms') : '' !!}
      </td>
    </tr>
    <tr><td colspan="12"><br /><br /></td></tr>

    <tr>
      @if ($settings->get('gst_registered'))
      <td colspan="9"><b>Item</b></td>
      @else
      <td colspan="10"><b>Item</b></td>
      @endif
      <td colspan="1" align="right"><b>Unit&nbsp;Price</b></td>
      @if ($settings->get('gst_registered'))
      <td colspan="1" align="right"><b>GST</b></td>
      @endif
      <td colspan="1" align="right"><b>Total</b></td>
    </tr>
    @if ($settings->get('gst_registered'))
    <tr>
        <td colspan="9">&nbsp;</td>
        <td>(excl&nbsp;GST)</td>
        <td>&nbsp;</td>
        <td>(incl&nbsp;GST)</td>
    </tr>
    @endif

  @foreach($invoice->invoice_items as $invoice_item)
    <tr>
      @if ($settings->get('gst_registered'))
      <td colspan="9">
      @else
      <td colspan="10">
      @endif
          {{ (int) $invoice_item->quantity }} x {{ $invoice_item->description }}
      </td>
      @if ($settings->get('gst_registered'))
      <td colspan="1" align="right">{{ number_format($invoice_item->price*10/11, 2) }}</td>
      <td colspan="1" align="right">{{ number_format($invoice_item->total/11, 2) }}</td>
      @else
      <td colspan="1" align="right">{{ $invoice_item->price }}</td>
      @endif
      <td colspan="1" align="right">{{ number_format($invoice_item->total, 2) }}</td>
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
@if (!$settings->get('gst_registered'))
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="5">{{ trans('invoice.no-gst') }}</td>
</tr>
@endif
<tr><td height="50%">&nbsp;</td></tr>
<tr><td colspan="12"><hr /></td></tr>
<tr>
  <td colspan="4"><h4>Enquiries</h4></td>
  @if ($invoice->is_quote == '' and $invoice->owing > 0)
  <td colspan="8"><h4>How to Pay</h4></td>
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
</td>
</tr>
</table>
@stop
