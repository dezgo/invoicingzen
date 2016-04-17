<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoicePrintTest extends TestCase
{
    use DatabaseTransactions;

    private $invoice;
    private $userAdmin;
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(App\User::class)->create();

        $this->userAdmin = factory(App\User::class)->create();
        $this->userAdmin->roles()->attach(2);

        $this->invoice = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice->id]);
        $this->invoice->customer_id = $this->user->id;
        $this->invoice->save();
    }

    public function testPrintPartiallyPaid()
    {
        $this->invoice->paid = $this->invoice->owing/2;
        $this->invoice->save();
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id.'/print')
            ->see(number_format($this->invoice->paid,2));
    }

    public function testPrintReceipt()
    {
        $this->invoice->paid = $this->invoice->owing;
        $this->invoice->save();
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id.'/print')
            ->see(number_format($this->invoice->paid,2))
            ->see('RECEIPT');
    }

    public function testPrintQuote()
    {
        $this->invoice->is_quote = 'on';
        $this->invoice->save();
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id.'/print')
            ->see('QUOTE')
            ->dontSee('How to Pay')
            ->dontSee('Amount Paid');
    }

    public function testCorrectCompanyName()
    {
        $this->actingAs($this->user)
            ->visit('/invoice/'.$this->invoice->id.'/print')
            ->see($this->userAdmin->company->company_name);
    }
}
