<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        if (\Auth::check()) {
            return \Auth::user()->company_id;
        }
        else {
            return 1;
        }
    }
}
