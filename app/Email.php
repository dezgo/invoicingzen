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
    public function subject($invoice_number)
    {
        return 'Invoice '.$this->invoice_number;
    }

    // the default body text for invoice emails
    public function body($first_name, $invoice_number, $total)
    {
        return
			'Hi '.$first_name.',<br />'.
			'<br />'.
			'Please find attached invoice '.$invoice_number.' for $'.
			number_format($total, 2).'<br />'.
			'<br />'.
			'Thanks,<br />'.
			Auth::user()->name.'<br />'.
			Auth::user()->business_name.
			$this->footer_text;
    }

    /**
     * Standard footer text in emails
     */
    protected function getFooterTextAttribute()
    {
        return
            "<br />".
            "<br />".
            "PS. To view this invoice online, go to <a href='".url('/')."'>".
            url('/')."</a>. For first-time users, go to <a href='".
            url('/password/reset')."'>".url('/password/reset')."</a> to create a password.";
    }
}
