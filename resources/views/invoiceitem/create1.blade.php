@extends('web')

@section('content')

    <h1 align="left">Create Invoice Item for invoice {{ $invoice->description }}</h1>
    <h2>Step 1 - Select category</h2>
    {!! Form::open(['route' => ['invoice_item.store1', $invoice]]) !!}
    {!! Form::hidden('invoice_id', $invoice->id) !!}

    <div class="form-group">
        {!! Form::label('category', 'Category:', ['class' => 'control-label']) !!}

        {!! Form::select('category_id', ['' => ''] + $invoice_item_categories->toArray(), null,
            ['class' => 'form-control', 'id' => 'category_list']) !!}
    </div>

    {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}

    {!! Form::close() !!}

    @include('errors.list')
@stop

@section('footer')
<script type="text/javascript">
$('#category_list').select2({
    placeholder: "Choose a category",
    tags: false,
    theme: "classic"
});
</script>
@stop
