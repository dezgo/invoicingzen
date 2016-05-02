<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    protected $table = 'subscription_types';

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function getDescriptionWithPriceAttribute()
    {
        return $this->description.' (A$'.number_format($this->price,0).')';
    }
}
