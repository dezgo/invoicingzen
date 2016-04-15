<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CrossCompanyAccessTest extends TestCase
{
    use DatabaseTransactions;

    private $invoice1;
    private $invoice2;

    public function setUp()
    {
        // This method will automatically be called prior to any of your test cases
        parent::setUp();

        $this->invoice1 = factory(App\Invoice::class)->create();
        $this->invoice2 = factory(App\Invoice::class)->create();

        $this->invoice1->user->roles()->attach(2);
        $company = factory(App\Company::class)->create();
        $this->invoice1->user->company_id = $company->id;
        $this->invoice1->user->save();

        $this->invoice2->user->roles()->attach(2);
        $company = factory(App\Company::class)->create();
        $this->invoice2->user->company_id = $company->id;
        $this->invoice2->user->save();

    }

    public function testInvoiceAccessOK()
    {
        $this->actingAs($this->invoice1->user)
             ->get('/invoice/'.$this->invoice1->id)
             ->assertResponseStatus(200);

        $this->actingAs($this->invoice1->user)
             ->visit('/invoice/'.$this->invoice1->id.'/edit')
             ->assertResponseStatus(200);

        $this->actingAs($this->invoice1->user)
             ->visit('/invoice/'.$this->invoice1->id.'/print')
             ->assertResponseStatus(200);

        $this->actingAs($this->invoice1->user)
             ->visit('/invoice/'.$this->invoice1->id.'/delete')
             ->assertResponseStatus(200);

        $this->actingAs($this->invoice1->user)
             ->visit('/invoice/'.$this->invoice1->id.'/email')
             ->assertResponseStatus(200);

        $this->actingAs($this->invoice1->user)
             ->visit('/invoice/'.$this->invoice1->id.'/merge')
             ->assertResponseStatus(200);
    }

    public function testInvoiceAccessFail()
    {
        $this->actingAs($this->invoice1->user)
             ->get('/invoice/'.$this->invoice2->id)
             ->assertResponseStatus(403);

        $this->actingAs($this->invoice1->user)
             ->get('/invoice/'.$this->invoice2->id.'/edit')
             ->assertResponseStatus(403);

        $this->actingAs($this->invoice1->user)
             ->get('/invoice/'.$this->invoice2->id.'/print')
             ->assertResponseStatus(403);

        $this->actingAs($this->invoice1->user)
             ->get('/invoice/'.$this->invoice2->id.'/delete')
             ->assertResponseStatus(403);

        $this->actingAs($this->invoice1->user)
             ->get('/invoice/'.$this->invoice2->id.'/email')
             ->assertResponseStatus(403);

        $this->actingAs($this->invoice1->user)
             ->get('/invoice/'.$this->invoice2->id.'/merge')
             ->assertResponseStatus(403);
    }
}
