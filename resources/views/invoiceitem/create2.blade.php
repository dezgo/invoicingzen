@extends('master')

@section('content')

    <h1 align="left">Create Invoice Item for invoice {{ $invoice->description }}</h1>
    <h2>Step 2 - Enter/select description</h2>
    {!! Form::open(['route' => ['invoice_item.store2', $invoice, $category]]) !!}
    {!! Form::hidden('invoice_id', $invoice->id) !!}

    <div class="form-group">
        {!! Form::label('category', 'Category:', ['class' => 'control-label']) !!}<br>
        {!! Form::text('category_description', $category->description, ['class' => 'control-label', 'disabled' => 'true']) !!}
        {!! Form::hidden('category_id', $category->id) !!}
    </div>

    <div class="form-group">
        {!! Form::label('description_label', 'Description:', ['class' => 'control-label']) !!}
        {!! Form::select('description', ['' => ''] + $invoice_item_list->toArray(),
            null, ['class' => 'form-control', 'id' => 'description_list'] ) !!}
    </div>

    {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}

    {!! Form::close() !!}

    @include('errors.list')
@stop

@section('footer')

<script type="text/javascript">
$('#description_list').select2({
    placeholder: "Choose a description",
    tags: true,
    theme: "classic"
});

</script>
@stop
