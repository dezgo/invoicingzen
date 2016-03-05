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
             ->press('btnSubmit')
             ->see(trans('validation.numeric', ['attribute' => trans('settings.next_invoice_number')]))
             ->see(trans('validation.custom.markup.numeric'));
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
             ->press('btnSubmit')
             ->see(trans('settings.update_success'));
    }

}
