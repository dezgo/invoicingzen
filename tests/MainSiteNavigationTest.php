<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MainSiteNavigationTest extends TestCase
{
	use DatabaseTransactions;

	public function testHome()
	{
		$this->visit('/')
			->see('Invoicing Zen')
			->dontSee('invoicesAnchor');
	}

	public function testHome_Signup()
	{
		$this->visit('https://localhost')
			->type('Joe', 'first_name')
			->type('Bloe', 'last_name')
			->type('Joe Bloe Inc', 'business_name')
			->type('joe@bloe.com', 'email')
			->type('Password01', 'password')
			->type('Password01', 'password_confirmation')
			->press('btnSignUp')
			->seePageIs('https://localhost/invoice');
	}

	public function testHome_ExistingUser()
	{
		$this->visit('https://localhost')
			->click('linkExistingUser')
			->seePageIs('https://localhost/login');
	}

	public function testHome_ReleaseNotes()
	{
		$this->visit('https://localhost')
			->click('linkReleaseNotes')
			->seePageIs('https://localhost/release-notes');
	}

	public function testLogin_Go()
	{
		$user = factory(App\User::class)->create();
		$this->visit('https://localhost/login')
			->type($user->email, 'email')
			->type('password', 'password')
			->press('btnLogin')
			->seePageIs('https://localhost/invoice');
	}

	public function testLogin_NewUser()
	{
		$this->visit('https://localhost/login')
			->click('linkSignUp')
			->seePageIs('https://localhost');
	}

	public function testLogin_ForgotPassword()
	{
		$this->visit('https://localhost/login')
			->click('linkForgotPassword')
			->seePageIs('https://localhost/password/reset');
	}

	public function testForgotPassword_ExistingUser()
	{
		$this->visit('https://localhost/password/reset')
			->click('linkExistingUser')
			->seePageIs('https://localhost/login');
	}

	public function testForgotPassword_SendLink()
	{
		Mail::shouldReceive('send');
		$user = factory(App\User::class)->create();
		$this->visit('https://localhost/password/reset')
			->type($user->email, 'email')
			->press('btnSubmit')
			->seePageIs('https://localhost/password/reset');
	}
}
