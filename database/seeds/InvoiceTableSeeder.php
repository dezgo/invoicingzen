<?php

use Illuminate\Database\Seeder;
use App\User;

class InvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            for ($i = 0; $i <= 1; $i++) {
                $invoice[$i] = factory(App\Invoice::class)->create();
                factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $invoice[$i]->id]);
                $invoice[$i]->customer_id = $user->id;
                $invoice[$i]->save();
            }
        }
    }
}
