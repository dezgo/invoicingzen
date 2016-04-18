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
			return "Invoice";
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

	public function merge(Invoice $invoice)
	{
		$new_invoice = new Invoice;
		$new_invoice->company_id = $invoice->user->company_id;
		$new_invoice->customer_id = $this->customer_id;
		if ($this->invoice_date->gt($invoice->invoice_date)) {
			$new_invoice->invoice_date = $this->invoice_date;
			$new_invoice->due_date = $this->due_date;
		}
		else {
			$new_invoice->invoice_date = $invoice->invoice_date;
			$new_invoice->due_date = $invoice->due_date;
		}
		$new_invoice->paid = $this->paid + $invoice->paid;

		$new_invoice->save();

		// move invoice items from previous invoices to new merged invoice
		foreach($this->invoice_items as $invoice_item) {
			$invoice_item->invoice_id = $new_invoice->id;
			$invoice_item->save();
		}
		foreach($invoice->invoice_items as $invoice_item) {
			$invoice_item->invoice_id = $new_invoice->id;
			$invoice_item->save();
		}

		$invoice->delete();
		$this->delete();

		return $new_invoice;
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
