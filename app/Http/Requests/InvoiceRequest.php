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
		$invoice_id = $this->request->get('id');
		return [
			'customer_id' => 'required|numeric',
			'invoice_date' => 'required|date',
			'invoice_number' => 'required|numeric|invoice_number_unique:'.$invoice_id,
			'due_date' => 'required|date',
		];
	}
}
