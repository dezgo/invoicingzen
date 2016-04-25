<?php

namespace App\Services;

use App\InvoiceTemplate;

class RestoreDefaultTemplates
{
    public static function restoreDefaults()
    {
        $settings = \App\Factories\SettingsFactory::create();
        $taxable = $settings->get('taxable');
        self::createTemplate('receipt', $taxable);
        self::createTemplate('quote', $taxable);
        self::createTemplate('invoice', $taxable);
    }

    public static function checkExists()
    {
        return (
            self::checkExistsSingle('Standard Receipt') !== null or
            self::checkExistsSingle('Standard Invoice') !== null or
            self::checkExistsSingle('Standard Quote') !== null);
    }

    private static function checkExistsSingle($title)
    {
        return InvoiceTemplate::where('title', $title)
                                ->where('company_id', \Auth::user()->company_id)
                                ->first();
    }

    private static function createTemplate($type, $taxable)
    {
        $invoice_template = new InvoiceTemplate();
        $invoice_template->company_id = \Auth::user()->company_id;
        $invoice_template->title = 'Standard '.ucfirst($type);
        $view = view('invoice_template.default', compact('type','taxable'));
        $invoice_template->template = $view->render();
        $invoice_template->type = $type;
        $invoice_template->default = true;

        $old_template = InvoiceTemplate::where('title', $invoice_template->title)->delete();
        $invoice_template->save();
    }
}
