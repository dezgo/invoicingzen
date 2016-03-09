<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceItemTickbox extends TestCase
{
    use DatabaseTransactions;

    private $invoice;
    private $user;

    public function setUp()
    {
        // This method will automatically be called prior to any of your test cases
        parent::setUp();

        $this->user = factory(App\User::class)->create();
        $this->user->roles()->attach(2);
        $this->invoice = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice->id]);
        $this->invoice->customer_id = $this->user->id;
        $this->invoice->save();
    }

    public function testSeeTickboxes()
    {
        $this->actingAs($this->user)
            ->visit('/invoice/'.$this->invoice->id)
            ->see('chkReady');
    }

    public function testTickboxSaves()
    {
        $invoice_id = $this->invoice->id;
        $invoice_item_id = $this->invoice->invoice_items->first()->id;
        $this->actingAs($this->user)
             ->post(url('/invoice_item/'.$invoice_item_id.'/ready'))
             ->seeInDatabase('invoice_items', ['id' => $invoice_item_id, 'ready' => true]);
    }
}
