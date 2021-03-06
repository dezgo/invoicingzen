<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
	protected $table = 'invoice_items';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'category_id',
		'description',
		'quantity',
		'price',
		'invoice_id',
		'url',
	];

	/**
	* Get the category for this invoice item
	*/
	public function category()
	{
		return $this->belongsTo('App\InvoiceItemCategory');
	}

	public static function invoiceItemList($category_id = 0) {
		if ($category_id == 0) {
			return InvoiceItem::orderBy('description')
				->lists('description', 'description');
		}
		else {
			return InvoiceItem::where('category_id', $category_id)
				->orderBy('description')
				->lists('description', 'description');
		}
	}

	public function getTotalAttribute()
	{
		return $this->quantity * $this->price;
	}

	public function invoice()
	{
		return $this->belongsTo('App\Invoice');
	}

	/**
	* Get the price the was last used for an item with this description
	*/
	public function getPrice()
	{
		$item = InvoiceItem::where('description', $this->description)
			->orderBy('updated_at', 'DESC')
			->first();

		return is_null($item) ? 0 : $item->price;
	}

	/**
	* Ensure all URLs start with http
	*/
	public function setUrlAttribute($value)
	{
		$this->attributes['url'] = strtolower($value);
		if ($value != '' && substr($value, 0, 4) != 'http') {
			$this->attributes['url'] = 'http://'.$value;
		}
	}

	public function getCategoryDescriptionAttribute()
	{
		if ($this->category == null) {
			return $this->category()->withTrashed()->get()->first()->description.
				' (deleted)';
		}
		else {
			return $this->category->description;
		}
	}
}
