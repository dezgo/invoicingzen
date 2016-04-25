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
            return $this->invoice[$fieldName];
        }
        else {
            $reflectionMethod = new \ReflectionMethod('\\App\\Invoice', 'get'.$fieldName.'Attribute');
            return $reflectionMethod->invoke($this->invoice);
        }
    }
}
