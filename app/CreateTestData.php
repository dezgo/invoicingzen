<?php

namespace App;

class CreateTestData
{
    public static function getCompany()
    {
        return factory(Company::class)->create();
    }

    public static function getUser(Company $company)
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(2);
        $user->company_id = $company->id;
        $user->save();

        return $user;
    }

    public static function getInvoices(User $user, $num_invoices, $num_invoice_items)
    {
        $invoices = array();
        for ($i=0; $i<$num_invoices; $i++) {
            $invoice = factory(Invoice::class)->create(['customer_id' => $user->id]);
            $invoices[] = $invoice;
            factory(InvoiceItem::class, $num_invoice_items)->create(['invoice_id' => $invoices[$i]->id]);
        }
        return $invoices;
    }
}
