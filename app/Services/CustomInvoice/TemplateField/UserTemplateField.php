<?php

namespace App\Services\CustomInvoice\TemplateField;

use App\Invoice;

class UserTemplateField implements TemplateField
{
    private $user;

    public function __construct(Invoice $invoice)
    {
        $this->user = $invoice->user;
    }

    public function get($fieldName)
    {
        if (array_key_exists($fieldName, $this->user->toArray())) {
            return $this->user->toArray()[$fieldName];
        }
        else {
            $reflectionMethod = new \ReflectionMethod('\\App\\User', 'get'.$fieldName.'Attribute');
            return $reflectionMethod->invoke($this->user);
        }
    }
}
