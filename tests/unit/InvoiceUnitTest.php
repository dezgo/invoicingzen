<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\CreateTestData;
use App\Invoice;

class InvoiceUnitTest extends TestCase
{
    use DatabaseTransactions;

    private $company;
    private $user;
    private $receipts;
    private $quotes;
    private $unpaid;

    public function setUp()
    {
        // This method will automatically be called prior to any of your test cases
        parent::setUp();

        $this->company = CreateTestData::getCompany();
        $this->user = CreateTestData::getUser($this->company);

        $this->receipts = CreateTestData::getInvoices($this->user, 2, 2);
        $this->receipts[0]->markPaid();
        $this->receipts[1]->markPaid();

        $this->quotes = CreateTestData::getInvoices($this->user, 2, 2);
        $this->quotes[0]->is_quote = 'on';
        $this->quotes[0]->save();
        $this->quotes[1]->is_quote = 'on';
        $this->quotes[1]->save();

        $this->unpaid = CreateTestData::getInvoices($this->user, 2, 2);
    }

    public function testAllReceiptsForUserCompany()
    {
        $this->be($this->user);
        $invoices = Invoice::allReceipts();
        $this->assertTrue(reset($invoices)->id == $this->receipts[0]->id);
        $this->assertTrue(end($invoices)->id == $this->receipts[1]->id);
        $this->assertTrue(count($invoices) == 2);
    }

    public function testAllQuotesForUserCompany()
    {
        $this->be($this->user);
        $invoices = Invoice::allQuotes();
        $this->assertTrue(reset($invoices)->id == $this->quotes[0]->id);
        $this->assertTrue(end($invoices)->id == $this->quotes[1]->id);
        $this->assertTrue(count($invoices) == 2);
    }

    public function testAllUnpaidForUserCompany()
    {
        $this->be($this->user);
        $invoices = Invoice::allUnpaid();
        $this->assertTrue(reset($invoices)->id == $this->unpaid[0]->id);
        $this->assertTrue(end($invoices)->id == $this->unpaid[1]->id);
        $this->assertTrue(count($invoices) == 2);
    }

}
