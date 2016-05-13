<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StripeWebhooksTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $url = 'http://localhost/stripe/webhook';
        $tester = new TeamTNT\Stripe\WebhookTester();
        $tester->setVersion('2014-09-08');
        $tester->setEndpoint($url);

        $response = $tester->triggerEvent('charge.succeeded');
    }
}
