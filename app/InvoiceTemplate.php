<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceTemplate extends Model
{
    // protected $fillable = [
    //     'title',
    //     'template',
    // ];

    public static function allInCompany($company_id)
	{
		return Company::find($company_id)->invoice_templates;
	}
}
