<?php

namespace App\Services\CustomInvoice\FieldWriter\Settings;

use App\Services\CustomInvoice\TemplateField\TemplateField;
use App\Services\CustomInvoice\FieldWriter\TemplateFieldWriter;

class EnquiriesWebFieldWriter implements TemplateFieldWriter
{
    public function write(TemplateField $field)
    {
        return $field->get('enquiries_web');
    }
}
