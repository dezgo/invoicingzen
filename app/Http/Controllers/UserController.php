<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\StripeBiller;

use App\Http\Requests\UserRequest;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Gate::check('admin')) {
            $users = User::where('company_id','=',Auth::user()->company_id)->get();
            return view('user.index', compact('users'));
        }
        else {
            $user = User::find(Auth::user()->id);
            return view('user.edit', compact('user'));
        }
    }

    public function create(Request $request = null)
    {
        $this->authorize('create-user');

        $active_tab = 'account';
        return view('user.create', compact('active_tab'));
    }

    public function store(UserRequest $request)
    {
        $this->authorize('create-user');

        $user = User::createWithCompany($request->all(), Auth::user()->company_id);
        return redirect('/user');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('edit-user', $user);

        $active_tab = 'account';
        return view('user.edit', compact('user', 'active_tab'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('edit-user', $user);

        $user->update($request->all());
        $request->session()->flash('status-success', 'User updated');
		return redirect('/user');
    }

    public function confirm_delete(User $user)
    {
        $this->authorize('delete-user', $user);
        return view('/user/delete', compact('user'));
    }

    public function destroy(User $user)
    {
        $this->authorize('delete-user', $user);
        $user->delete();
        return redirect('/user');
    }

    // this method is meant to allow a read-only view of the User.
    // I don't have one so am just redirecting to the edit view
    public function show(User $user)
    {
        $this->authorize('view-user', $user);
        return $this->edit($user);
    }

    public function select()
    {
        return view('user.select');
    }

    /**
     * Coming back from selecting a customer, now on to create the invoice
     * - customer pre-filled.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function selected(Request $request)
    {
        $this->validate($request, [
                'customer' => 'required',
            ]);

        return redirect('/invoice/'.$request->customer.'/create');
    }

    public function subscription_show()
    {
        $user = Auth::user();
        $active_tab = 'subscription';

        $current_status = StripeBiller::getCurrentStatus();
        $next_action = StripeBiller::getNextAction($current_status);
        $plan = $user->plan_name;

        return view('user.subscription', compact('user', 'active_tab', 'current_status', 'next_action', 'plan'));
    }

    public function subscription_update($action)
    {
        $user = Auth::user();

        if (!$user->hasStripeId() and $action !== 'act_cancel') {
            \Session()->flash('status-warning', trans('subscription.no_card_on_file'));
            return redirect('/subscription');
        }

        StripeBiller::subscription_update($action);

        \Session()->flash('status-success', 'Subscription Updated');
        return redirect('/subscription');
    }

    public function payments()
    {
        $user = Auth::user();
        $active_tab = 'payments';
        if ($user->hasStripeId()) {
            $invoices = $user->invoicesIncludingPending();
            if ($user->upcomingInvoice() !== null) {
                $invoices[] = $user->upcomingInvoice();
            }
        }
        else {
            $invoices = [];
        }
        return view('user.payments', compact('user', 'active_tab', 'invoices'));
    }

    public function card_show()
    {
        $user = Auth::user();
        $active_tab = 'credit_card';
        return view('user.card', compact('user', 'active_tab'));
    }

    public function card_update(Request $request)
    {
        try {
            StripeBiller::card_update($request->stripeToken);
        }
        catch (\Exception $e) {
            \Session()->flash('status-warning', 'There was a problem with your card. '.
                'Please check the details and try again ('.$e->getMessage().')');
            return redirect('/card');
        }

        \Session()->flash('status-success', 'Card details updated');
        return redirect('/card');
    }
}
