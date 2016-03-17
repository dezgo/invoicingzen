<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ErrorsPageTest extends TestCase
{
    /**
     * Try going to a non-existant page
     *
     */
    public function test404()
    {
        $this->get('/wrongpage')
            ->assertResponseStatus(404);
    }
}
