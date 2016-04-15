<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Invoice;

class CompanyBoundaryTest extends TestCase
{
    use DatabaseTransactions;

    private $company1;
    private $company2;
    private $user1;
    private $user2;
    private $invoice1;
    private $invoice2;

    public function setUp()
    {
        // This method will automatically be called prior to any of your test cases
        parent::setUp();

        $this->company1 = factory(App\Company::class)->create();
        $this->company2 = factory(App\Company::class)->create();

        $this->user1 = factory(App\User::class)->create();
        $this->user1->roles()->attach(2);
        $this->user1->company_id = $this->company1->id;

        $this->user2 = factory(App\User::class)->create();
        $this->user2->roles()->attach(2);
        $this->user2->company_id = $this->company2->id;

        $this->actingAs($this->user1);
        $this->invoice1 = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class)->create(['invoice_id' => $this->invoice1->id]);
        $this->invoice1->customer_id = $this->user1->id;
        $this->invoice1->save();

        $this->actingAs($this->user2);
        $this->invoice2 = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class)->create(['invoice_id' => $this->invoice2->id]);
        $this->invoice2->customer_id = $this->user2->id;
        $this->invoice2->save();
    }

    public function testOnlySeeUsersInvoices()
    {
        $this->actingAs($this->user1)
             ->visit('invoice')
             ->see($this->invoice1->description)
             ->dontSee($this->invoice2->description);

         $this->actingAs($this->user2)
              ->visit('invoice')
              ->see($this->invoice2->description)
              ->dontSee($this->invoice1->description);
    }

    public function testOnlySeeCompanyUsers()
    {
        $this->actingAs($this->user1)
             ->visit('user')
             ->see($this->user1->full_name)
             ->dontSee($this->user2->full_name);

         $this->actingAs($this->user2)
              ->visit('user')
              ->see($this->user2->full_name)
              ->dontSee($this->user1->full_name);
    }
}
