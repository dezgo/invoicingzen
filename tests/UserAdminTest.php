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
		// This method will automatically be called prior to any of your test cases
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
            ->see('Edit User')
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

    public function testSetRoleSuperAdmin()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($this->user)
             ->visit('/user/'.$user->id.'/edit')
             ->select('super_admin', 'role')
             ->press('Update')
             ->seeInDatabase('role_user', ['role_id' => 1, 'user_id' => $user->id]);
    }

    public function testSetRoleAdmin()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($this->user)
             ->visit('/user/'.$user->id.'/edit')
             ->select('admin', 'role')
             ->press('Update')
             ->seeInDatabase('role_user', ['role_id' => 2, 'user_id' => $user->id]);
    }

    public function testSetRoleUser()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($this->user)
             ->visit('/user/'.$user->id.'/edit')
             ->select('user', 'role')
             ->press('Update')
             ->dontSeeInDatabase('role_user', ['user_id' => $user->id]);
    }
}
