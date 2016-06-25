<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use App\User;
use App\Factories\SettingsFactory;

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
		return $this->invoice_number.': '.$this->user->description;
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
			return "quote";
		}
		elseif (round($this->owing,2) > 0.00) {
			return "invoice";
		}
		else {
			return "receipt";
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

	// return all invoices that the currently logged in user can view
	public static function allInvoices()
	{
		if (Auth::user()->isAdmin()) {
			$company = Auth::user()->company;
			$invoices = Company::find($company->id)->invoices;
		}
		else {
			$invoices = Invoice::where('customer_id', Auth::user()->id)->get();
		}
		return $invoices;
	}

	public static function allReceipts()
	{
		$company = Auth::user()->company;
		$sql =
			'select '.
			'    i.id, '.
			'	 i.paid '.
			'from invoices as i '.
			'    inner join invoice_items as ii on i.id = ii.invoice_id '.
			'    inner join users as u on u.id = i.customer_id '.
			'where '.
			'    u.company_id = ? and '.
			'	 not i.is_quote ';

		if (Auth::user()->isUser()) {
			$sql .= 'and i.customer_id = '.Auth::user()->id.' ';
		}

		$sql .=
			'group by '.
			'    i.id '.
			'having '.
			'    sum(ii.quantity * ii.price) = i.paid';

		$invoice_ids = DB::select($sql, [Auth::user()->company->id]);
		$invoices = [];
		foreach ($invoice_ids as $invoice_id) {
			$invoices[] = Invoice::find($invoice_id->id);
		}
		return $invoices;
	}

	public static function allQuotes()
	{
		$quotes = Invoice::allInvoices()->where('is_quote', 'on')->all();
		return $quotes;
	}

	public static function allUnpaid()
	{
		$company = Auth::user()->company;
		$sql =
			'select '.
			'    i.id, '.
			'	 i.paid '.
			'from invoices as i '.
			'    inner join invoice_items as ii on i.id = ii.invoice_id '.
			'    inner join users as u on u.id = i.customer_id '.
			'where '.
			'    u.company_id = ? and '.
			'	 not i.is_quote ';

		if (Auth::user()->isUser()) {
			$sql .= 'and i.customer_id = '.Auth::user()->id.' ';
		}

		$sql .=
			'group by '.
			'    i.id '.
			'having '.
			'    sum(ii.quantity * ii.price) > i.paid';

		$invoice_ids = DB::select($sql, [Auth::user()->company->id]);
		$invoices = [];
		foreach ($invoice_ids as $invoice_id) {
			$invoices[] = Invoice::find($invoice_id->id);
		}
		return $invoices;
	}

	public static function GenerateUUID($id)
	{
		return Uuid::uuid5(Uuid::NAMESPACE_DNS, 'invoicingzen '.$id);
	}

	public function markPaid()
	{
		$this->paid = $this->total;
		$this->save();
	}

	public function markUnpaid()
	{
		$this->paid = 0;
		$this->save();
	}
}
