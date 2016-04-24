@extends('web')

@section('content')
    <h1 align="left">Edit Invoice Template</h1>

    @include('errors.list')
    <form method="POST" action="{{ secure_url('/invoice_template/'.$invoice_template->id) }}" accept-charset="UTF-8">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="title" class="control-label">Title:</label>
            <input class="form-control" name="title" type="text" value="{{ $invoice_template->title }}" id="title">
        </div>

        <div class="form-group">
            <label for="template" class="control-label">Template Content:</label>
            <textarea class="form-control" name="template" id="template" rows="25"
            >{{ $invoice_template->template }}</textarea>
        </div>

        <input class="btn btn-primary" id="btnSubmit" type="submit" value="Update">
        <a class="btn btn-danger" id="linkDelete"
            href="{{ secure_url('/invoice_template/'.$invoice_template->id.'/delete') }}">Delete</a>

    </form>

@stop
