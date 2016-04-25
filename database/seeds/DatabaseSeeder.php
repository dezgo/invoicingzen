<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Services\RestoreDefaultTemplates;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(InvoiceItemCategoriesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(InvoiceTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        RestoreDefaultTemplates::restoreDefaults(1);
    }
}
