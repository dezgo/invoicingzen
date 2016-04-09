<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceMergeTest extends TestCase
{
    use DatabaseTransactions;

    public function testMergeInvoices()
    {
        $user = factory(App\User::class)->create();

        for ($i = 0; $i <=1; $i++) {
            $invoice[$i] = factory(App\Invoice::class)->create();
            factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $invoice[$i]->id]);
            $invoice[$i]->customer_id = $user->id;
            $invoice[$i]->save();
        }
        $invoice_merged = $invoice[0]->merge($invoice[1]);

        $this->assertTrue($invoice_merged->invoice_items->count() == 10);
    }
}
