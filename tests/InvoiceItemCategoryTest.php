<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceItemCategoryTest extends TestCase
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

	public function testShowIndex()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category')
			->see('Show Invoice Item Categories')
			->see('/create\'" class="btn btn-success">');
	}

	public function testCreate()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category/create')
			->see('Create Invoice Item Category');
	}

	public function testCreate_invalid()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category/create')
			->press('Save')
			->see('description field is required');
	}

	public function testCreate_save()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category/create')
			->type('A new one', 'description')
			->press('Save')
			->seePageIs('/invoice_item_category');
	}

	public function testEdit()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category/1/edit')
			->see('Edit Invoice Item Category');
	}

	public function testEdit_invalid()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category/1/edit')
			->type('', 'description')
			->press('Update')
			->see('description field is required');
	}

	public function testEdit_save()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category/1/edit')
			->type('A new one', 'description')
			->press('Update')
			->seePageIs('/invoice_item_category');
	}

	public function testDetails()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category/1')
			->see('Show Invoice Item Category')
			->see('disabled')
			->press('Edit')
			->seePageIs('/invoice_item_category/1/edit');
	}

	public function testDelete()
	{
		$this->actingAs($this->user)
			 ->visit('/invoice_item_category/1/delete')
			 ->press('Delete')
			 ->seePageIs('/invoice_item_category');
	}
}
