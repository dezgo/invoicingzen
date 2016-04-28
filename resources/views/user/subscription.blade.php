@extends('web')

@section('content')
@include('user.sidebar')
{!! Form::open(['method' => 'POST', 'url' => url('/user/subscribe')]) !!}
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="{{ env('STRIPE_KEY') }}"
    data-amount="{{ 900 }}"
    data-name="Invoicing Zen"
    data-description="InvoicingZen Standard"
    data-image="{{ url('/images/'.Auth::user()->company->logofilename) }}"
    data-locale="auto">
  </script>
  {!! Form::close() !!}
@stop
