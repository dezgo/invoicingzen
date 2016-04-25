@extends('web')
@section('content')
    <h1 align="left">Delete Invoice Templates</h1>

    WARNING: Standard templates already exist. Restoring defaults will overwrite
    them. Is that OK?<br />
    <br />
    <form method="POST" action='/invoice_template/defaults'>
        {{ csrf_field() }}
        <input type="submit" name="btnConfirmDelete" value="CONFIRM RESTORE" class="btn btn-warning" />
    </form>

@stop
