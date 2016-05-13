<?php

use Illuminate\Database\Seeder;
use App\SubscriptionType;

class SubscriptionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscription_type = new SubscriptionType;
        $subscription_type->description = 'Standard';
        $subscription_type->price = 9.00;
        $subscription_type->payments_per_year = 12;
		$subscription_type->save();

        $subscription_type = new SubscriptionType;
        $subscription_type->description = 'Premium';
        $subscription_type->price = 19.00;
        $subscription_type->payments_per_year = 12;
		$subscription_type->save();
    }
}
