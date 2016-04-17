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
}
