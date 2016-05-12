<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Setting Language Lines
    |--------------------------------------------------------------------------
    |
    | text for each setting
    |
    */

    'title'                  => 'Settings',
    'markup'                 => 'Default Item Markup (%)',
    'tax'                    => 'GST',
    'taxable'                => 'GST Registered',
    'bsb'                    => 'BSB',
    'bank_account_number'    => 'Bank Account number',
    'update_success'         => 'Settings updated',
    'abn'                    => 'ABN',
    'payment_terms'          => 'Payment Terms',
    'mailing_address_line_1' => 'Mailing Address Line 1',
    'mailing_address_line_2' => 'Mailing Address Line 2',
    'mailing_address_line_3' => 'Mailing Address Line 3',
    'enquiries_phone'        => 'Phone',
    'enquiries_email'        => 'Email',
    'enquiries_web'          => 'Web',
    'logo'                   => 'Company Logo',
    'logo_format_validation' => 'Expecting logo to be in jpg, png, or gif format.',
    'email_signature'        => 'Email Signature',
    'email_host'             => 'Email Host',
    'email_port'             => 'Email Port',
    'email_username'         => 'Email Username',
    'email_password'         => 'Email Password',
    'email_encryption'       => 'Email Encryption',
    'email_prepopulate'      => 'Pre-populate with common email settings',
    'update_button'          => 'Update',
    'close_button'           => 'Close',

    'help_email_prepopulate_title'  => 'Help: Email Pre-populate',
    'help_email_prepopulate_body'   => 'The system already knows the settings for a few
        common email providers. If you have one of these providers, select the
        appropriate item, and the email fields will be automatically populated
        with the correct data.',

    'help_email_host_title'  => 'Help: Email Host',
    'help_email_host_body'   => 'The email host refers to the \'outgoing\' or
        \'SMTP\' server used by your email provider. It often starts with \'smtp.\'
        or \'mail.\'.<br /><Br />
        Note: this is different to the \'incoming\'
        mail server which is not required here. The email host is only used to
        send emails, not receive them.',

    'help_email_port_title'  => 'Help: Email Port',
    'help_email_port_body'   => 'The port is generally either 465 or 587.',

    'help_email_username_title'  => 'Help: Email Username',
    'help_email_username_body'   => 'The email username is often your email address.
        Sometimes, it might be the first part of your email address (everything up to
        but not including the @ sign).',

    'help_email_password_title'  => 'Help: Email Password',
    'help_email_password_body'   => 'The email password is the same one you use
        to login to your email, but if you have turned on 2-factor authentication,
        you might need to generate an \'app-specific password\' to use here.',

    'help_email_encryption_title'  => 'Help: Email Encryption',
    'help_email_encryption_body'   => 'The email method is almost always either
        tls or ssl.',
];
