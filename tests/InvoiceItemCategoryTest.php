<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceItemCategoryTest extends TestCase
{
	use DatabaseTransactions;

	private $user;
	private $category;
	private $invoice_item;

	public function setUp()
	{
		// This method will automatically be called prior to any of your test cases
		parent::setUp();

		$this->user = factory(App\User::class)->create();
		$this->user->roles()->attach(1);

		$this->invoice_item = App\InvoiceItem::first();
		$this->category = $this->invoice_item->category;
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
			->visit('/invoice_item_category/'.$this->category->id.'/edit')
			->see('Edit Invoice Item Category');
	}

	public function testEdit_invalid()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category/'.$this->category->id.'/edit')
			->type('', 'description')
			->press('Update')
			->see('description field is required');
	}

	public function testEdit_save()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category/'.$this->category->id.'/edit')
			->type('A new one', 'description')
			->press('Update')
			->seePageIs('/invoice_item_category');
	}

	public function testDetails()
	{
		$this->actingAs($this->user)
			->visit('/invoice_item_category/'.$this->category->id)
			->see('Show Invoice Item Category')
			->see('disabled')
			->press('Edit')
			->seePageIs('/invoice_item_category/'.$this->category->id.'/edit?id='.$this->category->id);
	}

	public function testDelete()
	{
		$this->actingAs($this->user)
			 ->visit('/invoice_item_category/'.$this->category->id.'/delete')
			 ->press('Delete')
			 ->seePageIs('/invoice_item_category');

		$this->actingAs($this->user)
			 ->visit('/invoice_item/'.$this->invoice_item->id.'/delete')
			 ->see($this->category->description.' (deleted)');
	}
}
