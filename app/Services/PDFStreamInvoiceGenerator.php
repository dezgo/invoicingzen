<?php

namespace App\Services;

use App\Contracts\InvoiceGenerator as Contract;
use App\Services\CustomInvoice\InvoiceGenerator;
use App\Invoice;
use App\Factories\SettingsFactory;
use App\InvoiceTemplate;

class PDFStreamInvoiceGenerator implements Contract
{
    private $pdf;

    public function create(Invoice $invoice)
    {
        $settings = SettingsFactory::create();
		$invoice_generator = new InvoiceGenerator();
		$template = InvoiceTemplate::get($invoice->type, $invoice->user->company);
		$invoice_content = $invoice_generator->output($template, $invoice);

		$this->pdf = \PDF::loadView('invoice.print', compact('invoice', 'settings', 'invoice_content'));
        $this->pdf->setOption('print-media-type', true);
    }

    public function output()
    {
		return $this->pdf->stream();
    }
}
