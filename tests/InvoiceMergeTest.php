<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\InvoiceMerger;

class InvoiceMergeTest extends TestCase
{
    use DatabaseTransactions;

    public function testMergeInvoices()
    {
        $user = App\User::find(3);
        \Carbon\Carbon::setToStringFormat('d-m-Y');
        for ($i = 0; $i <=1; $i++) {
            $invoice[$i] = factory(App\Invoice::class)->create();
            factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $invoice[$i]->id]);
            $invoice[$i]->customer_id = $user->id;
            $invoice[$i]->save();
        }
        $new_total_count = 10;

        $invoiceMerger = new InvoiceMerger($invoice[0], $invoice[1]);
        $invoice_merged = $invoiceMerger->merge();
        $this->assertTrue($invoice_merged->invoice_items->count() == $new_total_count);
    }

    public function testMergeButtonExists()
    {
        $userAdmin = App\User::find(2);
        $invoice = $userAdmin->invoices->first();
        $this->actingAs($userAdmin)
            ->visit('/invoice/'.$invoice->id.'/print')
            ->see('btnMerge');
    }

    public function testMergeButtonHiddenForNonAdmins()
    {
        $user = App\User::find(3);
        $invoice = $user->invoices->first();
        $this->actingAs($user)
            ->visit('/invoice/'.$invoice->id.'/print')
            ->dontSee('btnMerge');
    }

    public function testMergeButtonGoesToMergePage()
    {
        $userAdmin = App\User::find(2);
        $invoice = $userAdmin->invoices->first();
        $this->actingAs($userAdmin)
            ->visit('/invoice/'.$invoice->id.'/print')
            ->click('btnMerge')
            ->see('Merge Invoice');
    }

    public function testSeeInvoiceList()
    {
        $userAdmin = App\User::find(2);
        for ($i = 0; $i <=1; $i++) {
            $invoice[$i] = factory(App\Invoice::class)->create();
            factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $invoice[$i]->id]);
            $invoice[$i]->customer_id = $userAdmin->id;
            $invoice[$i]->save();
        }

        $this->actingAs($userAdmin)
            ->visit('/invoice/'.$invoice[0]->id.'/merge')
            ->see('name="merge_invoice_1" value="'.$invoice[1]->id.'"')
            ->dontSee('name="merge_invoice_1" value="'.$invoice[0]->id.'"');
    }

    public function testMerge()
    {
        $userAdmin = App\User::find(2);
        for ($i = 0; $i <=1; $i++) {
            $invoice[$i] = factory(App\Invoice::class)->create();
            factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $invoice[$i]->id]);
            $invoice[$i]->customer_id = $userAdmin->id;
            $invoice[$i]->save();
        }

        $this->actingAs($userAdmin)
            ->visit('/invoice/'.$invoice[0]->id.'/merge')
            ->press('btnSubmit')
            ->see('Show Invoices');

    }
}
