<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\RestoreDefaultTemplates;

class InvoiceTemplate extends Model
{
    public static function allInCompany($company_id)
	{
		return Company::find($company_id)->invoice_templates;
	}

    public static function get($type, Company $company)
    {
        $default = self::where('type', '=', $type)
            ->where('default', '=', true)
            ->where('company_id', '=', $company->id)
            ->first();
        if ($default == null) {
            return RestoreDefaultTemplates::restoreDefault($company->id, $type)->template;
        }
        else {
            return $default->template;
        }
    }
}
