<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // me : super_admin
        DB::table('role_user')->insert([
            'role_id' => 1,
            'user_id' => 1,
        ]);

        // joe admin : admin
        DB::table('role_user')->insert([
            'role_id' => 2,
            'user_id' => 2,
        ]);
    }
}
