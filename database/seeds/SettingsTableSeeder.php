<?php

use Illuminate\Database\Seeder;
use App\Factories\SettingsFactory;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = SettingsFactory::create(1);
        $setting->set('markup', '20');
        $setting->set('abn', '12 234 234 234');
        $setting->set('payment_terms', '7 Days');
        $setting->set('bsb', '123123');
        $setting->set('bank_account_number', '12121212');
        $setting->set('mailing_address_line_1', '7 Rose Avenue');
        $setting->set('mailing_address_line_2', 'Manuka ACT 2603');
        $setting->set('mailing_address_line_3', '');
        $setting->set('enquiries_phone', '02 6123 4567');
        $setting->set('enquiries_email', 'mail@widgetscorp.com');
        $setting->set('enquiries_web', 'http://www.widgetscorp.com');
    }
}
