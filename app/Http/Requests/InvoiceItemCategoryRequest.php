<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceItemCategoryRequest extends Request
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
		$id = $this->request->get('id');
		$company_id = Auth::user()->company_id;
		return [
			'description' => 'required|string|min:2|unique:invoice_item_categories,'.
							 'description,'.$id.',id,company_id,'.$company_id
		];
	}
}
