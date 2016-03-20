<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CompanyBoundary;

class Invoice extends Model
{
	use SoftDeletes;
	use CompanyBoundary;

	protected $table = 'invoices';
	protected $dateFormat = 'd-m-Y';
	protected $dates = ['invoice_date', 'due_date', 'created_at', 'updated_at', 'deleted_at'];

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
		$this->setRawAttributes(array(
			'invoice_date' => $this->getDefaultInvoiceDate(),
			'due_date' => $this->getDefaultDueDate(),
			'invoice_number' => $this->getNextInvoiceNumber(),
		), true);
		parent::__construct($attributes);
	}

	/**
	 * Check if the given invoice number has already been used
	 *
	 * @return boolean (true if available)
	 */
	private function checkInvoiceNumber($invoice_number)
	{
		$count = DB::table('invoices')->where('invoice_number', $invoice_number)->count();
		return $count == 0;
	}

	/**
	 * Gets the next invoice number, being the setting, but if that's already
	 * taken, keep incrementing until we find an available one
	 *
	 * @return int
	 */
	private function getNextInvoiceNumber()
	{
		$invoice_number = \Setting::get('next_invoice_number',1);
		while (!$this->checkInvoiceNumber($invoice_number)) {
			$invoice_number = $invoice_number + 1;
		}
		return $invoice_number;
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
		return 'Invoice '.$this->invoice_number.': '.$this->user->full_name.' ('.$this->owing.')';
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

	public function getOwingAttribute()
	{
		return $this->total - $this->paid;
	}

	/*
	 * Always return due date in set format
	 *
	 */
	public function getDueDateAttribute($value)
	{
		return date($this->dateFormat, strtotime($value));
	}

	/*
	 * Always return invoice date in set format
	 *
	 */
	public function getInvoiceDateAttribute($value)
	{
		return date($this->dateFormat, strtotime($value));
	}

	/*
	 * Always return invoice date in set format
	 *
	 */
	public function getTypeAttribute($value)
	{
		if ($this->is_quote == 'on') {
			return "Quote";
		}
		elseif (round($this->owing,2) > 0.00) {
			return "Invoice";
		}
		else {
			return "Receipt";
		}
	}

	/**
	 * Convert value returned by checkbox html control to boolean
	 * suitable for storing in db
	 */
	/**
	 * Convert due date into an instance of Carbon

	 * @param $value
	 */
	public function setIsQuoteAttribute($value)
	{
		$this->attributes['is_quote'] = ($value == 'on');
	}

	/**
	 * Convert value returned by checkbox html control to boolean
	 * suitable for storing in db
	 */
	/**
	 * Convert due date into an instance of Carbon

	 * @param $value
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
		$this->attributes['due_date'] = Carbon::createFromFormat($this->dateFormat, $value);
	}

	/**
	 * Convert invoice date into an instance of Carbon
	 *
	 * @param $value
	 */
	public function setInvoiceDateAttribute($value)
	{
		$this->attributes['invoice_date'] = Carbon::createFromFormat($this->dateFormat, $value);
	}
}
