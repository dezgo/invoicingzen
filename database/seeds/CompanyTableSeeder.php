<?php

use Illuminate\Database\Seeder;
use App\Company;

class CompanyTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $company = new Company;
        $company->subdomain = 'cw';
        $company->company_name = 'Computer Whiz - Canberra';
		$company->save();
	}
}
