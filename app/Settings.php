<?php

namespace App;

use Setting as AnlutroSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;

class Settings
{
    /**
     * Ensure the logo is uploaded in the correct mimetype
     *
     * @param  int  $id
     * @return boolean
     */
    private static function checkLogoMimeType($file)
    {
        if ($file->getClientMimeType() == 'image/jpeg' ||
            $file->getClientMimeType() == 'image/png' ||
            $file->getClientMimeType() == 'image/gif'
            ) {
                return true;
        }

        if ($file->getClientMimeType() == "application/octet-stream") {
            if (strtolower($file->getExtension() == 'jpg' ||
                strtolower($file->getExtension()) == 'jpeg' ||
                strtolower($file->getExtension()) == 'png' ||
                strtolower($file->getExtension()) == 'gif'
                )) {
                    return true;
            }
        }
        return false;
    }

    public static function set($key, $value)
    {
        if (!Auth::check()) {
            throw new RuntimeException('Settings can\'t be updated without a logged in user');
        }

        AnlutroSetting::set($key, $value);
        AnlutroSetting::setExtraColumns(['company_id' => Auth::user()->company_id]);
        AnlutroSetting::save();
    }

    public static function get($key)
    {
        if (!Auth::check()) {
            throw new RuntimeException('Settings can\'t be retrieved without a logged in user');
        }

        AnlutroSetting::setExtraColumns(['company_id' => Auth::user()->company_id]);
        return AnlutroSetting::get($key);
    }

    public static function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'next_invoice_number' => 'numeric',
            'markup' => 'numeric',
            'bsb' => 'regex:/^\d{6}$/',
            'bank_account_number' => 'regex:/^\d{6,10}$/',
            'abn' => 'regex:/^\d{2} \d{3} \d{3} \d{3}$/',
            'payment_terms' => 'string',
            'mailing_address_line_1' => 'string',
            'mailing_address_line_2' => 'string',
            'mailing_address_line_3' => 'string',
            'enquiries_phone' => 'regex:/^[\d ()-]{6,14}$/',
            'enquiries_email' => 'email',
            'enquiries_web' => 'url',
            'email_port' => 'numeric',
            ]);

        if ($request->hasFile('logo')) {
            $validator->after(function($validator) {
                if (!Settings::checkLogoMimeType($validator->getFiles()['logo'])) {
                    $validator->errors()->add('logo', trans('settings.logo_format_validation'));
                }
            });
        }

        if ($validator->fails()) {
            return redirect('settings')
                    ->withErrors($validator)
                    ->withInput();
        }

        AnlutroSetting::set('next_invoice_number', $request->next_invoice_number);
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
            $request->file('logo')->move($destinationPath, Auth::user()->logo_filename);
        }
        if ($request->email_signature == '') {
            $email_signature = Settings::defaultEmailFooterText();
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
        AnlutroSetting::setExtraColumns(['company_id' => Auth::user()->company_id]);
        AnlutroSetting::save();
        $request->session()->flash('status', trans('settings.update_success'));
    }

    private static function defaultEmailFooterText()
	{
		return
			"<br />".
			"<br />".
			"PS. To view this invoice online, go to <a href='".url('/')."'>".
			url('/')."</a>. For first-time users, go to <a href='".
			url('/password/reset')."'>".url('/password/reset')."</a> to create a password.";
	}
}
