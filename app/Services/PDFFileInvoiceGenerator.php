<?php

namespace App\Services;

use App\Contracts\InvoiceGenerator;
use App\Invoice;

class PDFFileInvoiceGenerator implements InvoiceGenerator
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
		$filename = '/tmp/invoice'.$this->invoice->invoice_number.'.pdf';
		\File::delete($filename);
		$this->pdf->save($filename);
        return $filename;
    }
}
