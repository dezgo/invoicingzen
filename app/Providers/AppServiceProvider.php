<?php

namespace App\Providers;

use App\Invoice;
use App\InvoiceItem;
use App\InvoiceItemCategory;
use App\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\InvoiceNumberChecker;

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
        Invoice::deleting(function ($invoice) {
            $invoice->invoice_items()->delete();
        });
        Invoice::created(function ($invoice) {
            $invoice->uuid = Invoice::GenerateUUID($invoice->id);
            $invoice->save();
        });
        InvoiceItemCategory::saving(function ($invoice_item_categories) {
            if (Auth::check()) {
                $invoice_item_categories->company_id = Auth::user()->company_id;
            }
        });

        Validator::extend('invoice_number_unique', function($field,$value,$parameters){
            $new_invoice_number = $value;
            $id = $parameters[0];

            if ($id != null and Invoice::find($id)->invoice_number == $new_invoice_number) {
                return true;
            }
            else {
                return InvoiceNumberChecker::number_available($new_invoice_number, Auth::user()->company_id);
            }
        });

        Validator::extend('noscript', function($field,$value,$parameters){
            return strpos($value, '<script') === false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
