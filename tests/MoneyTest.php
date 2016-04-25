<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MoneyTest extends TestCase
{
    public function testUnformatted()
    {
        $amount = 2345.45765;
        $this->assertTrue(App\Money::get($amount) == "2345.46");
    }

    public function testFormatted()
    {
        $amount = 2345.45765;
        $this->assertTrue(App\Money::getFormatted($amount) == "2,345.46");
    }
}
