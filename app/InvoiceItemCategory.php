<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItemCategory extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $table = 'invoice_item_categories';

	protected $fillable = ['description'];

	public function category()
	{
		return $this->hasMany('App\InvoiceItem', 'category_id');
	}

	/**
	 * Return a unique list of descriptions for the current category
	 *
	 */
	public function description_list()
	{
		return \App\InvoiceItem::distinct()
			->select('description')
			->where('category_id', '=', $this->id)
			->groupBy('description')
			->get()
			->lists('description');
	}

	public static function allInCompany($company_id)
	{
		return InvoiceItemCategory::where('company_id', '=', $company_id)->get();
	}
}
