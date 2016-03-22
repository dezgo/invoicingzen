<?php

use Illuminate\Database\Seeder;
use App\InvoiceItemCategory;

class InvoiceItemCategoriesTableSeeder extends Seeder
{
	private function insert_one($description)
	{
		$cat = new InvoiceItemCategory;
		$cat->description = $description;
		$cat->save();
	}

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->insert_one('Cable');
		$this->insert_one('Case');
		$this->insert_one('CPU');
		$this->insert_one('Discount');
		$this->insert_one('Hard Drive');
		$this->insert_one('Input');
		$this->insert_one('Labour');
		$this->insert_one('Laptop');
		$this->insert_one('Motherboard');
		$this->insert_one('Monitor');
		$this->insert_one('Networking');
		$this->insert_one('Optical');
		$this->insert_one('Power Supply');
		$this->insert_one('RAM');
		$this->insert_one('Software');
		$this->insert_one('Solid State Drive');
		$this->insert_one('Graphics Card');
	}
}
