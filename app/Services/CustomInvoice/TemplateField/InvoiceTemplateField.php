<?php

namespace App\Services\CustomInvoice\TemplateField;

use App\Invoice;

class InvoiceTemplateField implements TemplateField
{
    private $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function get($fieldName)
    {
        if (array_key_exists($fieldName, $this->invoice->toArray())) {
            return $this->invoice->toArray()[$fieldName];
        }
        else {
            throw new \RuntimeException('Invoice field '.$fieldName.' doesn\'t exist.');
        }
    }
}
