<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Derek',
            'last_name' => 'Gillett',
            'email' => 'mail@computerwhiz.com.au',
            'password' => bcrypt('9E%QovBUBJCRSqcRvEZl8&TzjFx5E^'),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Joe',
            'last_name' => 'Admin',
            'email' => 'joeadmin@computerwhiz.com.au',
            'password' => bcrypt('9E%QovBUBJCRSqcRvEZl8&TzjFx5E^'),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Joe',
            'last_name' => 'Customer',
            'email' => 'joecustomer@computerwhiz.com.au',
            'password' => bcrypt('9E%QovBUBJCRSqcRvEZl8&TzjFx5E^'),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Joe',
            'last_name' => 'User',
            'email' => 'joeuser@computerwhiz.com.au',
            'password' => bcrypt('9E%QovBUBJCRSqcRvEZl8&TzjFx5E^'),
        ]);

        $this->user = factory(App\User::class, 20)->create();
    }
}
