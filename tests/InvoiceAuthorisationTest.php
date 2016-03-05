<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceAuthorisationTest extends TestCase
{
    use DatabaseTransactions;

    private $userAdmin;
    private $invoice1;
    private $invoice2;

    // create two invoices (which creates two users)
    // ensure each user can only see their invoice (and not the other)
    public function setUp()
    {
        parent::setUp();

        $this->userAdmin = factory(App\User::class)->create();
        $this->userAdmin->roles()->attach(2);

        $this->invoice1 = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice1->id]);

        $this->invoice2 = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice2->id]);
    }

    // index page shows just the invoices for the logged in user
    public function testUser1IndexPage()
    {
        $this->actingAs($this->invoice1->user)
            ->visit('/invoice')
            ->see('/invoice/'.$this->invoice1->id)
            ->dontSee('/invoice/'.$this->invoice2->id);
    }

    public function testUser2IndexPage()
    {
        $this->actingAs($this->invoice2->user)
            ->visit('/invoice')
            ->see('/invoice/'.$this->invoice2->id)
            ->dontSee('/invoice/'.$this->invoice1->id);
    }

    public function testUser1SeeInvoice1()
    {
        $this->actingAs($this->invoice1->user)
            ->visit('/invoice/'.$this->invoice1->id)
            ->see($this->invoice1->user->full_name);
    }

    public function testUser2DontSeeInvoice1()
    {
        $this->actingAs($this->invoice2->user)
            ->get('/invoice/'.$this->invoice1->id)
            ->seeStatusCode(403);
    }

    public function testUser1DontSeeInvoice2()
    {
        $this->actingAs($this->invoice1->user)
            ->get('/invoice/'.$this->invoice2->id)
            ->seeStatusCode(403);
    }

    public function testUser2SeeInvoice2()
    {
        $this->actingAs($this->invoice2->user)
            ->visit('/invoice/'.$this->invoice2->id)
            ->see($this->invoice2->user->full_name);
    }

    public function testUserAdminSeesAll()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice1->id)
            ->see($this->invoice1->user->full_name)
            ->visit('/invoice/'.$this->invoice2->id)
            ->see($this->invoice2->user->full_name);
    }
}
