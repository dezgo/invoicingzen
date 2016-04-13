<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceTest extends TestCase
{
    use DatabaseTransactions;

    private $invoice;
    private $userAdmin;
    private $user;

    public function setUp()
    {
        // This method will automatically be called prior to any of your test cases
        parent::setUp();

        $this->user = factory(App\User::class)->create();
        $this->userAdmin = factory(App\User::class)->create();
        $this->userAdmin->roles()->attach(2);
        $this->invoice = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice->id]);
        $this->invoice->customer_id = $this->user->id;
        $this->invoice->save();
    }

    public function testShowIndexAsAdmin()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice')
            ->see('Show Invoices')
            ->see('class="btn btn-success">Create</a>');
    }

    public function testShowIndexAsUser()
    {
        $this->actingAs($this->user)
            ->visit('/invoice')
            ->see('Show Invoices')
            ->dontSee('class="btn btn-success">Create</a>');
    }

    public function testCreate()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice')
            ->click('Create')
            ->see('Create Invoice');
    }

    public function testCreate_invalid()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/create')
            ->type('','invoice_number')
            ->press('Save')
            ->see('The invoice number field is required');
    }

    public function testCreate_save()
    {
        $customer = factory(App\User::class)->create();
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/create')
            ->select($customer->id, 'customer_id')
            ->press('Save')
            ->see('Show Invoice')
            ->see('Great, you have your invoice')
            ->see('btnAddInvoiceItem');
    }

    public function testEdit()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id.'/edit')
            ->see('Edit Invoice');
    }

    public function testEdit403()
    {
        $this->actingAs($this->user)
            ->get('/invoice/'.$this->invoice->id.'/edit')
            ->seeStatusCode(403);
    }

    public function testEdit_invalid()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id.'/edit')
            ->type('', 'invoice_number')
            ->press('Update')
            ->see('invoice number field is required');
    }

    public function testEdit_save()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id.'/edit')
            ->type('01-02-2015', 'invoice_date')
            ->press('Update')
            ->seePageIs('/invoice/'.$this->invoice->id);
    }

    public function testShowAsAdmin()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id)
            ->see('Show Invoice')
            ->see('disabled')
            ->press('btnSubmit')
            ->see('Edit Invoice');
    }

    public function testShowAsUser()
    {
        $this->actingAs($this->user)
            ->visit('/invoice/'.$this->invoice->id)
            ->see('Show Invoice')
            ->see('disabled')
            ->dontSee('btnSubmit');
    }

    public function testDelete()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id.'/delete')
            ->press('Delete')
            ->seePageIs('/invoice');
    }

    public function testPrintAsUser()
    {
        $this->actingAs($this->user)
            ->visit('/invoice/'.$this->invoice->id)
            ->click('btnViewInvoice')
            ->see('Customer Details')
            ->see('How to Pay')
            ->see('Enquiries')
            ->dontSee('<nav class="navbar navbar-default">');
    }

    public function testPrintAsAdmin()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id)
            ->click('btnViewInvoice')
            ->see('Customer Details')
            ->see('How to Pay')
            ->see('Enquiries');
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

    public function testCreateInvoiceWizardValidation()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/')
            ->click('Create Invoice')
            ->see('Pick a Customer:')
            ->press('Next')
            ->see('The customer field is required');
    }

    public function testCreateInvoiceWizardExistingCustomer()
    {
        $customer = factory(App\User::class)->create();
        $this->actingAs($this->userAdmin)
            ->visit('/')
            ->click('Create Invoice')
            ->select($customer->id, 'customer')
            ->press('Next')
            ->seePageIs('/invoice/'.$customer->id.'/create');
    }

    public function testCreateInvoiceWizardNewCustomer()
    {
        $customer = factory(App\User::class)->create();
        $this->actingAs($this->userAdmin)
            ->visit('/')
            ->click('Create Invoice')
            ->click('Create a new customer')
            ->seePageIs('/user/create?flag=1')
            ->type('Robert', 'first_name')
            ->type('Wagner', 'last_name')
            ->type('rwagner@gmail.com', 'email')
            ->type('1 Waid Road', 'address1')
            ->type('Knoxton', 'suburb')
            ->select('ACT', 'state')
            ->type('2000', 'postcode')
            ->press('Save')
            ->see('Create Invoice')
            ->see('Robert Wagner');
    }

    // check next invoice number logic
    public function testNextInvoiceNumber()
    {
        $settings = \App::make('App\Contracts\Settings');
        $next = $settings->get('next_invoice_number');
        $inv1 = new App\Invoice();
        $inv1->customer_id = $this->user->id;
        $inv1->save();
        $inv2 = new App\Invoice();
        $inv2->customer_id = $this->user->id;
        $inv2->save();
        $inv3 = new App\Invoice();
        $inv3->customer_id = $this->user->id;
        $inv3->save();
        assert($inv1->invoice_number == ($next));
        assert($inv2->invoice_number == ($next+1));
        assert($inv3->invoice_number == ($next+2));
    }

    // ensure there's an option to make a quote
    public function testFindIsQuoteCheckbox()
    {
        $this->actingAs($this->userAdmin)
             ->visit('/invoice/'.$this->invoice->id)
             ->see('is_quote')
             ->visit('/invoice/'.$this->invoice->id.'/edit')
             ->check('is_quote')
             ->press('btnSubmit')
             ->seeInDatabase('invoices', ['id' => $this->invoice->id, 'is_quote' => 1]);
    }

    // is the customer select box a select2 js control?
    public function testCheckSelect2()
    {
        $this->actingAs($this->userAdmin)
             ->visit('/invoice/'.$this->invoice->id)
             ->see("$('#customer_list').select2(");

         $this->actingAs($this->userAdmin)
              ->visit('/user/select')
              ->see("$('#customer_list').select2(");

          $this->actingAs($this->userAdmin)
               ->visit('/invoice/create')
               ->see("$('#customer_list').select2(");
    }

    // ensure user sees correct message on blank invoices page
    public function testBlankInvoicePageUser()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($user)
             ->visit('/invoice')
             ->see(trans('invoice.welcome-user'));
    }

    // ensure user sees correct message on non-blank invoices page
    public function testNonBlankInvoicePageUser()
    {
        $this->actingAs($this->invoice->user)
             ->visit('/invoice')
             ->dontSee(trans('invoice.welcome-user'));
    }

    // ensure admin sees correct message on blank invoices page
    public function testBlankInvoicePageAdmin()
    {
        $this->userAdmin->company_id = 2;
        $this->actingAs($this->userAdmin)
             ->visit('/invoice')
             ->see(trans('invoice.welcome-admin'));
    }

    // ensure admin sees correct message on blank invoices page
    public function testNonBlankInvoicePageAdmin()
    {
        $this->userAdmin->company_id = 1;
        $this->actingAs($this->userAdmin)
             ->visit('/invoice')
             ->dontSee(trans('invoice.welcome-admin'));
    }

    // check that only admins can toggle ready tick on invoice item
    public function testReadyTickAccess()
    {
        $this->actingAs($this->userAdmin)
             ->visit('/invoice/'.$this->invoice->id)
             ->see('<input type="checkbox" name="chkReady" iiid="');

        $this->actingAs($this->user)
             ->visit('/invoice/'.$this->invoice->id)
             ->dontSee('<input type="checkbox" name="chkReady" iiid="');
    }
}
