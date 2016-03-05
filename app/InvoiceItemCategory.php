<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItemCategory extends Model
{
	use SoftDeletes;

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	/**
	 * Explicitly specify the table name for this model.
	 *
	 * @var string
	 */
	protected $table = 'invoice_item_categories';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['description'];

	/**
	 * Get the invoice items in this category
	 */
	public function category()
	{
		return $this->hasMany('App\InvoiceItem', 'category_id');
	}

	public static function categoryList() {
		return InvoiceItemCategory::all()->lists('description', 'id');
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
}
