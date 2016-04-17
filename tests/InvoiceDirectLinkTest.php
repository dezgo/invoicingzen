<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class InvoiceDirectLinkTest extends TestCase
{
    use DatabaseTransactions;

    private $invoice;

    public function setUp()
    {
        parent::setUp();

        $this->be(User::all()->first());
        $this->invoice = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice->id]);
    }

    public function testLinkWorks()
    {
        $this->visit('/view/'.$this->invoice->uuid)
            ->see('Customer Details:');
    }

    public function testBadLinkFails()
    {
        $this->visit('/view/abc')
            ->see(trans('exception_messages.invalid-uuid'));
    }
}
