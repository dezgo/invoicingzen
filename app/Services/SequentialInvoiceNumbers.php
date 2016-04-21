<?php

namespace App\Services;

use App\Contracts\InvoiceNumberGenerator as Contract;
use App\Invoice;

class SequentialInvoiceNumbers implements Contract
{
    public static function getNextNumber($company_id)
    {
        $max_invoice_number = \DB::table('invoices')
				  ->join('users', 'invoices.customer_id', '=', 'users.id')
				  ->where('users.company_id', '=', $company_id)
                  ->max('invoices.invoice_number');
        return $max_invoice_number+1;
    }
}
