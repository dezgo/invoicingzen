<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

    public function testDelete()
    {
        $this->actingAs($this->user)
            ->visit('/invoice_item/'.$this->invoice->invoice_items->first()->id.'/delete')
            ->press('Delete')
            ->seePageIs('/invoice/'.$this->invoice->id);
    }

    public function testMarkup()
    {
        $invoice_item = $this->invoice->invoice_items->first();
        $this->actingAs($this->user)
            ->visit('/invoice_item/'.$invoice_item->id.'/edit')
            ->click('btnMarkup')
            ->click('btnMarkDown');

// would be nice to test for new value, but not possible as it's in javascript
// and 'see' is looking at HTML sent back by browser only. at least we can
// test clicking hte buttons to ensure nothing bad happens!
            // ->see(round($invoice_item->price * (1+\Setting::get('markup')/100), 2));
    }

    public function testURL()
    {
        $ii = $this->invoice->invoice_items->first();
        $ii->url = 'www.google.com.au';
        $ii->save();
        $this->actingAs($this->user)
            ->visit('/invoice_item/'.$ii->id.'/edit')
            ->see('anchorURL')
            ->click('anchorURL')
            ->seePageIs($ii->url);
    }
}
