<?php namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as BaseController;
use Log;

class WebhookController extends BaseController
{
    /**
     * Handle a Stripe webhook.
     *
     * @param  array  $payload
     * @return Response
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        Log::info('Handled Invoice Payment Succeeded webhook');
    }

    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        Log::info('Handled Customer Subscription Deleted webhook');
        parent::handleCustomerSubscriptionDeleted($payload);
    }
}
