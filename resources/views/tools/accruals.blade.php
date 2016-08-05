@extends('web')

@section('content')
<h1>Accruals Generator</h1>

<script>
    $(document).ready(function(){
      var date_input=$('input[name="start_date"]');
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        container: container,
        todayHighlight: true,
        autoclose: true,
        dateFormat: 'dd-mm-yy',
      };
      date_input.datepicker(options);

      var date_input=$('input[name="payment_date"]');
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        container: container,
        todayHighlight: true,
        autoclose: true,
        dateFormat: 'dd-mm-yy',
      };
      date_input.datepicker(options);
    })
</script>

<form action="/tools/accruals" method="POST" class="form-horizontal">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <label for="start_date" class="col-sm-2 control-label">Start Date</label>
        <div class="col-sm-6">
            <input name="start_date" type="text" id="start_date" value="{{ old('start_date') }}" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label for="amount" class="col-sm-2 control-label">Amount</label>
        <div class="col-sm-6">
            <input name="amount" type="text" id="amount" value="{{ old('amount') }}" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label for="every_n_days" class="col-sm-2 control-label">Every N Days</label>
        <div class="col-sm-6">
            <input name="every_n_days" type="text" id="every_n_days" value="{{ old('every_n_days') }}" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label for="periods" class="col-sm-2 control-label">Periods</label>
        <div class="col-sm-6">
            <input name="periods" type="text" id="periods" value="{{ old('periods') }}" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-2">
            <input name="submit" type="submit" id="submit" class="form-control" value='Generate'>
        </div>
    </div>

</form>

@if (isset($data))

<table class="table">
    <tr>
        <th><b>Start</b></th>
        <th><b>End</b></th>
        <th><b>Amount</b></th>
    </tr>
@foreach ($data as $data_row)
    <tr>
        <td>{{ $data_row['start_date']->toDateString() }}</td>
        <td>{{ $data_row['end_date']->toDateString() }}</td>
        <td>{{ $data_row['amount'] }}</td>
    </tr>
@endforeach
</table>

@endif

@stop
