<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Invoice Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used when displaying invoices
    |
    */

    'welcome-admin' => 'Welcome to the invoicing area! Click the \'Create\' button below to create your first invoice.',
    'welcome-user' => 'Welcome to the invoicing area! You don\'t currently have any invoices to view.',
    'add-invoice-items' => 'Great, you have your invoice, now let\'s add some items to it. Click the \'Add Invoice Item\' button below to get started.',
    'no-invoices-to-merge' => 'Sorry, but this customer only has the one invoice, so merging it isn\'t an option right now',

    // texts on invoice
    'grand-total' => 'Grand-total',
    'amount-paid' => 'Amount paid',
    'balance-due' => 'Balance owing',
    'no-tax' => 'No '.trans('settings.tax').' has been charged',

    // invoice template generator
    'token-mismatch' => 'Mismatching number of start and end tokens',

    // stripe payments
    'payment-success' => 'Thank you. Your payment of :amount for invoice :invoice_number was succesfully processed.',
];
