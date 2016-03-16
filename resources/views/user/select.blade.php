@extends('web')

@section('content')
    <h1 align="left">Create Invoice</h1>

    {!! Form::open(['url' => '/user/select']) !!}

    <!-- Customer Form Input -->
    <div class="form-group">
        {!! Form::label('customer', 'Pick a customer:', ['class' => 'control-label']) !!}
        {!! Form::select('customer', ['' => ''] + $customer_list->toArray(), null, ['id' => 'customer_list', 'class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

    <div class="form-group">
        {!! Form::label('customer', 'or', ['class' => 'control-label']) !!}
    </div>

    <div class="form-group">
        <a href="/user/create?flag=1" type="button" class="btn btn-primary">
            Create a new customer
        </a>
    </div>

    @include('errors.list')
@stop

@section('footer')
    <script type="text/javascript">
        $('#customer_list').select2({
            placeholder: 'Choose a customer',
            tags: false,
            theme: "classic"
        });
    </script>
@stop
