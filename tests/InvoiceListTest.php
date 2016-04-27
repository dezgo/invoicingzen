<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceListTest extends TestCase
{
    use DatabaseTransactions;

    private $invoice;
    private $quote;
    private $userAdmin;
    private $user;

    public function setUp()
    {
        // This method will automatically be called prior to any of your test cases
        parent::setUp();

        // create an admin user
        $this->user = factory(App\User::class)->create();
        $this->userAdmin = factory(App\User::class)->create();
        $this->userAdmin->roles()->attach(2);

        // create an invoice with 5 items and link to above user
        $this->invoice = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice->id]);
        $this->invoice->customer_id = $this->user->id;
        $this->invoice->save();

        // create a quote with 5 items and link to above user
        $this->quote = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->quote->id]);
        $this->quote->customer_id = $this->user->id;
        $this->quote->is_quote = 'on';
        $this->quote->save();
    }

    public function testSeeQuoteColumn()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice')
            ->see('<td><h4>Type</h4></td>');
    }

    public function testZeroOwingForQuote()
    {
        $this->assertTrue($this->quote->owing == 0);
    }

}
