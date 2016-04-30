<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use App\Http\Requests\UserRequest;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // check if user is allowed to use this controller
    private function checkAccess($user_id)
    {
        return Gate::check('admin') || Auth::user()->id == $user_id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new user. $request->flag not
     * null means the request came from the 'new invoice' wiz
     * so ensure we go back there after customer creation.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request = null)
    {
        if (!Gate::check('admin')) {
            abort('403');
        }

        $active_tab = 'account';
        return view('user.create', compact('active_tab'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if (!Gate::check('admin')) {
            abort('403');
        }

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
        if (Gate::denies('update-user', $user))
        {
            abort(403);
        }

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
        if (Gate::denies('update-user', $user))
        {
            abort(403);
        }

        $user->update($request->all());
        $request->session()->flash('status-success', 'User updated');
		return redirect('/user');
    }

    public function confirm_delete(User $user)
    {
        return view('/user/delete', compact('user'));
    }

    public function destroy(User $user)
    {
        $this->authorize('update-user', $user);
        $user->delete();
        return redirect('/user');
    }

    // this method is meant to allow a read-only view of the User.
    // I don't have one so am just redirecting to the edit view
    public function show(User $user)
    {
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

    public function subscription(User $user)
    {
        $active_tab = 'subscription';
        return view('user.subscription', compact('user', 'active_tab'));
    }

    public function payments(User $user)
    {
        $active_tab = 'payments';
        return view('user.payments', compact('user', 'active_tab'));
    }

    public function card(User $user)
    {
        $active_tab = 'credit_card';
        $start_year = date("Y");
        for ($year=$start_year; $year<$start_year+15; $year++) {
            $expiration_years[] = $year;
        }
        return view('user.card', compact('user', 'active_tab', 'expiration_years'));
    }

    public function updatecc(Request $request)
    {
        $user = Auth::user();
        try {
            $user->newSubscription('standard', 'standard')->create($request->stripeToken);
        }
        catch (Exception $e) {
            \Session()->flash('status-warning', 'There was a problem with your card. '.
                'Please check the details and try again ('.$e->description.')');
            return redirect('/user/'.Auth::user()->id.'/card');
        }

        \Session()->flash('status-success', 'Card details updated');
        return redirect('/user/'.Auth::user()->id.'/card');
    }
}
