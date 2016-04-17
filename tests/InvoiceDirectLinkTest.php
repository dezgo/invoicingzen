<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Invoice;

class InvoiceDirectLinkTest extends TestCase
{
    public function testLinkWorks()
    {
        $invoice = Invoice::all()->first();
        $this->visit('/view/'.$invoice->uuid)
            ->see('Customer Details:');
    }

    public function testBadLinkFails()
    {
        $this->visit('/view/abc')
            ->see(trans('exception_messages.invalid-uuid'));
    }
}
