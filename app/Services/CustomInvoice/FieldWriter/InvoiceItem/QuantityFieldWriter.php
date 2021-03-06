<?php

namespace App\Services\CustomInvoice\FieldWriter\InvoiceItem;

use App\Services\CustomInvoice\TemplateField\TemplateField;
use App\Services\CustomInvoice\FieldWriter\TemplateFieldWriter;

class QuantityFieldWriter implements TemplateFieldWriter
{
    public function write(TemplateField $field)
    {
        return $field->get('quantity');
    }
}
