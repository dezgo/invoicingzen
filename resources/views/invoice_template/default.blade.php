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
            $*Company.Logo*$
        </td>
        <td colspan="6" align="right">
            <h3>$*Company.Name*$</h3>
            <br><Br>
            <h4 class="text-uppercase">
                {{ strtoupper($type) }}
            </h4>
        </td>
    </tr>

    <tr>
      <td colspan="8">
        <b>Customer Details:</b>
      </td>
      <td colspan="4" align="right">
          {{ trans('settings.abn') }}: $*Settings.ABN*$
      </td>
    </tr>
    <tr>
        <td colspan="7">
            $*User.Fullname*$<br />
            $*User.AddressMulti*$
        </td>
      <td colspan="3">
        <b>Number:</b><br />
        <b>Date:</b>
        @if ($type == 'invoice')
        <Br /><b>{{ trans('settings.payment_terms') }}:</b>
        @endif
      </td>
      <td colspan="2" align="right">
          $*Invoice.InvoiceNumber*$<Br />
          $*Invoice.InvoiceDate*${!!
              ($type == 'invoice') ? '<br />$*Settings.PaymentTerms*$' : '' !!}
      </td>
    </tr>
    <tr><td colspan="12"><br /><br /></td></tr>

    <tr>
      @if ($taxable)
      <td colspan="9"><b>Item</b></td>
      @else
      <td colspan="10"><b>Item</b></td>
      @endif
      <td colspan="1" align="right"><b>Unit&nbsp;Price</b></td>
      @if ($taxable)
      <td colspan="1" align="right"><b>{{ trans('settings.tax') }}</b></td>
      @endif
      <td colspan="1" align="right"><b>Total</b></td>
    </tr>
    @if ($taxable)
    <tr>
        <td colspan="9">&nbsp;</td>
        <td>(excl&nbsp;{{ trans('settings.tax') }})</td>
        <td>&nbsp;</td>
        <td>(incl&nbsp;{{ trans('settings.tax') }})</td>
    </tr>
    @endif

  $*StartInvoiceItems*$
    <tr>
      @if ($taxable)
      <td colspan="9">
      @else
      <td colspan="10">
      @endif
          $*InvoiceItem.Quantity*$ x $*InvoiceItem.Description*$
      </td>
      @if ($taxable)
      <td colspan="1" align="right">$*InvoiceItem.PriceExTax*$</td>
      <td colspan="1" align="right">$*InvoiceItem.Tax*$</td>
      @else
      <td colspan="1" align="right">$*InvoiceItem.Price*$</td>
      @endif
      <td colspan="1" align="right">$*InvoiceItem.Total*$</td>
    </tr>
  $*EndInvoiceItems*$
  <tr><td><br /></td></tr>
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="3"><b>{{ trans('invoice.grand-total') }}:</b></td>
  <td colspan="2" align="right">$*Invoice.Total*$</td>
</tr>
@if ($type != 'quote')
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="3"><b>{{ trans('invoice.amount-paid') }}:</b></td>
  <td colspan="2" align="right">$*Invoice.Paid*$</td>
</tr>
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="3"><b>{{ trans('invoice.balance-due') }}:</b></td>
  <td colspan="2" align="right">$*Invoice.Owing*$</td>
</tr>
@endif
@if (!$taxable)
<tr>
  <td colspan="7">&nbsp;</td>
  <td colspan="5">{{ trans('invoice.no-tax') }}</td>
</tr>
@endif
<tr><td height="50%">&nbsp;</td></tr>
<tr><td colspan="12"><hr /></td></tr>
<tr>
  <td colspan="4"><h4>Enquiries</h4></td>
  @if ($type == 'invoice')
  <td colspan="8"><h4>How to Pay</h4></td>
  @endif
</tr>
<tr>
  <td colspan="4">{{ trans('settings.enquiries_phone') }}:&nbsp;$*Settings.EnquiriesPhone*$</td>
  @if ($type == 'invoice')
  <td colspan="4">Payment by EFT</td>
  <td colspan="4">Payment by Cheque</td>
  @endif
</tr>
<tr>
  <td colspan="4">{{ trans('settings.enquiries_email') }}:&nbsp;$*Settings.EnquiriesEmail*$</td>
  @if ($type == 'invoice')
  <td colspan="1">{{ trans('settings.bsb') }}:</td>
  <td colspan="3">$*Settings.BSB*$</td>
  <td colspan="4">Mail Cheques to</td>
  @endif
</tr>
<tr>
  <td colspan="4">{{ trans('settings.enquiries_web') }}:&nbsp;$*Settings.EnquiriesWeb*$</td>
  @if ($type == 'invoice')
  <td colspan="1">Account:</td>
  <td colspan="3">$*Settings.BankAccountNumber*$</td>
  <td colspan="4">$*Settings.MailingAddressLine1*$</td>
  @endif
</tr>
@if ($type == 'invoice')
<tr>
  <td colspan="4">&nbsp;</td>
  <td colspan="1">Reference:</td>
  <td colspan="3">Inv $*Invoice.InvoiceNumber*$</td>
  <td colspan="4">$*Settings.MailingAddressLine2*$</td>
</tr>
<tr>
  <td colspan="8">&nbsp;</td>
  <td colspan="4">$*Settings.MailingAddressLine3*$</td>
</tr>
@endif
</table>
