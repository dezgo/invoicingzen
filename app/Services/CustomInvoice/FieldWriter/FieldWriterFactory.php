<?php

namespace App\Services\CustomInvoice\FieldWriter;

class FieldWriterFactory
{
    public static function getFieldWriter($fieldType, $field)
    {
        $class = 'App\\Services\\CustomInvoice\\FieldWriter\\'.$fieldType.'\\'.$field.'FieldWriter';
        if (class_exists($class)) {
            return new $class();
        }
        else {
            throw new \Exception('Unsupported field writer: '.$fieldType.'\\'.$field);
        }
    }
}
