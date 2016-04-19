<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class Invoice extends Model
{
	use SoftDeletes;

	protected $table = 'invoices';
	protected $dates = ['invoice_date', 'due_date', 'deleted_at'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'invoice_number',
		'customer_id',
		'invoice_date',
		'due_date',
		'paid',
		'is_quote'
	];

	/**
     * Constructor - set default values for new record
     *
     * @return null
     */
	public function __construct(array $attributes = array())
	{
		$defaults = [
		       'invoice_date' => $this->getDefaultInvoiceDate(),
		       'due_date' => $this->getDefaultDueDate(),
		       ];
		$this->setRawAttributes($defaults, true);
		parent::__construct($attributes);
	}

	private function getDefaultInvoiceDate()
	{
		return Carbon::today();
	}

	private function getDefaultDueDate()
	{
		return Carbon::today()->addDays(7);
	}

	public function getDescriptionAttribute()
	{
		return $this->invoice_number.': '.$this->user->full_name;
	}

	/**
	 * Setup the relationship to users
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('App\User', 'customer_id');
	}

	/**
	 * Get the invoice items for this invoice
	 */
	public function invoice_items()
	{
		return $this->hasMany('App\InvoiceItem');
	}

	public function getTotalAttribute()
	{
		return $this->invoice_items->sum('total');
	}

	/*
	 * Get amount owing on invoice (or zero if a quote)
	 */
	public function getOwingAttribute()
	{
		if ($this->is_quote == '') {
			return $this->total - $this->paid;
		}
		else {
			return 0;
		}
	}

	public function getTypeAttribute($value)
	{
		if ($this->is_quote == 'on') {
			return "Quote";
		}
		elseif (round($this->owing,2) > 0.00) {
			$settings = \App::make('App\Contracts\Settings');
			if ($settings->get('gst_registered')) {
				return "Tax Invoice";
			}
			else {
				return "Invoice";
			}
		}
		else {
			return "Receipt";
		}
	}

	/**
	 * Holding is_quote attribute as either 'on' or blank in app, but stored
	 * as boolean in database. This converts it from string to boolean before
	 * storing in db
	 */
	public function setIsQuoteAttribute($value)
	{
		$this->attributes['is_quote'] = ($value == 'on');
	}

	/**
	 * Holding is_quote attribute as either 'on' or blank in app, but stored
	 * as boolean in database. This converts it from boolean to string on
	 * retrieval from db
	 */
	public function getIsQuoteAttribute($value)
	{
		return $value ? 'on' : '';
	}

	/**
	* Convert due date into an instance of Carbon

	* @param $value
	*/
	public function setDueDateAttribute($value)
	{
		if(gettype($value) == 'string') {
			$this->attributes['due_date'] = Carbon::createFromFormat('d-m-Y', $value);
		}
	}

	/**
	* Convert invoice date into an instance of Carbon
	*
	* @param $value
	*/
	public function setInvoiceDateAttribute($value)
	{
		if(gettype($value) == 'string') {
			$this->attributes['invoice_date'] = Carbon::createFromFormat('d-m-Y', $value);
   		}
	}

	public function getCustomerAttribute()
	{
		return $this->user->description;
	}

	public static function allInCompany($company_id)
	{
		return Invoice::where('company_id', '=', $company_id)->get();
	}

	public static function GenerateUUID($id)
	{
		return Uuid::uuid5(Uuid::NAMESPACE_DNS, 'invoicingzen '.$id);
	}
}
