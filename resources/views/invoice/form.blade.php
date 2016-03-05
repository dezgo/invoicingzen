<?php $options['class'] = 'form-control' ?>

{!! Form::hidden('id', null, $options) !!}

<div class="form-group">
    {!! Form::label('customer', 'Customer:', ['class' => 'control-abel freetext']) !!}

    <?php $options['id'] = 'customer_list' ?>
    {{ Form::select('customer_id', ['' => ''] + $customer_list->toArray(), null, $options) }}
    <?php unset($options['id']) ?>
</div>

<div class="form-group">
    {!! Form::label('invoice_number', 'Invoice Number:', ['class' => 'control-label freetext']) !!}
    {{ Form::text('invoice_number', null, $options) }}
</div>

<div class="form-group">
    {!! Form::label('is_quote', 'Is Quote:', ['class' => 'control-abel freetext']) !!}
    {!! Form::hidden('is_quote', '', $options) !!}
    {{ Form::checkbox('is_quote', 'on', null, $options) }}
</div>

<div class="form-group">
    {!! Form::label('paid', 'Amount Paid:', ['class' => 'control-abel freetext']) !!}
    {{ Form::text('paid', null, $options) }}
</div>

<div class="form-group">
    {!! Form::label('invoice_date', 'Invoice Date:', ['class' => 'control-abel freetext']) !!}

    <?php $options['id'] = 'invoice_date' ?>
    {{ Form::text('invoice_date', null, $options) }}
    <?php unset($options['id']) ?>
</div>

<div class="form-group">
    {!! Form::label('due_date', 'Due Date:', ['class' => 'control-abel freetext']) !!}

    <?php $options['id'] = 'due_date' ?>
    {{ Form::text('due_date', null, $options) }}
    <?php unset($options['id']) ?>
</div>

@if(Gate::check('admin'))
{!! Form::submit($submitButtonText, ['class' => 'btn btn-primary', 'id' => 'btnSubmit']) !!}
@endif

@section('footer1')
    <script type="text/javascript">
        $('#invoice_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#due_date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#customer_list').select2({
            placeholder: 'Choose a customer',
            tags: false,
            theme: "classic"
        });
    </script>
@stop
