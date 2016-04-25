<?php

namespace App\Services\CustomInvoice\FieldWriter\InvoiceItem;

use App\Services\CustomInvoice\TemplateField\TemplateField;
use App\Services\CustomInvoice\FieldWriter\TemplateFieldWriter;
use App\Money;

class PriceFieldWriter implements TemplateFieldWriter
{
    public function write(TemplateField $field)
    {
        return Money::getFormatted($field->get('price'));
    }
}
