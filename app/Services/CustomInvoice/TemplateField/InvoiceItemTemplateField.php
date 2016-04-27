<?php

namespace App\Services\CustomInvoice\TemplateField;

use App\InvoiceItem;

class InvoiceItemTemplateField implements TemplateField
{
    private $invoice_item;

    public function __construct(InvoiceItem $invoice_item)
    {
        $this->invoice_item = $invoice_item;
    }

    public function get($fieldName)
    {
        if (array_key_exists($fieldName, $this->invoice_item->toArray())) {
            return $this->invoice_item->toArray()[$fieldName];
        }
        else {
            $reflectionMethod = new \ReflectionMethod('\\App\\InvoiceItem', 'get'.$fieldName.'Attribute');
            return $reflectionMethod->invoke($this->invoice_item);
        }
    }
}
