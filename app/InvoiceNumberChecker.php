<?php

namespace App;

class InvoiceNumberChecker
{
    public static function number_used($invoice_number, $company_id)
    {
        $company = Company::find($company_id);
        $invoice = $company->invoices->where('invoice_number', $invoice_number)->first();
        return $invoice != null;
    }

    public static function number_available($invoice_number, $company_id)
    {
        return !InvoiceNumberChecker::number_used($invoice_number, $company_id);
    }
}
