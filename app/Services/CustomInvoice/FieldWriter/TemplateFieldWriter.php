<?php

namespace App\Services\CustomInvoice\FieldWriter;

use App\Services\CustomInvoice\TemplateField\TemplateField;

interface TemplateFieldWriter
{
    public function write(TemplateField $field);
}
