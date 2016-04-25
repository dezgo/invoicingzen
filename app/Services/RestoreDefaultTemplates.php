<?php

namespace App\Services;

use App\InvoiceTemplate;

class RestoreDefaultTemplates
{
    public static function restoreDefaults($company_id)
    {
        $settings = \App\Factories\SettingsFactory::create($company_id);
        $taxable = $settings->get('taxable');
        self::createTemplate($company_id, 'receipt', $taxable);
        self::createTemplate($company_id, 'quote', $taxable);
        self::createTemplate($company_id, 'invoice', $taxable);
    }

    public static function restoreDefault($company_id, $type)
    {
        $settings = \App\Factories\SettingsFactory::create($company_id);
        $taxable = $settings->get('taxable');
        return self::createTemplate($company_id, $type, $taxable);
    }

    public static function checkExists($company_id)
    {
        return (
            self::checkExistsSingle($company_id, 'Standard Receipt') !== null or
            self::checkExistsSingle($company_id, 'Standard Invoice') !== null or
            self::checkExistsSingle($company_id, 'Standard Quote') !== null);
    }

    private static function checkExistsSingle($company_id, $title)
    {
        return InvoiceTemplate::where('title', $title)
                                ->where('company_id', $company_id)
                                ->first();
    }

    private static function createTemplate($company_id, $type, $taxable)
    {
        $invoice_template = new InvoiceTemplate();
        $invoice_template->company_id = $company_id;
        $invoice_template->title = 'Standard '.ucfirst($type);
        $view = view('invoice_template.default', compact('type','taxable'));
        $invoice_template->template = $view->render();
        $invoice_template->type = $type;
        $invoice_template->default = true;

        $old_template = InvoiceTemplate::where('title', $invoice_template->title)->delete();
        $invoice_template->save();
        return $invoice_template;
    }
}
