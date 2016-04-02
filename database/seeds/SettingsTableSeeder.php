<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Setting::set('next_invoice_number', 1);
        \Setting::set('markup', '20');
        \Setting::set('abn', '12 234 234 234');
        \Setting::set('payment_terms', '7 Days');
        \Setting::set('bsb', '123123');
        \Setting::set('bank_account_number', '12121212');
        \Setting::set('mailing_address_line_1', '7 Rose Avenue');
        \Setting::set('mailing_address_line_2', 'Manuka ACT 2603');
        \Setting::set('mailing_address_line_3', '');
        \Setting::set('enquiries_phone', '02 6123 4567');
        \Setting::set('enquiries_email', 'mail@widgetscorp.com');
        \Setting::set('enquiries_web', 'www.widgetscorp.com');
        \Setting::setExtraColumns(['company_id' => 1]);
        \Setting::save();
    }
}
