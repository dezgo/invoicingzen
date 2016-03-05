<?php $options['class'] = 'form-control' ?>

    {!! Form::hidden('id', null, $options) !!}
    {!! Form::hidden('invoice_id', $invoice_id, $options) !!}

    <div class="form-group">
        {!! Form::label('category', 'Category:', ['class' => 'control-label']) !!}

        <?php $options['id'] = 'category_list' ?>
        {{ Form::select('category_id', ['' => ''] + $invoice_item_categories->toArray(), null, $options) }}
        <?php unset($options['id']) ?>
    </div>

    <div class="form-group">
        {{ Form::label('description_label', 'Description:', ['class' => 'control-label']) }}

        <?php $options['id'] = 'description_list' ?>
        {{ Form::select('description', ['' => ''] + $invoice_item_list->toArray(), null, $options) }}
        <?php unset($options['id']) ?>
    </div>

    <div class="form-group">
        {{ Form::label('quantity', 'Quantity:', ['class' => 'control-label']) }}
        {{ Form::text('quantity', null, $options) }}
    </div>

    <div class="form-group">
        {!! Form::label('price', 'Price:', ['class' => 'control-label']) !!}

        <?php $options['autofocus'] = 'true' ?>
        {{ Form::text('price', null, $options) }}
        <?php unset($options['autofocus']) ?>
    </div>

    {!! Form::submit($submitButtonText, ['class' => 'btn btn-success']) !!}

    @if (!array_key_exists("disabled",$options))
    <a class="btn btn-primary" id='btnMarkup'>
        <i class="fa fa-btn fa-arrow-up"></i></a>
    <a class="btn btn-primary" id='btnMarkDown'>
        <i class="fa fa-btn fa-arrow-down"></i></a>
    @endif

@section('footer1')
<script type="text/javascript">
$( document ).ready(function() {
    var markUp = 1+{{ Setting::get('markup', 10)/100 }};
    $("#btnMarkup").click(function(){
        var newPrice = $('#price').val()*markUp;
        newPrice = newPrice.toFixed(2);
        $('#price').val(newPrice);
    });
    $("#btnMarkDown").click(function(){
        var newPrice = $('#price').val()/markUp;
        newPrice = newPrice.toFixed(2);
        $('#price').val(newPrice);
    });

    $('#category_list').select2({
        placeholder: "Choose a category",
        tags: false,
        theme: "classic"
    });

    $('#description_list').select2({
        placeholder: "Choose an item",
        tags: true,
        theme: "classic",
    });
});
</script>
@stop
