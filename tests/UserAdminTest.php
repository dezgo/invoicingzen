<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserAdminTest extends TestCase
{
    use DatabaseTransactions;

	private $user;

	public function setUp()
	{
		parent::setUp();

		$this->user = factory(App\User::class)->create();
		$this->user->roles()->attach(1);
	}

	public function testShowIndex()
	{
		$this->actingAs($this->user)
			->visit('/user')
			->see('Show Users');
    }

    public function testShowEditPage()
    {
        $this->actingAs($this->user)
            ->visit('/user')
            ->click('Joe Customer')
            ->see('btnSubmit')
            ->see('joecustomer@computerwhiz.com.au');
    }

    public function testEditBlankFirstName()
    {
        $this->actingAs($this->user)
            ->visit('/user/'.$this->user->id.'/edit')
            ->type('','first_name')
            ->press('Update')
            ->see('The first name field is required');
    }

    public function testEditBlankLastName()
    {
        $this->actingAs($this->user)
            ->visit('/user/'.$this->user->id.'/edit')
            ->type('','last_name')
            ->press('Update')
            ->see('The last name field is required');
    }

    public function testEditBlankEmail()
    {
        $this->actingAs($this->user)
            ->visit('/user/'.$this->user->id.'/edit')
            ->type('','email')
            ->press('Update')
            ->see('The email field is required');
    }

    // public function testDeactivate()
    // {
    //     $this->actingAs($this->user)
    //         ->visit('/user/'.$this->user->id.'/edit')
    //         ->click('btnDeactivate')
    //         ->seePageIs('/user/'.$this->user->id.'/delete');
    // }
    //
    // public function testConfirmDeactivate()
    // {
    //     $this->actingAs($this->user)
    //         ->visit('/user/'.$this->user->id.'/delete')
    //         ->press('btnConfirmDeactivation')
    //         ->seePageIs('/user');
    // }
}
