<?php

namespace App\Contracts;

use App\Invoice;

interface InvoiceGenerator
{
    public function create(Invoice $invoice);

    public function output();
}
