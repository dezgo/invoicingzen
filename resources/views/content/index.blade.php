@extends('web')

@section('content')

  <div class="row">
      <div class="col-md-5">
          <div class="panel panel-default">
              <div class="panel-heading">Need a simple invoicing system?</div>
              <div class="panel-body">
                  Invoicing Zen is an Australian-based service designed for businesses
                  needing a quick and simple invoicing system.<br />
                  <br />
                  It's currently in Beta, so sign
                  up and give it a go. We welcome any feedback,
                  while it's in Beta you can use it for free.<br />
                  <br />
                  If you're interested in the progress we've made so far, check
                  out the <u><a href="/release-notes">release notes</a></u>.
              </div>
          </div>
      </div>

      <div class="col-md-5 col-md-offset-2">
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

                      <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                          <label class="col-md-4 control-label">Confirm Password</label>

                          <div class="col-md-6">
                              <input type="password" class="form-control" name="password_confirmation">

                              @if ($errors->has('password_confirmation'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('password_confirmation') }}</strong>
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
      </div>
  </div>

@stop
