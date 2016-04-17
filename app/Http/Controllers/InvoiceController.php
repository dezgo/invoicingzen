<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceItem;
use App\User;
use App\Http\Requests\InvoiceRequest;
use Illuminate\Support\Facades\Auth;
use Gate;
use App\Services\SequentialInvoiceNumbers;
use App\Exceptions\CustomException;

class InvoiceController extends Controller
{


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		if (Auth::user()->isAdmin()) {
			$invoices = Invoice::allInCompany(Auth::user()->company_id);
		}
		else {
			$invoices = Invoice::where('customer_id', Auth::user()->id)->get();
		}
		return view('invoice.index', compact('invoices'));
	}

	/**
	 * Use this method when coming into the invoice creation screen*
	 * having already selected a customer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function createFromCustomer(User $customer)
	{
		return redirect('/invoice/'.$customer->id.'/create');
	}

	/**
	 * Show the form for creating a new invoice - step2, the actual invoice.
	 * optionally pass in the customer - to cover the wizard where Customer
	 * is selected on previous screen
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(User $customer = null, Invoice $invoice)
	{
		if (Gate::denies('admin')) {
			abort(403);
		}

		if (!is_null($customer)) {
			$invoice->customer_id = $customer->id;
		}

	    $settings = \App::make('App\Contracts\Settings');
        $invoice->invoice_number = SequentialInvoiceNumbers::getNextNumber(Auth::user()->company_id);

		$invoice_items = InvoiceItem::invoiceItemList();
		\Carbon\Carbon::setToStringFormat('d-m-Y');
		return view('invoice.create',compact('invoice','invoice_items'));
	}

	/**
	 * Store newly created invoice
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(InvoiceRequest $request)
	{
		$invoice = Invoice::create($request->all());
		return redirect('/invoice/'.$invoice->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Invoice $invoice)
	{
		if (Gate::denies('view-invoice', $invoice)) {
			abort(403);
		}

		\Carbon\Carbon::setToStringFormat('d-m-Y');
		return view('invoice.show', compact('invoice'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Invoice $invoice)
	{
		if (Gate::denies('edit-invoice', $invoice)) {
			abort(403);
		}

		\Carbon\Carbon::setToStringFormat('d-m-Y');
		$invoice_items = InvoiceItem::all()->where('invoice_id', $invoice->id);
		return view('invoice.edit', compact('invoice','invoice_items'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(InvoiceRequest $request, Invoice $invoice)
	{
		$invoice->update($request->all());
		return redirect('/invoice/'.$invoice->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Invoice $invoice)
	{
		$invoice->delete();
		return redirect('/invoice');
	}

	/**
	 * Show the specified resource to be deleted.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Invoice $invoice)
	{
		if (Gate::denies('edit-invoice', $invoice)) {
			abort(403);
		}

		return view('invoice.delete', compact('invoice'));
	}

	/**
	 * Show the printed version of the invoice
	 *
	 */
	public function prnt(Invoice $invoice)
	{
		if (Gate::denies('view-invoice', $invoice)) {
			abort(403);
		}

		$settings = \App::make('App\Contracts\Settings');
		return view('invoice.print', compact('invoice', 'settings'));
	}

	public function selectmerge(Invoice $invoice)
	{
		if (Gate::denies('edit-invoice', $invoice)) {
			abort(403);
		}

		return view('invoice.selectmerge', compact('invoice'));
	}

	public function domerge(Request $request)
	{
		$invoice1 = Invoice::find($request->merge_invoice_1);
		$invoice2 = Invoice::find($request->merge_invoice_2);

		$new_invoice = $invoice1->merge($invoice2);
		return redirect('/invoice');
	}

	public function view($uuid)
	{
		$invoice = Invoice::where('uuid','=',$uuid)->first();
		if ($invoice == null) {
			throw new CustomException(trans('exception_messages.invalid-uuid'));
		}

		Auth::login($invoice->user);
		$settings = \App::make('App\Contracts\Settings');
		return view('invoice.print', compact('invoice', 'settings'));
	}
}
