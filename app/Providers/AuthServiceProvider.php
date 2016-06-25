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
            if ($invoice->user->company_id == $user->company_id and
                ($user->isAdmin() || $invoice->user->id == $user->id)) {
                return true;
            }
        });

        $gate->define('edit-invoice', function ($user, $invoice) {
            if ($invoice->user->company_id == $user->company_id and
                $user->isAdmin()) {
                return true;
            }
        });

        $gate->define('delete-invoice', function ($user, $invoice) {
            if ($invoice->user->company_id == $user->company_id and
                $user->isAdmin()) {
                return true;
            }
        });

        $gate->define('create-user', function($user) {
            return $user->isAdmin();
        });

        $gate->define('view-user', function($user, $userToView) {
            if ($userToView->company_id == $user->company_id and
                ($user->isAdmin() || $user->id == $userToView->id))
            {
                return true;
            }
        });

        $gate->define('edit-user', function($user, $userToEdit) {
            if ($userToEdit->company_id == $user->company_id and
                ($user->isAdmin() || $user->id == $userToEdit->id))
            {
                return true;
            }
        });

        $gate->define('delete-user', function($user, $userToDelete) {
            if ($userToDelete->company_id == $user->company_id and
                ($user->isAdmin() || $user->id == $userToDelete->id))
            {
                return true;
            }
        });

        $gate->define('premium', function($user) {
            if ($user->isAdmin() and $user->isPremium())
            {
                return true;
            }
        });

        $gate->define('standard', function($user) {
            if ($user->isAdmin() and $user->isStandard())
            {
                return true;
            }
        });
    }
}
