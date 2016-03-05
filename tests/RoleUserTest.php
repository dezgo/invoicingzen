<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleUserTest extends TestCase
{
    use DatabaseTransactions;

	private $userSuperAdmin;
    private $userAdmin;

    /**
     * Create class variables to be used in all tests
     */
	public function setUp()
	{
		parent::setUp();

		$this->userSuperAdmin = factory(App\User::class)->create();
		$this->userSuperAdmin->roles()->attach(1);
        $this->userAdmin = factory(App\User::class)->create();
		$this->userAdmin->roles()->attach(2);
        $this->user = factory(App\User::class)->create();
	}

    /**
     * Ensure super admin has appropriate access
     *
     * @return void
     */
    public function testSuperAdmin()
    {
        $this->actingAs($this->userSuperAdmin)
             ->visit('/')
             ->see('userAnchor')
             ->see('invoicesAnchor')
             ->see('invoiceItemCategoriesAnchor')
             ->see('settingsAnchor')
             ->see('createInvoiceAnchor');
    }

    /**
     * Ensure admin has appropriate access
     *
     * @return void
     */
    public function testAdmin()
    {
        $this->actingAs($this->userAdmin)
             ->visit('/')
             ->dontSee('userAnchor')
             ->see('invoicesAnchor')
             ->see('invoiceItemCategoriesAnchor')
             ->see('settingsAnchor')
             ->see('createInvoiceAnchor');
    }

    /**
     * Ensure user has appropriate access
     *
     * @return void
     */
    public function testUser()
    {
        $this->actingAs($this->user)
             ->visit('/')
             ->dontSee('userAnchor')
             ->see('invoicesAnchor')
             ->dontSee('invoiceItemCategoriesAnchor')
             ->dontSee('settingsAnchor')
             ->dontSee('createInvoiceAnchor');
    }

    /**
     * Ensure user has appropriate access
     *
     * @return void
     */
    public function testGuest()
    {
        $this->visit('/')
             ->dontSee('userAnchor')
             ->dontSee('invoicesAnchor')
             ->dontSee('invoiceItemCategoriesAnchor')
             ->dontSee('settingsAnchor')
             ->dontSee('createInvoiceAnchor');
    }
}
