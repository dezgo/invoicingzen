<?php

namespace App\Providers;

use App\Invoice;
use App\InvoiceItem;
use App\InvoiceItemCategory;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('invoiceitem.invoice', function($view)
        {
            $view->with('invoice_list', Invoice::all()->lists('description', 'id'));
        });

        view()->composer('invoiceitem.form', function($view)
        {
            $view->with('invoice_item_categories', InvoiceItemCategory::all()->lists('description', 'id'));
            $view->with('invoice_item_list', InvoiceItem::invoiceItemList());
        });

        view()->composer('invoice.form', function($view)
        {
            $view->with('customer_list', User::userSelectList());
        });

        view()->composer('user.select', function($view)
        {
            $view->with('customer_list', User::userSelectList());
        });

        Invoice::created(function ($invoice) {
            \Setting::set('next_invoice_number',$invoice->invoice_number+1);
            \Setting::save();
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
