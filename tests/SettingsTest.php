<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SettingsTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    public function setUp()
    {
        // This method will automatically be called prior to any of your test cases
        parent::setUp();

        $this->user = factory(App\User::class)->create();
        $this->user->roles()->attach(1);

    }

    /**
     * Basic layout of settings page
     *
     * @return void
     */
    public function testSettings_layout()
    {
        $this->actingAs($this->user)
             ->visit('/settings')
             ->see(trans('settings.next_invoice_number'))
             ->see(trans('settings.markup'));
    }

    /**
     * See settings and change them - check validation
     *
     * @return void
     */
    public function testUpdateSettings_validate()
    {
        $this->actingAs($this->user)
             ->visit('/settings')
             ->type('a', 'next_invoice_number')
             ->type('a', 'markup')
             ->type('a', 'bsb')
             ->type('a', 'bank_account_number')
             ->type('a', 'abn')
             ->type('a', 'enquiries_phone')
             ->type('a', 'enquiries_web')
             ->type('a', 'enquiries_email')
             ->attach(public_path().'/images/ui-bg_diagonals-thick_18_b81900_40x40.png', 'logo')
             ->press('btnSubmit')
             ->see(trans('validation.numeric', ['attribute' => trans('settings.next_invoice_number')]))
             ->see(trans('validation.custom.markup.numeric'))
             ->see(trans('validation.custom.bsb.regex'))
             ->see(trans('validation.custom.bank_account_number.regex'))
             ->see(trans('validation.custom.abn.regex'))
             ->see(trans('validation.custom.enquiries_phone.regex'))
             ->see(trans('validation.custom.enquiries_web.url'))
             ->see(trans('validation.custom.enquiries_email.email'))
             ->see('Expecting logo to be in JPEG format');
    }

    /**
     * See settings and change them - check validation
     *
     * @return void
     */
    public function testUpdateSettings()
    {
        $this->actingAs($this->user)
             ->visit('/settings')
             ->type('34', 'next_invoice_number')
             ->type('20', 'markup')
             ->type('123123', 'bsb')
             ->type('123456789', 'bank_account_number')
             ->type('12 123 123 123', 'abn')
             ->type('7 Days', 'payment_terms')
             ->type('The Shack', 'mailing_address_line_1')
             ->type('12 Stop Place', 'mailing_address_line_2')
             ->type('Birmingham NSW 1234', 'mailing_address_line_3')
             ->type('(02) 6123 3434', 'enquiries_phone')
             ->type('mail@computerwhiz.com.au', 'enquiries_email')
             ->type('http://computerwhiz.com.au', 'enquiries_web')
             ->attach(public_path().'images/logo.jpg', 'logo')
             ->press('btnSubmit')
             ->see(trans('settings.update_success'));
    }

}
