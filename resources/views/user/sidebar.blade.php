<ul class="nav nav-tabs">
  <li role="presentation"{!! ($active_tab == 'account') ? ' class="active"' : '' !!}><a href="/user/{{ $user->id }}/edit">Account</a></li>
  <li role="presentation"{!! ($active_tab == 'subscription') ? ' class="active"' : '' !!}><a href="/subscription">Subscription</a></li>
  <li role="presentation"{!! ($active_tab == 'payments') ? ' class="active"' : '' !!}><a href="/payments">Payments</a></li>
  <li role="presentation"{!! ($active_tab == 'credit_card') ? ' class="active"' : '' !!}><a href="/card">Credit Card</a></li>
</ul>
<br />
