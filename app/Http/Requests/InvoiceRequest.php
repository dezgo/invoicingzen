<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class InvoiceRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		if (!Auth::check()) {
			throw new \RuntimeException('No logged in user!');
		}
		$invoice_id = $this->request->get('id');
		return [
			'customer_id' => 'required|numeric',
			'invoice_date' => 'required|date',
			'invoice_number' => 'required|numeric|unique:invoices,invoice_number,'.
				$invoice_id.',id,company_id,'.Auth::user()->company_id,
			'due_date' => 'required|date',
		];
	}
}
