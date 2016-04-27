<?php

namespace App\Services\CustomInvoice\FieldWriter\Invoice;

use App\Services\CustomInvoice\TemplateField\TemplateField;
use App\Services\CustomInvoice\FieldWriter\TemplateFieldWriter;
use App\Money;

class PaidFieldWriter implements TemplateFieldWriter
{
    public function write(TemplateField $field)
    {
        return Money::getFormatted($field->get('paid'));
    }
}
