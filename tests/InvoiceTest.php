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
        parent::setUp();

        $this->user = factory(App\User::class)->create();
        $this->userAdmin = factory(App\User::class)->create();
        $this->userAdmin->roles()->attach(2);
        $this->invoice = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice->id]);
        $this->invoice->user()->associate($this->user);
        $this->invoice->save();

        $this->be($this->userAdmin);
        $settings = \App\Factories\SettingsFactory::create();
        $settings->set('taxable', false);
        App\Services\RestoreDefaultTemplates::restoreDefaults($this->userAdmin->company_id);
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
            ->see(trans('validation.required', ['attribute' => 'invoice number']))
            ->see(trans('validation.required', ['attribute' => 'customer id']));
    }

    public function testCreate_save()
    {
        $this->be($this->userAdmin);
        $invoice_number = \App\Services\SequentialInvoiceNumbers::getNextNumber($this->userAdmin->company_id);

        $customer = factory(App\User::class)->create();
        $this->actingAs($this->userAdmin)
            ->visit('/invoice/create')
            ->select($customer->id, 'customer_id')
            ->press('Save')
            ->see('Show Invoice')
            ->see('Great, you have your invoice')
            ->see('btnAddInvoiceItem')
            ->seeInDatabase('invoices', [
                'invoice_number' => $invoice_number,
            ]);
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
            ->press('btnSubmit')
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
            ->press('btnConfirmDelete')
            ->seePageIs('/invoice');

        $invoice = App\Invoice::where('id', '=', $this->invoice->id)->first();
        $this->assertTrue($invoice == null);

        $invoice_item = App\InvoiceItem::where('invoice_id', '=', $this->invoice->id)->first();
        $this->assertTrue($invoice_item == null);
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
        $company = factory(App\Company::class)->create();
        $this->userAdmin->company_id = $company->id;
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
