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

    // texts on PDF'd invoice
    'grand-total' => 'Grand-total',
    'amount-paid' => 'Amount paid',
    'balance-due' => 'Balance owing',
    'no-gst' => 'No GST has been included',

    // stripe payments
    'payment-success' => 'Thank you. Your payment of :amount for invoice :invoice_number was succesfully processed.',
];
