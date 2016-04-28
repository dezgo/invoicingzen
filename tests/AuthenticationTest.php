<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    // leave all fields blank and check correct validation messages are shown
    public function testRegisterAllBlank()
    {
        $this->visit('/')
             ->press('btnSignUp')
             ->see(trans('validation.required', ['attribute' => 'first name']))
             ->see(trans('validation.required', ['attribute' => 'last name']))
             ->see(trans('validation.required', ['attribute' => 'password']))
             ->see(trans('validation.required', ['attribute' => 'business name']))
             ->see(trans('validation.required', ['attribute' => 'email']));
    }

    // enter an invalid email address and ensure appropriate
    // message is displayed
    public function testInvalidEmail()
    {
        $this->visit('/')
             ->type('Holly','first_name')
             ->type('Edwards','last_name')
             ->type('holly@edwards','email')
             ->type('password1','password')
             ->press('btnSignUp')
             ->see(trans('validation.email', ['attribute' => 'email']));
    }

    public function testRegisterExistingBusinessName()
    {
        DB::table('users')->where('email', 'holly@edwards.com')->delete();

        $this->visit('/')
             ->type('Holly','first_name')
             ->type('Edwards','last_name')
             ->type('holly@edwards.com','email')
             ->type('password1','password')
             ->type('Willy\'s Widgets','business_name')
             ->press('btnSignUp')
             ->see('Holly Edwards')
             ->visit('/logout');

        $this->visit('/')
             ->type('Holly','first_name')
             ->type('Edwards','last_name')
             ->type('edward@edwards.com','email')
             ->type('password1','password')
             ->type('Willy\'s Widgets','business_name')
             ->press('btnSignUp')
             ->see(trans('validation.custom.business_name.unique'));
    }

    // finally, register correctly
    // try to register again to check we can't register the same email twice
    // then try logging in with wrong password
    public function testRegisterOK()
    {
        DB::table('users')->where('email', 'holly@edwards.com')->delete();

        $this->visit('/')
             ->type('Holly','first_name')
             ->type('Edwards','last_name')
             ->type('holly@edwards.com','email')
             ->type('password1','password')
             ->type('Willy\'s Widgets','business_name')
             ->press('btnSignUp')
             ->see('Holly Edwards')
             ->seeInDatabase('companies', ['company_name' => 'Willy\'s Widgets'])
             ->visit('/logout');

        $user = DB::table('users')->where('email', 'holly@edwards.com')->first();
        $company = DB::table('companies')->where('company_name', 'Willy\'s Widgets')->first();
        $this->seeInDatabase('users', ['email' => 'holly@edwards.com', 'company_id' => $company->id]);

        $this->visit('/')
             ->type('Holly','first_name')
             ->type('Edwards','last_name')
              ->type('holly@edwards.com','email')
              ->type('password1','password')
              ->type('Willy\'s Widgets','business_name')
              ->press('btnSignUp')
              ->see(trans('validation.unique', ['attribute' => 'email']));

        $this->visit('/login')
             ->type('holly@edwards.com','email')
             ->type('wrong password','password')
             ->press('btnLogin')
             ->see(trans('auth.failed'));
    }

    public function testSeeUpdateUserPage()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($user)
             ->visit('/user/'.$user->id.'/edit')
             ->see('btnSubmit');
     }

     public function testValidateBadUserData()
     {
         $user = factory(App\User::class)->create();
         $this->actingAs($user)
              ->visit('/user/'.$user->id.'/edit')
              ->type('', 'first_name')
              ->type('', 'last_name')
              ->press('btnSubmit')
              ->see(trans('validation.required', ['attribute' => 'first name']))
              ->see(trans('validation.required', ['attribute' => 'last name']));
      }
}
