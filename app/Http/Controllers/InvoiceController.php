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
use App\Factories\NextInvoiceNumberFactory;
use App\Exceptions\CustomException;
use App\InvoiceMerger;
use App\Services\PDFStreamInvoiceGenerator;
use App\Factories\SettingsFactory;
use App\Services\CustomInvoice\InvoiceGenerator;
use App\InvoiceTemplate;

class InvoiceController extends Controller
{
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
		$this->authorize('create-invoice');

		if (!is_null($customer)) {
			$invoice->customer_id = $customer->id;
		}

        $invoice->invoice_number = NextInvoiceNumberFactory::get(Auth::user()->company_id);

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
		$this->authorize('create-invoice');
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
		$this->authorize('view-invoice', $invoice);

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
		$this->authorize('edit-invoice', $invoice);

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
		$this->authorize('edit-invoice', $invoice);
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
		$this->authorize('delete-invoice', $invoice);
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
		$this->authorize('delete-invoice', $invoice);
		return view('invoice.delete', compact('invoice'));
	}

	/**
	 * Show the printed version of the invoice
	 *
	 */
	public function prnt(Invoice $invoice)
	{
		$this->authorize('view-invoice', $invoice);
		return $this->viewPrint($invoice);
	}

	public function selectmerge(Invoice $invoice)
	{
		$this->authorize('edit-invoice', $invoice);
		return view('invoice.selectmerge', compact('invoice'));
	}

	public function domerge(Request $request)
	{
		$invoice1 = Invoice::find($request->merge_invoice_1);
		$invoice2 = Invoice::find($request->merge_invoice_2);
		$this->authorize('edit-invoice', $invoice1);
		$this->authorize('edit-invoice', $invoice2);

		$invoice_merger = new InvoiceMerger($invoice1, $invoice2);
		$invoice_merger->merge();
		return redirect('/invoice');
	}

	// no auth check on this one, anyone with the link can see the invoice
	// and also gets automatically logged in as the invoice owner
	// not sure if this is the best idea
	public function view($uuid)
	{
		$invoice = Invoice::where('uuid','=',$uuid)->first();
		if ($invoice == null) {
			throw new CustomException(trans('exception_messages.invalid-uuid'));
		}

		Auth::login($invoice->user);
		return $this->viewPrint($invoice);
	}

	private function viewPrint(Invoice $invoice)
	{
		$settings = SettingsFactory::create();
		$invoice_generator = new InvoiceGenerator();
		$template = InvoiceTemplate::get($invoice->type, Auth::user()->company);
		$invoice_content = $invoice_generator->output($template, $invoice);
		return view('invoice.print', compact('invoice', 'settings', 'invoice_content'));
	}

	public function generate_pdf(Invoice $invoice)
	{
		$this->authorize('view-invoice', $invoice);
		$pdf = new PDFStreamInvoiceGenerator();
        $pdf->create($invoice);

        return $pdf->output();
	}

	public function markPaid(Invoice $invoice)
	{
		$this->authorize('edit-invoice', $invoice);
		$invoice->markPaid();
		return $this->viewPrint($invoice);
	}

	public function markUnpaid(Invoice $invoice)
	{
		$this->authorize('edit-invoice', $invoice);
		$invoice->markUnpaid();
		return $this->viewPrint($invoice);
	}

	/**
	 * Pay invoice
	 */
	public function pay(Invoice $invoice, Request $request)
	{
		$this->authorize('edit-invoice', $invoice);
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
				return redirect('/invoice/'.$invoice->id.'/print');
		}

		// update the invoice to show amount now paid
		$invoice->paid = $invoice->paid + $amount;
		$invoice->save();

		\Session()->flash('status-success', trans('invoice.payment-success', [
			'amount' => money_format('%i', $amount),
			'invoice_number' => $invoice->invoice_number]));
		return redirect('/invoice/'.$invoice->id.'/print');
	}
}
