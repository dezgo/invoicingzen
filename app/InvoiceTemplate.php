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

    public static function get($type)
    {
        $default = self::where('type', '=', $type)->where('default', '=', true)->first();
        if ($default == null) {
            throw new \Exception('No default '.$type.' template found');
        }
        else {
            return $default->template;
        }
    }
}
