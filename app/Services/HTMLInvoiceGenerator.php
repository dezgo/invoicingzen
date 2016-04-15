<?php

namespace App\Services;

use App\Contracts\InvoiceGenerator;

class HTMLInvoiceGenerator implements InvoiceGenerator
{
    private $invoice;

    public function create(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function output()
    {
        $settings = \App::make('App\Contracts\Settings');
        $invoice = $this->invoice;
		return view('invoice.print', compact('invoice', 'settings'));
    }
}
