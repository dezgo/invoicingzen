<?php

namespace App\Services\CustomInvoice\TemplateField;

use App\Factories\SettingsFactory;

class SettingsTemplateField implements TemplateField
{
    public function get($fieldName)
    {
        $settings = SettingsFactory::create();
        return $settings->get($fieldName);
    }
}
