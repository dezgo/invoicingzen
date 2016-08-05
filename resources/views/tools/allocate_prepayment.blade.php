@extends('web')

@section('content')
<h1>Allocate Prepayment</h1>

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

      var date_input=$('input[name="end_date"]');
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

<form action="/tools/allocate_prepayment" method="POST" class="form-horizontal">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <label for="start_date" class="col-sm-2 control-label">Start Date</label>
        <div class="col-sm-6">
            <input name="start_date" type="text" id="start_date" value="{{ old('start_date') }}" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label for="end_date" class="col-sm-2 control-label">End Date</label>
        <div class="col-sm-6">
            <input name="end_date" type="text" id="end_date" value="{{ old('end_date') }}" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label for="amount" class="col-sm-2 control-label">Amount</label>
        <div class="col-sm-6">
            <input name="amount" type="text" id="amount" value="{{ old('amount') }}" class="form-control">
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
        <th><b>Days</b></th>
        <th><b>Allocated</b></th>
    </tr>
@foreach ($data as $data_row)
    <tr>
        <td>{{ $data_row['start_of_month']->toDateString() }}</td>
        <td>{{ $data_row['end_of_month']->toDateString() }}</td>
        <td>{{ $data_row['days'] }}</td>
        <td>{{ $data_row['allocated'] }}</td>
    </tr>
@endforeach
</table>

@endif

@stop
