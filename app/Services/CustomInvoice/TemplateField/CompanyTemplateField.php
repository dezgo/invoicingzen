<?php

namespace App\Services\CustomInvoice\TemplateField;

use App\Invoice;

class CompanyTemplateField implements TemplateField
{
    private $company;

    public function __construct(Invoice $invoice)
    {
        $this->company = \Auth::user()->company;
    }

    public function get($fieldName)
    {
        if (array_key_exists($fieldName, $this->company->toArray())) {
            return $this->company->toArray()[$fieldName];
        }
        else {
            $reflectionMethod = new \ReflectionMethod('\\App\\Company', 'get'.$fieldName.'Attribute');
            return $reflectionMethod->invoke(new \App\Company());
        }
    }
}
