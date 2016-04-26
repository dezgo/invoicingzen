@extends('web')
@section('content')
<Table class="table" style="background:white;">
    <tr valign="top">
        <Td width="50%">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title">Features</h3>
                    <Br />&nbsp;
                </div>
            </div>
        </td>
        <Td width="10%">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title">BASIC</h3>
                    <Br />Free
                </div>
            </div>
        </td>
        <Td width="10%">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title">STANDARD</h3>
                    <Br />A$9/month
                </div>
            </div>
        </td>
        <Td width="10%">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title">PREMIUM</h3>
                    <Br />A$19/month
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>Invoices per month</td>
        <td class="text-center">10</td>
        <td class="text-center">Unlimited</td>
        <td class="text-center">Unlimited</td>
    </tr>
    <tr>
        <td>Works for GST and non-GST registered businesses</td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
    </tr>
    <tr>
        <td>Ability to upload your own logo</td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
    </tr>
    <tr>
        <td>Invoices viewable online and downloadable as PDF</td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
    </tr>
    <tr>
        <td>One-click to mark invoice as paid/unpaid</td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
    </tr>
    <tr>
        <td>Peace-of-mind - SSL encrypts all data communications
            between browser and server</td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
    </tr>
    <tr>
        <td>Email invoices using your own email address</td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
    </tr>
    <tr>
        <td>Customise email signature</td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
    </tr>
    <tr>
        <td>Get paid faster with one-click link to invoice</td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
    </tr>
    <tr>
        <td>Lightning-fast filter on invoice list page to quickly
            find invoices</td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
    </tr>
    <tr>
        <td>Custom Invoice/Quote/Receipt Templates</td>
        <td class="text-center"><img src='/images/cross_sm.png' /></td>
        <td class="text-center"><img src='/images/cross_sm.png' /></td>
        <td class="text-center"><img src='/images/tick_sm.png' /></td>
    </tr>
</table>
<Br />
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
    </div>
</div>
@stop
