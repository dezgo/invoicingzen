@extends('web')

@section('content')
    <h1 align="left">Create Invoice Template</h1>

    @include('errors.list')
    <form method="POST" action="{{ secure_url('/invoice_template') }}" accept-charset="UTF-8">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="title" class="control-label">Title:</label>
            <input class="form-control" name="title" type="text" value="{{ old('title') }}" id="title">
        </div>

        <div class="form-group">
            <label for="type" class="control-label">Type:</label>
            <select class="form-control" name="type" value="{{ old('type') }}" id="type">
                <option value=""></option>
                <option value="receipt"{{ old('type') == 'receipt' ? ' selected' : ''}}>Receipt</option>
                <option value="invoice"{{ old('type') == 'invoice' ? ' selected' : ''}}>Invoice</option>
                <option value="quote"{{ old('type') == 'quote' ? ' selected' : ''}}>Quote</option>
            </select>
        </div>

        <div class="form-group">
            <label for="template" class="control-label">Template Content:</label>
            <textarea class="form-control" name="template" id="template" rows="25"
            >{{ old('template') }}</textarea>
        </div>

        <input class="btn btn-primary" id="btnSubmit" type="submit" value="Save">

    </form>

@stop
