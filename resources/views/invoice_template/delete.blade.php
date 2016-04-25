@extends('web')
@section('content')
    <h1 align="left">Delete Invoice Template</h1>

    WARNING: The following Invoice Tepmlate will be permanently deleted.<br />
    <br />
    <b>Title:</b> {{ $invoice_template->title }}<br />
    <br />
    <form method="POST" action='/invoice_template/{{ $invoice_template->id }}'>
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="DELETE" />
        <input type="submit" name="btnConfirmDelete" value="CONFIRM DELETE" class="btn btn-danger" />
    </form>

@stop
