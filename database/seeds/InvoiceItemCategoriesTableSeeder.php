<?php

use Illuminate\Database\Seeder;
use App\InvoiceItemCategory;

class InvoiceItemCategoriesTableSeeder extends Seeder
{
	public function run()
	{
		factory(App\InvoiceItemCategory::class, 15)->create();
	}
}
