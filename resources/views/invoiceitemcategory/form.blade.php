<?php $options['class'] = 'control-label' ?>
<?php $options['autofocus'] = 'true' ?>

{{ Form::hidden('id') }}

<div class="form-group">
    {{ Form::label('description', 'Description:', ['class' => 'control-label']) }}
    {{ Form::text('description', null, $options) }}
</div>

{{ Form::submit($submitButtonText, ['class' => 'btn btn-primary']) }}
