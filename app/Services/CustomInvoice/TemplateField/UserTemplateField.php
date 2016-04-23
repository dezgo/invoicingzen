<?php

namespace App\Services\CustomInvoice\TemplateField;

use App\Invoice;

class UserTemplateField implements TemplateField
{
    private $user;

    public function __construct(Invoice $invoice)
    {
        $this->user = \Auth::user();
    }

    public function get($fieldName)
    {
        if (array_key_exists($fieldName, $this->user->toArray())) {
            return $this->user->toArray()[$fieldName];
        }
        else {
            throw new \RuntimeException('User field '.$fieldName.' doesn\'t exist.');
        }
    }
}
