<?php

namespace App\Services;

use App\Contracts\InvoiceNumberGenerator as Contract;
use App\Invoice;

class SequentialInvoiceNumbers implements Contract
{
    public static function getNextNumber($company_id)
    {
        return \DB::table('invoices')
				  ->join('users', 'users.id', '=', 'invoices.id')
				  ->where('users.company_id', '=', $company_id)
                  ->max('invoices.invoice_number')+1;
    }
}
