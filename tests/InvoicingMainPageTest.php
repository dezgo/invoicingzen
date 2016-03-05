<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoicingMainPageTest extends TestCase
{
	use DatabaseTransactions;

	private $user;

	public function setUp()
	{
		// This method will automatically be called prior to any of your test cases
		parent::setUp();

		$this->user = factory(App\User::class)->create();
		$this->user->roles()->attach(1);
	}

	public function testMain()
	{
		$this->actingAs($this->user)
			 ->visit('/')
			 ->see('invoicesAnchor')
			 ->see('invoiceItemCategoriesAnchor')
			 ->see('createInvoiceAnchor')
			 ->see('settingsAnchor')
			 ->see('userAnchor');
	}

  public function testUsersLink()
  {
    $this->actingAs($this->user)
		->visit('/')
		->click('userAnchor')
		->seePageIs('/user');
  }

  public function testInvoicesLink()
  {
    $this->actingAs($this->user)
		->visit('/')
		->click('invoicesAnchor')
		->seePageIs('/invoice');
  }

  public function testInvoiceItemCategoriesLink()
  {
    $this->actingAs($this->user)
		->visit('/')
		->click('invoiceItemCategoriesAnchor')
		->seePageIs('/invoice_item_category');
  }
}
