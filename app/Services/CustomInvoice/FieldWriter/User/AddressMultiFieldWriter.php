<?php

namespace App\Services\CustomInvoice\FieldWriter\User;

use App\Services\CustomInvoice\TemplateField\TemplateField;
use App\Services\CustomInvoice\FieldWriter\TemplateFieldWriter;

class AddressMultiFieldWriter implements TemplateFieldWriter
{
    public function write(TemplateField $field)
    {
        return $field->get('addressmulti');
    }
}
