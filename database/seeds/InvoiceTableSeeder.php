<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Invoice;
use App\InvoiceItem;

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
                $invoice[$i] = factory(Invoice::class)->create([
                    'customer_id' => $user->id,
                ]);
                factory(InvoiceItem::class, 5)->create([
                    'invoice_id' => $invoice[$i]->id,
                ]);
            }
        }
    }
}
