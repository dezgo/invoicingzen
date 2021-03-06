<?php

namespace App\Services\CustomInvoice\FieldWriter\Company;

use App\Services\CustomInvoice\TemplateField\TemplateField;
use App\Services\CustomInvoice\FieldWriter\TemplateFieldWriter;

class NameFieldWriter implements TemplateFieldWriter
{
    public function write(TemplateField $field)
    {
        return $field->get('company_name');
    }
}
