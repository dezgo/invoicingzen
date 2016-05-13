<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Stripe\Token;

class SubscriptionTest extends TestCase
{
    private $userAdmin;

    private function createToken($cardNum)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $data = \Stripe\Token::create([
            'card' => [
                "number" => $cardNum,
                "exp_month" => 11,
                "exp_year" => 2036,
                "cvc" => "314"
            ]
        ]);

        return $data['id'];
    }

    public function setUp()
    {
        parent::setUp();

        $this->userAdmin = factory(App\User::class)->create();
        $this->userAdmin->roles()->attach(2);
        $this->userAdmin->save();


    }

    /**
     * @group stripe
     */
    public function testSubscriptionTabFree()
    {
        $this->assertTrue($this->userAdmin->isFree());

        $this->actingAs($this->userAdmin)
            ->visit('/subscription')
            ->see(trans('subscription.act_sub_std'))
            ->see(trans('subscription.act_sub_prem'))
            ->see(trans('subscription.sub_nothing'));
    }

    /**
     * @group stripe
     */
    public function testSubscriptionTabStandard()
    {
        $token = $this->createToken('4242424242424242');
        $this->userAdmin->createAsStripeCustomer($token);
        $this->userAdmin->newSubscription('default', 'standard')->create();

        $this->be($this->userAdmin);
        $this->visit('/subscription')
            ->see($this->userAdmin->plan_name)
            ->see(trans('subscription.act_swap'))
            ->see(trans('subscription.act_cancel'))
            ->see(trans('subscription.sub_subscribed'));

        $this->assertTrue($this->userAdmin->isStandard());
    }

    /**
     * @group stripe
     */
    public function testSubscriptionTabPremium()
    {
        $token = $this->createToken('4242424242424242');
        $this->userAdmin->createAsStripeCustomer($token);
        $this->userAdmin->newSubscription('default', 'premium')->create();

        $this->assertTrue($this->userAdmin->isPremium());

        $this->actingAs($this->userAdmin)
            ->visit('/subscription')
            ->see($this->userAdmin->plan_name)
            ->see(trans('subscription.act_swap'))
            ->see(trans('subscription.act_cancel'))
            ->see(trans('subscription.sub_subscribed'));
    }

    public function testPaymentsTabNoCustomer()
    {
        $this->actingAs($this->userAdmin)
            ->visit('/payments')
            ->see(trans('subscription.no_payments'));
    }
}
