<?php

namespace App\Services\CustomInvoice\TemplateField;

class TemplateFieldFactory
{
    public static function getTemplateField($fieldType, $invoice)
    {
        $class = 'App\\Services\\CustomInvoice\\TemplateField\\'.$fieldType.'TemplateField';
        if (class_exists($class)) {
            return new $class($invoice);
        }
        else {
            throw new \Exception('Unsupported template field format: '.$fieldType);
        }
    }
}
