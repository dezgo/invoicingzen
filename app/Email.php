<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * Standard footer text in emails
     */
    public function getFooterTextAttribute()
    {
        return
            "<br />".
            "<br />".
            "PS. To view this invoice online, go to <a href='".url('/')."'>".
            url('/')."</a>. For first-time users, go to <a href='".
            url('/password/reset')."'>".url('/password/reset')."</a> to create a password.";
    }
}
