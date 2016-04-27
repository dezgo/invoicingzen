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

        $company = factory(App\Company::class)->create();

        $this->user = factory(App\User::class)->create();
        $this->user->company()->associate($company);
        $this->user->save();

        $this->userAdmin = factory(App\User::class)->create();
        $this->userAdmin->roles()->attach(2);
        $this->userAdmin->company()->associate($company);
        $this->userAdmin->save();

        $this->invoice = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice->id]);
        $this->invoice->user()->associate($this->user->id);
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

    public function testPDFOutput()
    {
        try {
            $this->actingAs($this->user)
                ->visit('/invoice/'.$this->invoice->id.'/print')
                ->click('linkPDF');
        }
        catch (\Exception $e) {
            $this->assertTrue($e instanceof \InvalidArgumentException);
        }
    }

    public function testPay()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id.'/print')
            ->click('btnPay')
            ->see('RECEIPT')
            ->see('Mark Unpaid');
    }

    public function testUnpay()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id.'/print')
            ->click('btnPay')
            ->click('btnUnpay')
            ->see('INVOICE')
            ->see('Mark Paid');
    }
}
