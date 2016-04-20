<?php

namespace App;

use App\Services\SequentialInvoiceNumbers;
use App\Invoice;

class InvoiceMerger
{
    private $invoiceSrc1;
    private $invoiceSrc2;
    private $invoiceDest;

    public function __construct(Invoice $invoiceSrc1, Invoice $invoiceSrc2)
    {
        $this->invoiceSrc1 = $invoiceSrc1;
        $this->invoiceSrc2 = $invoiceSrc2;
    }

    public function merge()
    {
        $this->cloneInvoice();
        $this->mergeInvoiceDetails();
        $this->moveInvoiceItems();
        $this->deleteSourceInvoices();

        return $this->invoiceDest;
    }

    private function cloneInvoice()
    {
        $this->invoiceDest = new Invoice;
        $this->invoiceDest->customer_id = $this->invoiceSrc1->customer_id;
        $this->invoiceDest->invoice_number = SequentialInvoiceNumbers::getNextNumber($this->invoiceSrc1->user->company_id);
        $this->invoiceDest->invoice_date = $this->invoiceSrc1->invoice_date;
        $this->invoiceDest->due_date = $this->invoiceSrc1->due_date;
        if ($this->invoiceSrc1->paid != null) {
            $this->invoiceDest->paid = $this->invoiceSrc1->paid;
        }
        $this->invoiceDest->save();
    }

    private function mergeInvoiceDetails()
    {
        $this->invoiceDest->customer_id = $this->invoiceSrc2->customer_id;
        if ($this->invoiceSrc2->invoice_date->gt($this->invoiceSrc1->invoice_date)) {
            $this->invoiceDest->invoice_date = $this->invoiceSrc2->invoice_date;
            $this->invoiceDest->due_date = $this->invoiceSrc2->due_date;
        }
        else {
            $this->invoiceDest->invoice_date = $this->invoiceSrc1->invoice_date;
            $this->invoiceDest->due_date = $this->invoiceSrc1->due_date;
        }
        if ($this->invoiceSrc1->paid != null or $this->invoiceSrc2->paid != null) {
            $this->invoiceDest->paid = $this->invoiceSrc2->paid + $this->invoiceSrc1->paid;
        }

        $this->invoiceDest->save();
    }

    private function moveInvoiceItems()
    {
        foreach($this->invoiceSrc1->invoice_items as $invoice_item) {
            $invoice_item->invoice_id = $this->invoiceDest->id;
            $invoice_item->save();
        }
        foreach($this->invoiceSrc2->invoice_items as $invoice_item) {
            $invoice_item->invoice_id = $this->invoiceDest->id;
            $invoice_item->save();
        }
    }

    private function deleteSourceInvoices()
    {
        $this->invoiceSrc1->delete();
        $this->invoiceSrc2->delete();
    }
}
