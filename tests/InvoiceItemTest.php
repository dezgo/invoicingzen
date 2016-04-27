<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Factories\SettingsFactory;

class InvoiceItemTest extends TestCase
{
    use DatabaseTransactions;

    private $invoice;
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->invoice = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice->id]);
        $this->user = factory(App\User::class)->create();
        $this->user->roles()->attach(1);
    }

    public function testCreate_noCategory()
    {
        $this->actingAs($this->user)
            ->visit('/invoice/'.$this->invoice->id)
            ->see('Show Invoice')
            ->click('btnCreateItem')
            ->see('Step 1 - Select category')
            ->press('Next')
            ->see(trans('validation.required', ['attribute' => 'category']));
    }

    public function testCreate_noDescr()
    {
        $category = App\InvoiceItemCategory::orderBy(DB::raw('RAND()'))->take(1)->first();
        $this->actingAs($this->user)
            ->visit('/invoice/'.$this->invoice->id)
            ->click('btnCreateItem')
            ->select($category->id,'category_id')
            ->press('Next')
            ->press('Next')
            ->see(trans('validation.required', ['attribute' => 'description']));
    }

    // go through the entire process of creating a new invoice item and saving it
    public function testCreate_save()
    {
        $this->actingAs($this->user)
            ->visit('/invoice/'.$this->invoice->id)
            ->click('btnCreateItem')
            ->select($this->invoice->invoice_items->first()->category_id,'category_id')
            ->press('Next')
            ->type($this->invoice->invoice_items->first()->description, 'description')
            ->press('Next')
            ->type('2', 'quantity')
            ->type('5.2', 'price')
            ->press('Save')
            ->seePageIs('/invoice/'.$this->invoice->id);
    }

    public function testEdit()
    {
        $this->actingAs($this->user)
            ->visit('/invoice_item/'.$this->invoice->invoice_items->first()->id.'/edit')
            ->see('Edit Invoice Item for invoice '.$this->invoice->invoice_number);
    }

    public function testEdit_invalid()
    {
        $this->actingAs($this->user)
            ->visit('/invoice_item/'.$this->invoice->invoice_items->first()->id.'/edit')
            ->type('', 'quantity')
            ->press('Update')
            ->see(trans('validation.required', ['attribute' => 'quantity']));
    }

    public function testEdit_save()
    {
        $description = App\InvoiceItem::orderBy(DB::raw('RAND()'))->take(1)->first();
        $this->actingAs($this->user)
            ->visit('/invoice_item/'.$this->invoice->invoice_items->first()->id.'/edit')
            ->type($description->description, 'description')
            ->press('Update')
            ->seePageIs('/invoice/'.$this->invoice->id);
    }

    public function testDetails()
    {
        $this->actingAs($this->user)
            ->visit('/invoice_item/'.$this->invoice->invoice_items->first()->id)
            ->see('Show Invoice Item for invoice '.$this->invoice->invoice_number)
            ->see('disabled')
            ->press('Edit')
            ->see('Edit Invoice Item for invoice '.$this->invoice->invoice_number);
    }

    public function testMarkupNotSet()
    {
        $settings = SettingsFactory::create($this->user->company_id);
        $settings->set('markup', '');
        $invoice_item = $this->invoice->invoice_items->first();
        $this->actingAs($this->user)
            ->visit('/invoice_item/'.$invoice_item->id.'/edit')
            ->see(trans('invoice_item.warning-markup-blank'));
    }

    public function testMarkup()
    {
        $settings = SettingsFactory::create($this->user->company_id);
        $settings->set('markup', '15');
        $invoice_item = $this->invoice->invoice_items->first();
        $this->actingAs($this->user)
            ->visit('/invoice_item/'.$invoice_item->id.'/edit')
            ->click('btnMarkup')
            ->click('btnMarkDown');
    }

    public function testURL()
    {
        $ii = $this->invoice->invoice_items->first();
        $ii->url = 'https://www.google.com.au';
        $ii->save();
        $this->actingAs($this->user)
            ->visit('/invoice_item/'.$ii->id.'/edit')
            ->see('anchorURL')
            ->click('anchorURL');
    }

    // ensure URL is only accessible to admins, not to users
    public function testURLAccess()
    {
        $ii = $this->invoice->invoice_items->first();
        $ii->url = 'www.google.com.au';
        $ii->save();

        $admin = factory(App\User::class)->create();
        $admin->roles()->attach(2);

        $this->actingAs($this->invoice->user)
            ->visit('/invoice/'.$this->invoice->id)
            ->dontSee('anchorURL');

        $this->actingAs($admin)
            ->visit('/invoice/'.$this->invoice->id)
            ->see('anchorURL');
    }
}
