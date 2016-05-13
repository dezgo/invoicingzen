<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreditCardTest extends TestCase
{
    private $userAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->userAdmin = factory(App\User::class)->create();
        $this->userAdmin->roles()->attach(2);
    }

    public function testCreditCardTab()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/card')
            ->see('placeholder="**** **** **** ****"');
    }

    public function testAddCreditCard()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/card')
            ->type('4242424242424242', 'card_number')
            ->type('01', 'exp_month')
            ->type('25', 'exp_year')
            ->type('123', 'cvc')
            ->press('btnUpdate')
            ->see('There was a problem with your card. Please check the details and try again (No stripe token found)');
    }
}
