<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\InvoiceNumberChecker;
use App\Invoice;
use App\Services\SequentialInvoiceNumbers;
use App\Company;

class InvoiceNumberCheckerTest extends TestCase
{
    public function testCheckNumberAvailable()
    {
        $company_id = Company::first()->id;
        $next_number = SequentialInvoiceNumbers::getNextNumber($company_id);

        $this->assertTrue(InvoiceNumberChecker::number_available($next_number, $company_id));
    }

    public function testCheckNumberUsed()
    {
        $invoice = Invoice::first();
        $company_id = $invoice->user->company_id;
        $next_number = $invoice->invoice_number;

        $this->assertTrue(InvoiceNumberChecker::number_used($next_number, $company_id));
    }
}
