<?php

namespace App\Services\CustomInvoice\FieldWriter\Settings;

use App\Services\CustomInvoice\TemplateField\TemplateField;
use App\Services\CustomInvoice\FieldWriter\TemplateFieldWriter;

class MailingAddressLine2FieldWriter implements TemplateFieldWriter
{
    public function write(TemplateField $field)
    {
        return $field->get('mailing_address_line_2');
    }
}
