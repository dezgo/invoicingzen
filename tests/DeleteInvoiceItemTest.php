<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteInvoiceItemTest extends TestCase
{
    public function testPressDeleteButton()
    {
        $user = App\User::find(2);
        $invoice_item = App\InvoiceItem::first();
        $this->actingAs($user)
            ->visit('/invoice_item/'.$invoice_item->id)
            ->click('btnDelete')
            ->see('btnConfirmDelete');
    }

    public function testDelete()
    {
        $user = App\User::find(2);
        $invoice_item = App\InvoiceItem::first();
        $this->actingAs($user)
            ->visit('/invoice_item/'.$invoice_item->id.'/delete')
            ->press('btnConfirmDelete')
            ->see('Show Invoice');
    }
}
