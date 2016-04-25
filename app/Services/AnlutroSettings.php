<?php

namespace App\Services;

use Setting as AnlutroSetting;
use App\Company;
use Illuminate\Http\Request;
use App\Contracts\Settings as SettingsContract;
use Illuminate\Support\Facades\Auth;

class AnlutroSettings implements SettingsContract
{
    private $company_id;
    private $valid_settings = [
        'taxable',
        'markup',
        'bsb',
        'bank_account_number',
        'abn',
        'payment_terms',
        'mailing_address_line_1',
        'mailing_address_line_2',
        'mailing_address_line_3',
        'enquiries_phone',
        'enquiries_email',
        'enquiries_web',
        'logo',
        'email_signature',
        'email_host',
        'email_port',
        'email_username',
        'email_password',
        'email_encryption',
    ];

    public function __construct($company_id = 0)
    {
        if ($company_id === 0) {
            if (Auth::check()) {
                $this->company_id = Auth::user()->company_id;
            }
            else {
                throw new \RuntimeException('No logged in user!');
            }
        }
        else {
            $this->company_id = $company_id;
        }
    }

    public function set($key, $value)
    {
        if (in_array($key, $this->valid_settings)) {
            AnlutroSetting::setExtraColumns(['company_id' => $this->company_id]);
            AnlutroSetting::set($key, $value);
            AnlutroSetting::save();
        } else {
            throw new \RuntimeException('\''.$key.'\' not a valid setting');
        }
    }

    public function get($key, $default = null)
    {
        if (in_array($key, $this->valid_settings)) {
            AnlutroSetting::setExtraColumns(['company_id' => $this->company_id]);
            if ($default == null) {
                return AnlutroSetting::get($key);
            }
            else {
                return AnlutroSetting::get($key, $default);
            }
        } else {
            throw new \RuntimeException('\''.$key.'\' not a valid setting');
        }
    }

    public function setAllUsing(Request $request)
    {
        AnlutroSetting::set('taxable', $request->taxable != null);
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
            $company = Company::find($this->company_id);
            $request->file('logo')->move($destinationPath, $company->logo_filename);
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
        AnlutroSetting::setExtraColumns(['company_id' => $this->company_id]);
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
        AnlutroSetting::setExtraColumns(['company_id' => $this->company_id]);
        $rtn =
            AnlutroSetting::get('email_host') != '' and
            AnlutroSetting::get('email_port') != '' and
            AnlutroSetting::get('email_username') != '' and
            AnlutroSetting::get('email_password') != '' and
            AnlutroSetting::get('email_encryption') != '';

        return $rtn;
    }
}
