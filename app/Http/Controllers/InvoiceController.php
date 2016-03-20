<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceItem;
use App\User;
use App\Email;
use App\Http\Requests\InvoiceRequest;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
	/**
	 * Check that the current user is allowed to see the specified invoice
	 *
	 * @return boolean
	 */
	private function checkOKToAccess(Invoice $invoice)
	{
		if (Auth::user()->isAdmin()) {
			return true;
		}
		else {
			return Auth::user()->id == $invoice->user->id;
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		if (Auth::user()->isAdmin()) {
			$invoices = Invoice::all();
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
	public function create(User $customer = null)
	{
		if (!Auth::user()->isAdmin()) {
			abort(403);
		}

		$invoice = new Invoice();
		if (!is_null($customer)) {
			$invoice->customer_id = $customer->id;
		}
		$invoice_items = InvoiceItem::invoiceItemList();
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
		if (!Auth::user()->isAdmin()) {
			abort(403);
		}
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
		if (!$this->checkOKToAccess($invoice)) {
			abort(403);
		}

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
		if (!Auth::user()->isAdmin()) {
			abort(403);
		}

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
		if (!Auth::user()->isAdmin()) {
			abort(403);
		}

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
		if (!Auth::user()->isAdmin()) {
			abort(403);
		}

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
		if (!Auth::user()->isAdmin()) {
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
		if (!$this->checkOKToAccess($invoice)) {
			abort(403);
		}

		return view('invoice.print', compact('invoice'));
	}

	/**
	 * Email the invoice to the Customer
	 */
	public function email(Invoice $invoice)
	{
		if (!Auth::user()->isAdmin()) {
			abort(403);
		}

		if ($invoice->user->email != '') {
			$email = new Email;
			$email->from = Auth::user()->email;
			$email->to = $invoice->user->email;
			$email->receiver_id = $invoice->user->id;
			$email->invoice_id = $invoice->id;
			$email->subject = 'Invoice '.$invoice->invoice_number;
			$email->invoice = $invoice;
			$email->body =
				'Hi '.$invoice->user->first_name.',<br />'.
				'<br />'.
				'Please find attached invoice '.$invoice->invoice_number.' for $'.
				number_format($invoice->total, 2).'<br />'.
				'<br />'.
				'Thanks,<br />'.
				Auth::user()->name.'<br />'.
				Auth::user()->business_name.
				$email->footer_text;

			return view('invoice.email', compact('email'));
		}
		else {
			\Session()->flash('status-warning', 'Customer does not have an email address!');
		}
	}

	/**
	 * Pay invoice
	 */
	public function pay(Invoice $invoice, Request $request)
	{
		$token = $request->stripeToken;
		$amount = $invoice->owing;

		try {
			$response = Auth::user()->charge($amount * 100, [
				'currency' => 'aud',
				'source' => $token,
				'description' => $invoice->description,
			]);
		} catch (Exception $e) {
			\Session()->flash('status-warning', 'There was a problem with your payment. '.
				'Please check the details and try again ('.$e->description.')');
				return redirect('/invoice/'.$invoice->id);
		}

		// update the invoice to show amount now paid
		$invoice->paid = $invoice->paid + $amount;
		$invoice->save();

		\Session()->flash('status-success', trans('invoice.payment-success', [
			'amount' => money_format('%i', $amount),
			'invoice_number' => $invoice->invoice_number]));
		return redirect('/invoice/'.$invoice->id);
	}
}
