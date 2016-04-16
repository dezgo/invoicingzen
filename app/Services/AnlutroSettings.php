<?php

namespace App\Services;

use Setting as AnlutroSetting;
use App\User;
use Illuminate\Http\Request;
use App\Contracts\Settings as SettingsContract;
use Illuminate\Support\Facades\Auth;

class AnlutroSettings implements SettingsContract
{
    private $user;

    public function __construct()
    {
        if (Auth::check()) {
            $this->user = Auth::user();
        }
        else {
            throw new \RuntimeException('No logged in user!');
        }
    }

    public function set($key, $value)
    {
        AnlutroSetting::setExtraColumns(['company_id' => $this->user->company_id]);
        AnlutroSetting::set($key, $value);
        AnlutroSetting::save();
    }

    public function get($key, $default = null)
    {
        AnlutroSetting::setExtraColumns(['company_id' => $this->user->company_id]);
        if ($default == null) {
            return AnlutroSetting::get($key);
        }
        else {
            return AnlutroSetting::get($key, $default);
        }
    }

    public function setAllUsing(Request $request)
    {
        AnlutroSetting::set('markup', $request->markup);
        AnlutroSetting::set('abn', $request->abn);
        AnlutroSetting::set('payment_terms', $request->payment_terms);
        AnlutroSetting::set('bsb', $request->bsb);
        AnlutroSetting::set('bank_account_number', $request->bank_account_number);
        AnlutroSetting::set('mailing_address_line_1', $request->mailing_address_line_1);
        AnlutroSetting::set('mailing_address_line_2', $request->mailing_address_line_2);
        AnlutroSetting::set('mailing_address_line_3', $request->mailing_address_line_3);
        AnlutroSetting::set('enquiries_phone', $request->enquiries_phone);
        AnlutroSetting::set('enquiries_email', $request->enquiries_email);
        AnlutroSetting::set('enquiries_web', $request->enquiries_web);
        if ($request->hasFile('logo')) {
            AnlutroSetting::set('logo', $request->file('logo'));
            $destinationPath = public_path().'/images';
            $request->file('logo')->move($destinationPath, $this->user->logo_filename);
        }
        if ($request->email_signature == '') {
            $email_signature = $this->defaultEmailFooterText();
        }
        else {
            $email_signature = $request->email_signature;
        }
        AnlutroSetting::set('email_signature', $email_signature);
        AnlutroSetting::set('email_host', $request->email_host);
        AnlutroSetting::set('email_port', $request->email_port);
        AnlutroSetting::set('email_username', $request->email_username);
        AnlutroSetting::set('email_password', $request->email_password);
        AnlutroSetting::set('email_encryption', $request->email_encryption);
        AnlutroSetting::setExtraColumns(['company_id' => $this->user->company_id]);
        AnlutroSetting::save();
    }

    private function defaultEmailFooterText()
	{
		return
			"<br />".
			"<br />".
			"PS. To view this invoice online, go to <a href='".url('/')."'>".
			url('/')."</a>. For first-time users, go to <a href='".
			url('/password/reset')."'>".url('/password/reset')."</a> to create a password.";
	}

    public function checkEmailSettings()
    {
        $rtn =
            AnlutroSetting::get('email_host') != '' and
            AnlutroSetting::get('email_port') != '' and
            AnlutroSetting::get('email_username') != '' and
            AnlutroSetting::get('email_password') != '' and
            AnlutroSetting::get('email_encryption') != '';

        return $rtn;
    }
}
