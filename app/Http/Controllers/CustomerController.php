<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();

        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     * $flag not empty means request came from the new invoice wizard
     * so ensure we go back there after creating customer.
     *
     * @param int $flag
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request = null)
    {
        if (is_null($request)) {
            session()->forget('inv_wizard');
        } else {
            session(['inv_wizard' => $request->flag]);
        }

        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param IlluminateHttpRequest $request
     *
     * @return IlluminateHttpResponse
     */
    public function store(CustomerRequest $request)
    {
        $customer = Customer::create($request->all());
        $customer->users()->attach(\Auth::user());
        if (session('inv_wizard') != '') {
            session()->forget('inv_wizard');

            return redirect('/invoice/'.$customer->id.'/create');
        } else {
            return redirect('/customer');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $this->authorize('view-customer-x', $customer);
        return view('customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer [description]
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $this->authorize('view-customer-x', $customer);
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $this->authorize('view-customer-x', $customer);
        $customer->update($request->all());
        return redirect('/customer');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('view-customer-x', $customer);
        $customer->delete();
        return redirect('/customer');
    }

    /**
     * Show the specified resource to be deleted.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Customer $customer)
    {
        return view('customer.delete', compact('customer'));
    }

    /**
     * First step in create invoice wizard, select customer.
     *
     * @param int $id
     *
     * @return IlluminateHttpResponse
     */
    public function select()
    {
        return view('customer.select');
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
}
