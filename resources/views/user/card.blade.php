@extends('web')

@section('content')
@include('user.sidebar')
@include('includes.flash_message_content')

<h1>{{ $user->hasStripeId() ? 'Update' : 'Add' }} Credit Card Details</h1>
Note that only the last 4 digits of your credit card number
are stored on our servers. Payment processing and storage of full credit card
details is done by a 3rd-party provider - stripe.com.<br />
<br />

<form method="POST" url="{{ route('user.card.update') }}" class="form-inline" id="payment-form">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PATCH">
    <ul class="alert-danger">
        <span class="payment-errors alert-danger"></span>
    </ul>

    <div class="form-row">
        <label for="card_number" class="control-label">Card Number:</label>
        <input type="text" size="20" class="form-control" data-stripe="number"{{ env('APP_ENV') == 'testing' ? " name=card_number" : '' }}
            placeholder="**** **** **** {{ $user->card_last_four == '' ? '****' : $user->card_last_four }}" />
    </div>
<br />
    <div class="form-row">
        <label for="exp_month" class="control-label">Expiration (MM/YY):</label>
        <input type="text" size="2" data-stripe="exp_month" class="control-label"{{ env('APP_ENV') == 'testing' ? " name=exp_month" : '' }} />
        <span> / </span>
        <input type="text" size="2" data-stripe="exp_year" class="control-label"{{ env('APP_ENV') == 'testing' ? " name=exp_year" : '' }} />
    </div>
    <br />
    <div class="form-row">
        <label for="cvc_number" class="control-label">CVC Number:</label>
        <input type="text" size="4" data-stripe="cvc" class="control-label"{{ env('APP_ENV') == 'testing' ? " name=cvc" : '' }} />
    </div>
    <br />
    <div class="form-row">
        <label for="address_zip" class="control-label">Postcode:</label>
        <input type="text" size="6" data-stripe="address_zip"{{ env('APP_ENV') == 'testing' ? " name=address_zip" : '' }} />
    </div>
    <br />
    <input type="submit" value="Update" class="btn btn-success" id='btnUpdate'/>

</form>
@stop

@section('footer')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
  Stripe.setPublishableKey('{{ env('STRIPE_KEY') }}');
</script>

<script type="text/javascript">
$(function() {
  var $form = $('#payment-form');
  $form.submit(function(event) {
    // Disable the submit button to prevent repeated clicks:
    $form.find('#btnUpdate').prop('disabled', true);
    $form.find('#btnUpdate').attr('value', 'Processing...');

    // Request a token from Stripe:
    Stripe.card.createToken($form, stripeResponseHandler);

    // Prevent the form from being submitted:
    return false;
  });
});

function stripeResponseHandler(status, response) {
  // Grab the form:
  var $form = $('#payment-form');

  if (response.error) { // Problem!

    // Show the errors on the form:
    $form.find('.payment-errors').text(response.error.message);
    $form.find('#btnUpdate').prop('disabled', false); // Re-enable submission
    $form.find('#btnUpdate').attr('value', 'Update');

  } else { // Token was created!

    // Get the token ID:
    var token = response.id;

    // Insert the token ID into the form so it gets submitted to the server:
    $form.append($('<input type="hidden" name="stripeToken">').val(token));

    // Submit the form:
    $form.get(0).submit();
  }
};
</script>
@stop
