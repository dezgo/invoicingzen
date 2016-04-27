<?php

namespace App\Services;

use App\Contracts\InvoiceGenerator;
use App\Factories\SettingsFactory;

class HTMLInvoiceGenerator implements InvoiceGenerator
{
    private $invoice;

    public function create(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function output()
    {
        $settings = SettingsFactory::create();
        $invoice = $this->invoice;
		return view('invoice.print', compact('invoice', 'settings'));
    }
}
