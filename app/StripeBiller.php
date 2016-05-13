<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class StripeBiller
{
    public static function card_update($token)
    {
        if ($token == '') {
            throw new \Exception('No stripe token found');
        }

        $user = Auth::user();
        if ($user->hasStripeId()) {
            $user->updateCard($token);
        }
        else {
            $user->createAsStripeCustomer($token);
        }
    }

    public static function subscription_update($action)
    {
        $user = Auth::user();
        switch ($action) {
            case 'act_sub_std':
                $user->newSubscription('default', 'standard')->create();
                break;
            case 'act_sub_prem':
                $user->newSubscription('default', 'premium')->create();
                break;
            case 'act_resume':
                $user->subscription('default')->resume();
                break;
            case 'act_cancel':
                $user->subscription('default')->cancel();
                break;
            case 'act_swap':
                if ($user->standard) {
                    $user->subscription('default')->swap('premium');
                }
                else {
                    $user->subscription('default')->swap('standard');
                }
                break;
            default:
                throw new \Exception('Invalid action: '.$action);
                break;
        }
    }

    public static function getCurrentStatus()
    {
        $user = Auth::user();
        if ($user->subscribed()) {
            if ($user->subscription()->cancelled()) {
                if ($user->subscription()->onGracePeriod()) {
                    return 'sub_cancel_grace';
                }
                else {
                    return 'sub_cancel';
                }
            }
            else {
                return 'sub_subscribed';
            }
        }

        return 'sub_nothing';
    }

    public static function getNextAction($status)
    {
        switch($status) {
            case 'sub_nothing':
            case 'sub_cancel':
                $next_action[0] = 'act_sub_std';
                $next_action[1] = 'act_sub_prem';
                break;
            case 'sub_subscribed':
                $next_action[0] = 'act_cancel';
                $next_action[1] = 'act_swap';
                break;
            case 'sub_cancel_grace':
                $next_action[0] = 'act_resume';
                $next_action[1] = 'act_swap';
                break;
            default:
                throw new \Exception('Invalid status: '.$status);
        }
        return $next_action;
    }
}
