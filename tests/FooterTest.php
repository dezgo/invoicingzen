<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Footer extends TestCase
{
    public function testFooterContainersDebugInfo()
    {
        $this->visit('/')
             ->see('a name="environment"')
             ->see('a name="debug_mode"');
    }
}
