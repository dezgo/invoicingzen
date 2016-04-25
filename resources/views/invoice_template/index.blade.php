@extends('web')

@section('content')
<h1>Invoice Templates</h1>

@if ($invoice_templates->count() <= 1)
    {{ trans('invoice_template.welcome') }}
@endif

<table class="table-condensed" id="invoiceTemplateTable">
@foreach($invoice_templates as $invoice_template)
<tr id='{{ $invoice_template->id }}'>
<td>{{ $invoice_template->title }}</td>
</tr>
@endforeach
</table>

<hr />

<a href="/invoice_template/create" class="btn btn-success">Create</a>
<a href="/invoice_template/defaults" class="btn btn-primary">Restore Defaults</a>

<script language="javascript">

$(document).ready (function(){
    $('#invoiceTemplateTable').on('mouseover', 'tr', function(event) {
        if ($(this).parent()[0].tagName == 'TBODY') {
            $(this).addClass('bg-primary').siblings().removeClass('bg-primary');
        }
    });

    $('tr').click(function(event) {
        console.log(event);
        location.href= '/invoice_template/'+event.currentTarget.id+'/edit';
    });

    $('tr').css('cursor', 'pointer');
});

</script>
@stop
