<?php

namespace App\Services;

use App\Contracts\InvoiceGenerator;
use App\Invoice;
use App\Factories\SettingsFactory;

class PDFStreamInvoiceGenerator implements InvoiceGenerator
{
    private $pdf;

    public function create(Invoice $invoice)
    {
        $settings = SettingsFactory::create();
		$this->pdf = \PDF::loadView('invoice.print', compact('invoice', 'settings'));
        $this->pdf->setOption('print-media-type', true);
    }

    public function output()
    {
		return $this->pdf->stream();
    }
}
