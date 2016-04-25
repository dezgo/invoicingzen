<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    protected $table = 'companies';

    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'company_name',
        'subdomain',
	];

    public static function my_id()
    {
        if (Auth::check()) {
            return Auth::user()->company_id;
        }
        else {
            throw new \RuntimeException('No logged in user');
        }
    }

    public function invoices()
    {
        return $this->hasManyThrough('App\Invoice', 'App\User', 'company_id', 'customer_id');
    }

    public function invoice_templates()
    {
        return $this->hasMany('App\InvoiceTemplate');
    }

    public function getLogoFilenameAttribute()
    {
        return 'logo'.$this->id.'.img';
    }

}
