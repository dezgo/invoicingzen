<?php

namespace App\Services;

use App\Contracts\InvoiceNumberGenerator as Contract;
use App\Invoice;

class SequentialInvoiceNumbers implements Contract
{
    public static function getNextNumber($company_id)
    {
        return Invoice::withTrashed()->where('company_id', '=', $company_id)->max('invoice_number')+1;
    }
}
