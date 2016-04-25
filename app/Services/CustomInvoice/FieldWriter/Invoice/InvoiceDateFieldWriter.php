<?php

namespace App\Services\CustomInvoice\FieldWriter\Invoice;

use App\Services\CustomInvoice\TemplateField\TemplateField;
use App\Services\CustomInvoice\FieldWriter\TemplateFieldWriter;

class InvoiceDateFieldWriter implements TemplateFieldWriter
{
    public function write(TemplateField $field)
    {
        return $field->get('invoice_date')->format('d-m-Y');
    }
}
