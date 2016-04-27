@extends('web')

@section('content')

<table>
    <tr valign="top">
        <td width="45%">
            <div class="panel panel-default">
                <div class="panel-heading">Need a simple invoicing system?</div>
                <div class="panel-body">
                    Hi, I'm Derek. I wrote InvoicingZen to fill a need I had in
                    my <a target="_blank" href="http://computerwhiz.com.au">own
                    small business</a> for a simple invoicing system without the
                    high costs charged by many of the online systems.<br />
                </div>
            </div>
            <Br />
            <div class="panel panel-default">
                <div class="panel-heading">What does it do?</div>
                <div class="panel-body">
                    Here's what you get:<br />
                    <ul>
                        <li>Works for GST and non-GST registered businesses</li>
                        <li>Custom Invoice/Quote/Receipt Templates</li>
                        <li>Ability to upload your own logo</li>
                        <li>Invoices viewable online and downloadable as PDF</li>
                        <li>One-click to mark invoice as paid/unpaid</li>
                        <li>Peace-of-mind - SSL encrypts all data communications
                            between browser and server</li>
                        <li>Email invoices using your own email address</li>
                        <li>Customise email signature</li>
                        <li>Customers get link in email which takes them straight
                            to their invoice in one click</li>
                        <li>Lightning-fast filter on invoice list page to quickly
                            find invoices</li>
                        <li>Missing something? <a href="/contact">Let me know</a></li>
                    </ul>
                </div>
            </div>
        </td>
        <td width="10%">&nbsp;</Td>
        <td width="45%">
          <div class="panel panel-default">
              <div class="panel-heading">Sign Up. It's free!</div>
              <div class="panel-body">
                  <form class="form-horizontal" role="form" method="POST" action="/register">
                      {!! csrf_field() !!}

                      <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                          <label class="col-md-4 control-label">First Name</label>

                          <div class="col-md-6">
                              <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">

                              @if ($errors->has('first_name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('first_name') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                          <label class="col-md-4 control-label">Last Name</label>

                          <div class="col-md-6">
                              <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">

                              @if ($errors->has('last_name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('last_name') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group{{ $errors->has('business_name') ? ' has-error' : '' }}">
                          <label class="col-md-4 control-label">Business Name</label>

                          <div class="col-md-6">
                              <input type="text" class="form-control" name="business_name" value="{{ old('business_name') }}">

                              @if ($errors->has('business_name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('business_name') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                          <label class="col-md-4 control-label">E-Mail Address</label>

                          <div class="col-md-6">
                              <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                              @if ($errors->has('email'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('email') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                          <label class="col-md-4 control-label">Password</label>

                          <div class="col-md-6">
                              <input type="password" class="form-control" name="password">

                              @if ($errors->has('password'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('password') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-3 col-md-offset-4">
                              <button id="btnSignUp" type="submit" class="btn btn-primary">
                                  <i class="fa fa-btn fa-user"></i>Sign Up
                              </button>
                          </div>
                          <div class="col-md-5">
                              <a href='/login' name='linkExistingUser'>Existing User? Login</a>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </td>
  </tr>
</table>

@stop
