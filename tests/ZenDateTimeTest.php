<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\ZenDateTime;

class ZenDateTimeTest extends TestCase
{
    public function testNumericDateTime()
    {
        $time = mktime(1,2,3,12,1,2000);
        $this->assertTrue(ZenDateTime::formatLong($time) == '01-12-2000 01:02:03');
    }
}
