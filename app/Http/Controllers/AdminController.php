<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Factories\SettingsFactory;

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
        $settings = SettingsFactory::create();
		return view('admin.settings', compact('settings'));
	}

	public function update(Request $request)
    {
		$validator = Validator::make($request->all(), [
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

        $settings = SettingsFactory::create();
		$settings->setAllUsing($request);
		$request->session()->flash('status-success', trans('settings.update_success'));
        return redirect(url('/settings'));
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
}
