<?php

namespace App\Services;

use App\Contracts\InvoiceGenerator;

class PDFStreamInvoiceGenerator implements InvoiceGenerator
{
    private $pdf;
    private $invoice;

    public function create(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $settings = \App::make('App\Contracts\Settings');
		$this->pdf = \PDF::loadView('invoice.print', compact('invoice', 'settings'));
    }

    public function output()
    {
		return $this->pdf->stream();
    }
}
