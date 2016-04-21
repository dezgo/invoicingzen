<?php

namespace App\Factories;

use App\Services\SequentialInvoiceNumbers;

class NextInvoiceNumberFactory
{
    public static function get($company_id)
    {
        return SequentialInvoiceNumbers::getNextNumber($company_id);
    }
}
