<?php

class URLTest extends TestCase
{
    private $user;

    public function setUp()
    {
        // This method will automatically be called prior to any of your test cases
        parent::setUp();

        $this->user = factory(App\User::class)->create();
        $this->user->roles()->attach(1);
    }

    /**
     * Try going to a non-existant page
     *
     */
    public function testNonAuthenticated()
    {
        // $this->get('http://invoicingzen.app/');
        //
        // $host = url()->getRequest()->server('HTTP_HOST');
        // $host_names = explode(".", $host);

        // $this->actingAs($this->user)
        //      ->visit('http://invoicingzen.app/invoice')
        //      ->assertRedirectedTo('http://cw.invoicingzen.app/invoice');
    }
}
