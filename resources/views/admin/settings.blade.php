@extends('web')

@section('content')
@include('includes.flash_message_content')

    <h1 align="left">{{ trans('settings.title') }}</h1>

    {!! Form::open(['route' => 'settings.update', 'files' => true]) !!}

    <div class="form-group">
        {!! Form::label('taxable', trans('settings.taxable'), ['class' => 'control-label']) !!}<Br />
        {{ Form::checkbox('taxable', 'on', $settings->get('taxable'), ['class' => 'form-control', 'data-size' => 'mini']) }}
    </div>

    <div class="form-group">
        {!! Form::label('markup', trans('settings.markup'), ['class' => 'control-label']) !!}
        {{ Form::text('markup', $settings->get('markup'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('bsb', trans('settings.bsb'), ['class' => 'control-label']) !!}
        {{ Form::text('bsb', $settings->get('bsb'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('bank_account_number', trans('settings.bank_account_number'), ['class' => 'control-label']) !!}
        {{ Form::text('bank_account_number', $settings->get('bank_account_number'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('abn', trans('settings.abn'), ['class' => 'control-label']) !!}
        {{ Form::text('abn', $settings->get('abn'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('payment_terms', trans('settings.payment_terms'), ['class' => 'control-label']) !!}
        {{ Form::text('payment_terms', $settings->get('payment_terms'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('mailing_address_line_1', trans('settings.mailing_address_line_1'), ['class' => 'control-label']) !!}
        {{ Form::text('mailing_address_line_1', $settings->get('mailing_address_line_1'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('mailing_address_line_2', trans('settings.mailing_address_line_2'), ['class' => 'control-label']) !!}
        {{ Form::text('mailing_address_line_2', $settings->get('mailing_address_line_2'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('mailing_address_line_3', trans('settings.mailing_address_line_3'), ['class' => 'control-label']) !!}
        {{ Form::text('mailing_address_line_3', $settings->get('mailing_address_line_3'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('enquiries_phone', trans('settings.enquiries_phone'), ['class' => 'control-label']) !!}
        {{ Form::text('enquiries_phone', $settings->get('enquiries_phone'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('enquiries_email', trans('settings.enquiries_email'), ['class' => 'control-label']) !!}
        {{ Form::text('enquiries_email', $settings->get('enquiries_email'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('enquiries_web', trans('settings.enquiries_web'), ['class' => 'control-label']) !!}
        {{ Form::text('enquiries_web', $settings->get('enquiries_web'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('logo', trans('settings.logo'), ['class' => 'control-label']) !!}
        <small>Expecting JPEG format</small>
        {{ Form::file('logo') }}
    </div>

    <div class="form-group">
        {!! Form::label('email_signature', trans('settings.email_signature'), ['class' => 'control-label']) !!}
        {{ Form::textarea('email_signature', $settings->get('email_signature'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('email_prepopulate', trans('settings.email_prepopulate'), ['class' => 'control-label']) !!}
        {{ Form::select('email_prepopulate', $email_providers, ['class' => 'form-control']) }}
        <i class="fa fa-question-circle" data-toggle="modal" data-target="#helpEmailPrepopulate"></i>

        <div class="modal fade" id="helpEmailPrepopulate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('settings.close_button') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('settings.help_email_prepopulate_title') }}</h4>
              </div>
              <div class="modal-body">
                  {!! trans('settings.help_email_prepopulate_body') !!}
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('settings.close_button') }}</button>
              </div>
            </div>
          </div>
        </div>
        {{ Form::text('email_host', $settings->get('email_host'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('email_host', trans('settings.email_host'), ['class' => 'control-label']) !!}
        <i class="fa fa-question-circle" data-toggle="modal" data-target="#helpEmailHost"></i>

        <div class="modal fade" id="helpEmailHost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('settings.close_button') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('settings.help_email_host_title') }}</h4>
              </div>
              <div class="modal-body">
                {!! trans('settings.help_email_host_body') !!}
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('settings.close_button') }}</button>
              </div>
            </div>
          </div>
        </div>
        {{ Form::text('email_host', $settings->get('email_host'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('email_port', trans('settings.email_port'), ['class' => 'control-label']) !!}
        <i class="fa fa-question-circle" data-toggle="modal" data-target="#helpEmailPort"></i>

        <div class="modal fade" id="helpEmailPort" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('settings.close_button') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('settings.help_email_port_title') }}</h4>
              </div>
              <div class="modal-body">
                {!! trans('settings.help_email_port_body') !!}
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('settings.close_button') }}</button>
              </div>
            </div>
          </div>
        </div>
        {{ Form::text('email_port', $settings->get('email_port'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('email_username', trans('settings.email_username'), ['class' => 'control-label']) !!}
        <i class="fa fa-question-circle" data-toggle="modal" data-target="#helpEmailUsername"></i>

        <div class="modal fade" id="helpEmailUsername" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('settings.close_button') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('settings.help_email_username_title') }}</h4>
              </div>
              <div class="modal-body">
                {!! trans('settings.help_email_username_body') !!}
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('settings.close_button') }}</button>
              </div>
            </div>
          </div>
        </div>
        {{ Form::text('email_username', $settings->get('email_username'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {!! Form::label('email_password', trans('settings.email_password'), ['class' => 'control-label']) !!}
        <i class="fa fa-question-circle" data-toggle="modal" data-target="#helpEmailPassword"></i>

        <div class="modal fade" id="helpEmailPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('settings.close_button') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('settings.help_email_password_title') }}</h4>
              </div>
              <div class="modal-body">
                {!! trans('settings.help_email_password_body') !!}
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('settings.close_button') }}</button>
              </div>
            </div>
          </div>
        </div>
        <input type='password' id='email_password' name='email_password' value='{{ $settings->get('email_password') }}' class='form-control' />
    </div>

    <div class="form-group">
        {!! Form::label('email_encryption', trans('settings.email_encryption'), ['class' => 'control-label']) !!}
        <i class="fa fa-question-circle" data-toggle="modal" data-target="#helpEmailEncryption"></i>

        <div class="modal fade" id="helpEmailEncryption" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('settings.close_button') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('settings.help_email_encryption_title') }}</h4>
              </div>
              <div class="modal-body">
                {!! trans('settings.help_email_encryption_body') !!}
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('settings.close_button') }}</button>
              </div>
            </div>
          </div>
        </div>
        {{ Form::text('email_encryption', $settings->get('email_encryption'), ['class' => 'form-control']) }}
    </div>

    {!! Form::submit(trans('settings.update_button'), ['class' => 'btn btn-primary', 'id' => 'btnSubmit']) !!}

    {!! Form::close() !!}

    @include('errors.list')
@stop

@section('footer')
<script language="Javascript">
$(document).ready (function(){
    $("[name='taxable']").bootstrapSwitch();

    $("[name='email_prepopulate']").on('change', function() {
        switch(this.value) {
            @foreach ($provider_settings as $provider_setting)
            case '{!! $provider_setting['Provider'] !!}':
                $('#email_host').val('{!! $provider_setting['Host'] !!}');
                $('#email_port').val('{!! $provider_setting['Port'] !!}');
                $('#email_encryption').val('{!! $provider_setting['Encryption'] !!}');
                break;
            @endforeach
        }
    });

    $(document).ready(function() {
        $("i.fa-question-circle").popover({'trigger':'click'});
        $("i.fa-question-circle").css('cursor', 'pointer');
    });
});
</script>

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'email_signature' );
    </script>
@stop
