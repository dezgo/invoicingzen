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
            $invoice = factory(App\Invoice::class)->create();
            factory(App\InvoiceItem::class, 15)->create(['invoice_id' => $invoice->id]);
            $invoice->customer_id = $user->id;
            $invoice->save();
        }
    }
}
