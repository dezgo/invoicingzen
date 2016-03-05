<?php

use Illuminate\Database\Seeder;

class InvoiceItemCategoriesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('invoice_item_categories')->insert(['description' => 'Cable']);
		DB::table('invoice_item_categories')->insert(['description' => 'Case']);
		DB::table('invoice_item_categories')->insert(['description' => 'CPU']);
		DB::table('invoice_item_categories')->insert(['description' => 'Discount']);
		DB::table('invoice_item_categories')->insert(['description' => 'Hard Drive']);
		DB::table('invoice_item_categories')->insert(['description' => 'Input']);
		DB::table('invoice_item_categories')->insert(['description' => 'Labour']);
		DB::table('invoice_item_categories')->insert(['description' => 'Laptop']);
		DB::table('invoice_item_categories')->insert(['description' => 'Motherboard']);
		DB::table('invoice_item_categories')->insert(['description' => 'Monitor']);
		DB::table('invoice_item_categories')->insert(['description' => 'Networking']);
		DB::table('invoice_item_categories')->insert(['description' => 'Optical']);
		DB::table('invoice_item_categories')->insert(['description' => 'Power Supply']);
		DB::table('invoice_item_categories')->insert(['description' => 'RAM']);
		DB::table('invoice_item_categories')->insert(['description' => 'Software']);
		DB::table('invoice_item_categories')->insert(['description' => 'Solid State Drive']);
		DB::table('invoice_item_categories')->insert(['description' => 'Graphics Card']);
	}
}
