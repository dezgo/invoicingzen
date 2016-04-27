<?php

namespace App\Services\CustomInvoice\FieldWriter\Company;

use App\Services\CustomInvoice\TemplateField\TemplateField;
use App\Services\CustomInvoice\FieldWriter\TemplateFieldWriter;

class LogoFieldWriter implements TemplateFieldWriter
{
    public function write(TemplateField $field)
    {
        if ($field->get('logofilename') != '') {
            return "<img class='left-block' src='".secure_url('/images/'.$field->get('logofilename'))."' />";
        }
        else {
            return "";
        }
    }
}
