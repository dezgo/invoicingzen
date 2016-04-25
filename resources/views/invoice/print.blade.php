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
<br class="hidden-print" />
<table class="hidden-print" cellpadding="0" cellspacing="0" width="720" border="0" align="center">
    <Tr>
        <Td>
            @can ('edit-invoice', $invoice)
                &nbsp;<a class="btn btn-primary" href="{{ '/invoice/'.$invoice->id }}">Edit</a>
            @endcan
            <a class="btn btn-primary" href="{{ '/invoice/'.$invoice->id.'/pdf' }}">View As PDF</a>
            @if(Gate::check('admin'))
            <a class="btn btn-primary" href="{{ 'invoice/'.$invoice->id.'/email' }}">
                Email
            </a>
            <a name='btnMerge' class="btn btn-primary" href="{{ '/invoice/'.$invoice->id.'/merge' }}">
                Merge
            </a>
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
