<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Email extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cc',
        'bcc',
        'subject',
        'body',
    ];

    public function __construct(array $attributes = array())
    {
        $defaults = [
			'from' => Auth::user()->email,
			];
		$this->setRawAttributes($defaults, true);
		parent::__construct($attributes);
    }

    /**
     * Setup the relationship to users - the sender
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Setup the relationship to users - the recipient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Setup the relationship to invoices
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

    // the default subject for invoice emails
    public function subject($invoice)
    {
        return 'Invoice '.$invoice->invoice_number;
    }

    // the default body text for invoice emails
    public function body($invoice)
    {
        return
			'Hi '.$invoice->user->first_name.',<br />'.
			'<br />'.
			'Click the link below to view invoice '.$invoice->invoice_number.' for $'.
			number_format($invoice->total, 2).'<br />'.
            '<a href=\''.url('/view/'.$invoice->uuid).'\'>View Invoice</a>'.
			'<br />'.
			'Thanks,<br />'.
			Auth::user()->name.'<br />'.
			\Setting::get('email_signature');
    }
}
