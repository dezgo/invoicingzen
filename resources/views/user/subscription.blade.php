@extends('web')

@section('content')
@include('user.sidebar')

<h1>Your Subscription</h1>
<form>
<div class="form-group">
    <label for="subscription" class="control-label">Subscription:</label>
    <select class="form-control" name="subscription">
        <option value="free" value="free">Free</option>
        <option value="standard" value="free">Standard (A$9)</option>
        <option value="premium" value="free">Premium (A$19)</option>
    </select>
</div>

<div class="form-group">
    <label for="voucher" class="control-label">Voucher:</label>
    <input type="text" class="form-control" name="voucher" />
</div>

<input type="submit" id="btnUpdate" value="Update" class="btn btn-success" />
</form>
@stop
