<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;

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
		$settings = [
			'next_invoice_number' 	 	=> Settings::get('next_invoice_number'),
			'markup' 				 	=> Settings::get('markup'),
			'bsb' 					 	=> Settings::get('bsb'),
			'bank_account_number' 	 	=> Settings::get('bank_account_number'),
			'abn' 					 	=> Settings::get('abn'),
			'payment_terms' 		 	=> Settings::get('payment_terms'),
			'mailing_address_line_1' 	=> Settings::get('mailing_address_line_1'),
			'mailing_address_line_2' 	=> Settings::get('mailing_address_line_2'),
			'mailing_address_line_3' 	=> Settings::get('mailing_address_line_3'),
			'enquiries_phone' 			=> Settings::get('enquiries_phone'),
			'enquiries_email' 			=> Settings::get('enquiries_email'),
			'enquiries_web' 			=> Settings::get('enquiries_web'),
			'email_signature' 			=> Settings::get('email_signature'),
			'email_host' 				=> Settings::get('email_host'),
			'email_port' 				=> Settings::get('email_port'),
			'email_username' 			=> Settings::get('email_username'),
			'email_password' 			=> Settings::get('email_password'),
			'email_encryption' 			=> Settings::get('email_encryption'),
		];
		return view('admin.settings', compact('settings'));
	}

	public function update(Request $request)
    {
		Settings::update($request);
        return redirect(url('/settings'));
    }
}
