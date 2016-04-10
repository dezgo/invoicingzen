<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		return view('admin.settings', compact('settings'));
	}

	/**
	 * Ensure the logo is uploaded in the correct mimetype
	 *
	 * @param  int  $id
	 * @return boolean
	 */
	private function checkLogoMimeType($file)
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

    public function update(Request $request)
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
			]);

		if ($request->hasFile('logo')) {
			$validator->after(function($validator) {
			    if (!$this->checkLogoMimeType($validator->getFiles()['logo'])) {
			        $validator->errors()->add('logo', trans('settings.logo_format_validation'));
			    }
			});
		}

		if ($validator->fails()) {
			return redirect('settings')
					->withErrors($validator)
					->withInput();
		}

		\Setting::set('next_invoice_number', $request->next_invoice_number);
		\Setting::set('markup', $request->markup);
		\Setting::set('abn', $request->abn);
		\Setting::set('payment_terms', $request->payment_terms);
		\Setting::set('bsb', $request->bsb);
		\Setting::set('bank_account_number', $request->bank_account_number);
		\Setting::set('mailing_address_line_1', $request->mailing_address_line_1);
		\Setting::set('mailing_address_line_2', $request->mailing_address_line_2);
		\Setting::set('mailing_address_line_3', $request->mailing_address_line_3);
		\Setting::set('enquiries_phone', $request->enquiries_phone);
		\Setting::set('enquiries_email', $request->enquiries_email);
		\Setting::set('enquiries_web', $request->enquiries_web);
		if ($request->hasFile('logo')) {
			\Setting::set('logo', $request->file('logo'));
			$destinationPath = public_path().'/images';
			$request->file('logo')->move($destinationPath, Auth::user()->logo_filename);
		}
		if ($request->email_signature == '') {
			$email_signature = $this->defaultEmailFooterText();
		}
		else {
			$email_signature = $request->email_signature;
		}
		\Setting::set('email_signature', $email_signature);
		\Setting::set('email_host', $request->email_host);
		\Setting::set('email_port', $request->email_port);
		\Setting::set('email_username', $request->email_username);
		\Setting::set('email_password', $request->email_password);
		\Setting::set('email_encryption', $request->email_encryption);
		\Setting::setExtraColumns(['company_id' => Auth::user()->company_id]);
        \Setting::save();
        $request->session()->flash('status', trans('settings.update_success'));
        return redirect(url('/settings'));
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
}
