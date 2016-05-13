@extends('email')

@section('content')
Hello,<br />
<br />
Someone recently requested a reset of your password at InvoicingZen.com. If this wasn't you,
you can safely delete this email.<br />
<br />
If this was you, click the button below to reset your password now:<br />
<a class="btn btn-primary" href="{{ $link = secure_url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">Reset Password</a><br />
<Br />
Or if that doesn't work, try clicking the below link, or copying and pasting it into
your browser address bar:<br />
<a href="{{ $link = secure_url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a><br />
<br />
Sincerely,<br />
<br />
Derek<br />
InvoicingZen.com
@stop
