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
			'invoice_number' => $this->getNextInvoiceNumber(),
			];
		$this->setRawAttributes($defaults, true);
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

	/*
	 * Get type of invoice
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

	public function sendByEmail(Email $email)
	{
		$email->to = $this->user->email;
		$email->receiver_id = $this->user->id;
		$email->invoice_id = $this->id;
		$email->subject = $email->subject($this->invoice_number);
		$email->invoice = $this;
		$email->body = $email->body(
			$this->user->first_name,
			$this->invoice_number,
			$this->total);
		return $email;
	}

	public function merge(Invoice $invoice)
	{
		$new_invoice = new Invoice;
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
}
