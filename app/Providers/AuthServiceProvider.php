<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

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

        $gate->define('create-invoice', function ($user) {
            return $user->isAdmin();
        });

        $gate->define('view-invoice', function ($user, $invoice) {
            if (Auth::check()) {
                if ($invoice->user->company_id == Auth::user()->company_id and
                    ($user->isAdmin() || $invoice->user->id == $user->id)) {
                    return true;
                }
            }
        });

        $gate->define('edit-invoice', function ($user, $invoice) {
            if (Auth::check()) {
                if ($invoice->user->company_id == Auth::user()->company_id and $user->isAdmin()) {
                    return true;
                }
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
