@extends('web')

@section('content')
@include('user.sidebar')

<h1>Update Credit Card Details</h1>
Note that only the last 4 digits of your credit card number
are stored on our servers. Payment processing and storage of full credit card
details is done by a 3rd-party provider - stripe.com.<br />
<br />

<form method='POST' action='/user/updatecc' id='payment-form' class="form-inline">
    {{ csrf_field() }}
    <ul class="alert-danger">
        <span class="payment-errors alert-danger"></span>
    </ul>

    <div class="form-row">
        <label for="card_number" class="control-label">Card Number:</label>
        <input type="text" size="20" class="form-control" data-stripe="number"
            placeholder="**** **** **** {{ $user->card_last_four == '' ? '****' : $user->card_last_four }}" />
    </div>
<br />
    <div class="form-row">
        <label for="expiration_month" class="control-label">Expiration (MM/YY):</label>
        <input type="text" size="2" data-stripe="exp_month" class="control-label"
        <span> / </span>
        <input type="text" size="2" data-stripe="exp_year" class="control-label">
    </div>
    <br />
    <div class="form-row">
        <label for="cvc_number" class="control-label">CVC Number:</label>
        <input type="text" size="4" data-stripe="cvc" class="control-label">
    </div>
    <br />
    <div class="form-row">
        <label for="address_zip" class="control-label">Postcode:</label>
        <input type="text" size="6" data-stripe="address_zip"  />
    </div>
    <br />
    <input type="submit" name="btnUpdate" value="Update" class="btn btn-success" />

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
    $form.find('.submit').prop('disabled', true);

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
    $form.find('.submit').prop('disabled', false); // Re-enable submission

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
