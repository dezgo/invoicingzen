<?php

namespace App\Providers;

use App\Invoice;
use App\InvoiceItem;
use App\InvoiceItemCategory;
use App\User;
use App\Company;
use App\Contracts\Settings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
            $view->with('customer_list', Auth::User()->userSelectList());
        });

        view()->composer('user.select', function($view)
        {
            if (Auth::check()) {
                $view->with('customer_list', Auth::User()->userSelectList());
            }
        });

        User::saving(function ($user) {
            if (Auth::check()) {
                $user->company_id = Auth::user()->company_id;
            }
        });
        Invoice::saving(function ($invoice) {
            if (Auth::check()) {
                $invoice->company_id = Auth::user()->company_id;
            }
        });
        Invoice::created(function ($invoice) {
            $invoice->uuid = Invoice::GenerateUUID($invoice->id);
            $invoice->save();
        });
        InvoiceItem::saving(function ($invoice_item) {
            if (Auth::check()) {
                $invoice_item->company_id = Auth::user()->company_id;
            }
        });
        InvoiceItemCategory::saving(function ($invoice_item_categories) {
            if (Auth::check()) {
                $invoice_item_categories->company_id = Auth::user()->company_id;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Settings', 'App\Services\AnlutroSettings');
        $this->app->bind('App\Contract\InvoiceNumberGenerator', 'App\Services\SequentialInvoiceNumbers');
    }
}
