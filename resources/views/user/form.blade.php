<?php $options['class'] = 'form-control' ?>

{{ Form::hidden('id') }}

<!-- First_name Form Input -->
<div class="form-group">
    {!! Form::label('first_name', 'First name:', ['class' => 'control-label']) !!}
    {{ Form::text('first_name', null, $options) }}
</div>

<!-- Last name Form Input -->
<div class="form-group">
    {!! Form::label('last_name', 'Last name:', ['class' => 'control-label']) !!}
    {{ Form::text('last_name', null, $options) }}
</div>

<!-- Email Form Input -->
<div class="form-group">
    {!! Form::label('email', 'Email:', ['class' => 'control-label']) !!}
    {{ Form::text('email', null, $options) }}
</div>

<!-- Email Form Input -->
<div class="form-group">
    {!! Form::label('business_name', 'Business Name:', ['class' => 'control-label']) !!}
    {{ Form::text('business_name', null, $options) }}
</div>

<!-- Address 1 Form Input -->
<div class="form-group">
    {!! Form::label('address1', 'Address:', ['class' => 'control-label']) !!}
    {{ Form::text('address1', null, $options) }}
    {{ Form::text('address2', null, $options) }}
</div>

<!-- Suburb Form Input -->
<div class="form-group">
    {!! Form::label('suburb', 'Suburb:', ['class' => 'control-label']) !!}
    {{ Form::text('suburb', null, $options) }}
</div>

<!-- State Form Input -->
<?php $options['id'] = 'state_list' ?>
<div class="form-group">
    {!! Form::label('state', 'State:', ['class' => 'control-label']) !!}<Br />
    {{ Form::select('state', [
            '' => '',
            'ACT' => 'ACT',
            'NSW' => 'NSW',
            'SA' => 'SA',
            'WA' => 'WA',
            'TAS' => 'TAS',
            'NT' => 'NT',
            'QLD' => 'QLD',
            'VIC' => 'VIC'
        ], null, $options) }}
</div>
<?php unset($options['id']) ?>

<!-- Postcode Form Input -->
<div class="form-group">
    {!! Form::label('postcode', 'Postcode:', ['class' => 'control-label']) !!}
    {{ Form::text('postcode', null, $options) }}
</div>

{!! Form::submit($submitButtonText, ['id' => 'btnSubmit', 'class' => 'btn btn-primary']) !!}

@section('footer1')
    <script type="text/javascript">
        $('#state_list').select2({
            placeholder: 'Choose a state',
            tags: false,
            theme: "classic"
        });
    </script>
@stop
