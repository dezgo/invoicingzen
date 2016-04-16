<?php

namespace App\Contracts;

interface InvoiceNumberGenerator
{
    public static function getNextNumber($company_id);
}
