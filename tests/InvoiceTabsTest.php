<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceTabsTest extends TestCase
{
    use DatabaseTransactions;

    private $userAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->userAdmin = factory(App\User::class)->create();
        $this->userAdmin->roles()->attach(2);
    }

    public function testShowIndexAsAdmin()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/invoice')
            ->see('id="tabUnpaid"')
            ->see('id="tabQuotes"')
            ->see('id="tabReceipts"');
    }
}
