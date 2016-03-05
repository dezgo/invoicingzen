<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Customer;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        $gate->define('authenticated', function ($user) {
            return true;
        });
        $gate->define('super-admin', function ($user) {
            return $user->isSuperAdmin();
        });
        $gate->define('admin', function ($user) {
            return $user->isAdmin();
        });
        $gate->define('customer', function ($user) {
            return $user->isCustomer();
        });

        $gate->define('create-invoice', function ($user) {
            return $user->isAdmin();
        });

        $gate->define('view-invoice-x', function ($user, $invoice) {
            if ($user->isAdmin())
            {
                return true;
            }

            if ($user->isCustomer())
            {
                return $user->hasCustomer($invoice->customer);
            }
        });

        $gate->define('view-invoice', function ($user) {
            if ($user->isAdmin() || $user->isCustomer())
            {
                return true;
            }
        });

        $gate->define('view-user', function($user, $userToView) {
            if ($user->isAdmin() || $user->id == $userToView->id)
            {
                return true;
            }
        });

        $gate->define('update-user', function($user, $userToView) {
            if ($user->isAdmin() || $user->id == $userToView->id)
            {
                return true;
            }
        });
    }
}
