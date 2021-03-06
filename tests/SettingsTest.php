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
        $this->user->roles()->attach(2);

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
             ->see(trans('settings.title'))
             ->see(trans('settings.markup'))
             ->see(trans('settings.taxable'))
             ->see(trans('settings.bsb'))
             ->see(trans('settings.bank_account_number'))
             ->see(trans('settings.abn'))
             ->see(trans('settings.payment_terms'))
             ->see(trans('settings.mailing_address_line_1'))
             ->see(trans('settings.mailing_address_line_2'))
             ->see(trans('settings.mailing_address_line_3'))
             ->see(trans('settings.enquiries_phone'))
             ->see(trans('settings.enquiries_email'))
             ->see(trans('settings.enquiries_web'))
             ->see(trans('settings.logo'))
             ->see(trans('settings.email_signature'))
             ->see(trans('settings.email_host'))
             ->see(trans('settings.email_port'))
             ->see(trans('settings.email_username'))
             ->see(trans('settings.email_password'))
             ->see(trans('settings.email_encryption'));
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
             ->type('a', 'markup')
             ->type('a', 'bsb')
             ->type('a', 'bank_account_number')
             ->type('a', 'abn')
             ->type('a', 'enquiries_phone')
             ->type('a', 'enquiries_web')
             ->type('a', 'enquiries_email')
             ->attach(public_path().'/css/all.css', 'logo')
             ->press('btnSubmit')
             ->see(trans('validation.custom.markup.numeric'))
             ->see(trans('validation.custom.bsb.regex'))
             ->see(trans('validation.custom.bank_account_number.regex'))
             ->see(trans('validation.custom.abn.regex'))
             ->see(trans('validation.custom.enquiries_phone.regex'))
             ->see(trans('validation.custom.enquiries_web.url'))
             ->see(trans('validation.custom.enquiries_email.email'))
             ->see(trans('settings.logo_format_validation'));
    }

    /**
     * See settings and change them - check OK
     * AND, ensure settings do actually show up on invoice
     *
     * @return void
     */
    public function testUpdateSettings()
    {
        $this->be($this->user);
        $settings = \App\Factories\SettingsFactory::create();
        $settings->set('taxable', false);
        App\Services\RestoreDefaultTemplates::restoreDefaults($this->user->company_id);
        $bsb = '123483';
        $bank_account_number = '123456789';
        $abn = '12 321 312 567';
        $payment_terms = '7 Days again';
        $mailing_address_line_1 = 'The Shack from the Outback';
        $mailing_address_line_2 = '12 Stopp Place';
        $mailing_address_line_3 = 'Birmingham NSW 1235';
        $enquiries_phone = '(02) 6123 3443';
        $enquiries_email = 'anewtwo@computerwhiz.com.au';
        $enquiries_web = 'http://testagain.computerwhiz.com.au';

        $this->actingAs($this->user)
             ->visit('/settings')
             ->check('taxable')
             ->type('20', 'markup')
             ->type($bsb, 'bsb')
             ->type($bank_account_number, 'bank_account_number')
             ->type($abn, 'abn')
             ->type($payment_terms, 'payment_terms')
             ->type($mailing_address_line_1, 'mailing_address_line_1')
             ->type($mailing_address_line_2, 'mailing_address_line_2')
             ->type($mailing_address_line_3, 'mailing_address_line_3')
             ->type($enquiries_phone, 'enquiries_phone')
             ->type($enquiries_email, 'enquiries_email')
             ->type($enquiries_web, 'enquiries_web')
             ->press('btnSubmit')
             ->see(trans('settings.update_success'));

         $invoice = factory(App\Invoice::class)->create();
         factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $invoice->id]);

         $this->actingAs($this->user)
              ->visit('/invoice/'.$invoice->id.'/print')
              ->see($bsb)
              ->see(trans('invoice.no-tax'))
              ->see($bank_account_number)
              ->see($abn)
              ->see($payment_terms)
              ->see($mailing_address_line_1)
              ->see($mailing_address_line_2)
              ->see($mailing_address_line_3)
              ->see($enquiries_phone)
              ->see($enquiries_email)
              ->see($enquiries_web);
    }

    public function testNewLogo()
    {
        $path = public_path().'/images/logo.png';
        $this->actingAs($this->user)
             ->visit('/settings')
             ->attach($path, 'logo')
             ->press('btnSubmit')
             ->see(trans('settings.update_success'));
    }

    public function testCompanySeparation()
    {
        $user2 = factory(App\User::class)->create();
        $user2->roles()->attach(2);

        $company = factory(App\Company::class)->create();
        $user2->company_id = $company->id;
        $user2->save();

        $this->actingAs($user2)
             ->visit('/settings')
             ->type('Terms for user2', 'payment_terms')
             ->press('btnSubmit')
             ->seeInDatabase('settings', [
                    'key'           => 'payment_terms',
                    'value'         => 'Terms for user2',
                    'company_id'    => $company->id]);
    }

    public function testTaxCheckbox()
    {
        $this->actingAs($this->user)
             ->visit('/settings')
             ->check('taxable')
             ->press('btnSubmit');

        $settings = \App\Factories\SettingsFactory::create();
        $this->assertTrue($settings->get('taxable') == true);
    }
}
