@extends('web')
@section('content')
<style>
ul.tick {
    list-style-image: url('/images/tick_sm.png');
}
ul.cross {
    list-style-image: url('/images/cross_sm.png');
}
</style>
<Table>
    <tr valign="top">
        <Td width="30%">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title">BASIC</h3>
                    <Br />Free
                </div>
                <div class="panel-body">
                    <ul class="tick">
                        <li>Up to 10 invoices per month</li>
                        <li>Works for GST and non-GST registered businesses</li>
                        <li>Ability to upload your own logo</li>
                        <li>Invoices viewable online and downloadable as PDF</li>
                        <li>One-click to mark invoice as paid/unpaid</li>
                        <li>Peace-of-mind - SSL encrypts all data communications
                            between browser and server</li>
                        <li>Email invoices using your own email address</li>
                        <li>Customise email signature</li>
                        <li>Get paid faster with one-click link to invoice</li>
                        <li>Lightning-fast filter on invoice list page to quickly
                            find invoices</li>
                    </ul>
                    <ul class="cross">
                        <li>Custom Invoice/Quote/Receipt Templates</li>
                    </ul>
                </div>
            </div>
        </Td>
        <td width="5%">&nbsp;</td>
        <Td width="30%">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title">STANDARD</h3>
                    <br />A$9/month
                </div>
                <div class="panel-body">
                    <ul class="tick">
                        <li>Unlimited invoices</li>
                        <li>Works for GST and non-GST registered businesses</li>
                        <li>Ability to upload your own logo</li>
                        <li>Invoices viewable online and downloadable as PDF</li>
                        <li>One-click to mark invoice as paid/unpaid</li>
                        <li>Peace-of-mind - SSL encrypts all data communications
                            between browser and server</li>
                        <li>Email invoices using your own email address</li>
                        <li>Customise email signature</li>
                        <li>Get paid faster with one-click link to invoice</li>
                        <li>Lightning-fast filter on invoice list page to quickly
                            find invoices</li>
                    </ul>
                    <ul class="cross">
                        <li>Custom Invoice/Quote/Receipt Templates</li>
                    </ul>
                </div>
            </div>
        </td>
        <td width="5%">&nbsp;</td>
        <Td width="30%">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title">PREMIUM</h3>
                    <br />A$19/month
                </div>
                <div class="panel-body">
                    <ul class="tick">
                        <li>Unlimited invoices</li>
                        <li>Works for GST and non-GST registered businesses</li>
                        <li>Ability to upload your own logo</li>
                        <li>Invoices viewable online and downloadable as PDF</li>
                        <li>One-click to mark invoice as paid/unpaid</li>
                        <li>Peace-of-mind - SSL encrypts all data communications
                            between browser and server</li>
                        <li>Email invoices using your own email address</li>
                        <li>Customise email signature</li>
                        <li>Get paid faster with one-click link to invoice</li>
                        <li>Lightning-fast filter on invoice list page to quickly
                            find invoices</li>
                            <li>Custom Invoice/Quote/Receipt Templates</li>
                    </ul>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title">Get Started Now</h3>
                </div>
                <div class="panel-body text-center">
                    You'll automatically get all the benefits of the
                    free plan, and can upgrade at any time.<br />
                    <Br />
                    <a href='/' id="btnSignUp" class="btn btn-primary">
                        Get Started
                    </a>
        </Td>
    </tr>
</Table>
@stop
