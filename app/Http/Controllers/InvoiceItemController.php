<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceItem;
use App\InvoiceItemCategory;
use App\Http\Requests\InvoiceItemRequest;

class InvoiceItemController extends Controller
{
    /**
     * First step in creating an invoice item - select category
     *
     * @return \Illuminate\Http\Response
     */
    public function create1(Invoice $invoice)
    {
        $invoice_item_categories = \App\InvoiceItemCategory::all()->lists('description', 'id');
        return view('invoiceitem.create1', compact('invoice','invoice_item_categories'));
    }

    /**
     * End step 1. Pass through invoice to which item relates and
     * and the selected category to step 2
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store1(Request $request, $invoice_id)
    {
        $this->validate($request, [
                'category_id' => 'required',
            ]);

        return redirect('/invoice/'.$invoice_id.'/item/'.$request->category_id.'/create2');
    }

    /**
     * Second step in creating an invoice item - select description or
     * enter a new one
     *
     * @return \Illuminate\Http\Response
     */
    public function create2(Invoice $invoice, InvoiceItemCategory $category)
    {
        $invoice_item_list = InvoiceItem::invoiceItemList($category->id);
        return view('invoiceitem.create2', compact('invoice', 'category', 'invoice_item_list'));
    }

    /**
     * End step 2. Pass category and description and create invoice item
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store2(Request $request, Invoice $invoice, InvoiceItemCategory $category)
    {
        $this->validate($request, [
                'description' => 'required',
            ]);

        $invoice_id = $invoice->id;
        $invoice_item = new InvoiceItem();
        $invoice_item->invoice_id = $invoice->id;
        $invoice_item->category_id = $category->id;
        $invoice_item->description = $request->description;
        $invoice_item->quantity = 1;
        $invoice_item->price = $invoice_item->getPrice();
        $invoice_item->save();

        return view('invoiceitem.create', compact('invoice','invoice_item', 'invoice_id'));
    }

    /**
     * No longer showing the invoiceitem list separately, shown when viewing
     * an invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('/invoice');
    }

    /**
    * First step in creating an invoice item - select category
     *
     * @return \Illuminate\Http\Response
     */
    public function create(InvoiceItem $invoice_item)
    {
        return view('invoiceitem.create', compact('invoice_item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceItemRequest $request, InvoiceItem $invoice_item)
    {
        $invoice_item->quantity = $request->quantity;
        $invoice_item->price = $request->price;
        $invoice_item->save();
        return redirect('/invoice/'.$invoice_item->invoice->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceItem $invoice_item)
    {
        return view('invoiceitem.show', compact('invoice_item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceItem $invoice_item)
    {
        return view('invoiceitem.edit', compact('invoice_item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvoiceItemRequest $request, InvoiceItem $invoice_item)
    {
        $invoice_item->update($request->all());
        return redirect('/invoice/'.$invoice_item->invoice->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceItem $invoice_item)
    {
        $invoice_item->delete();
        return redirect('/invoice/'.$invoice_item->invoice->id);
    }

    /**
     * Show the specified resource to be deleted.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(InvoiceItem $invoice_item)
    {
        return view('invoiceitem.delete', compact('invoice_item'));
    }

    /**
     * Toggle the invoiceitem ready flag (called via ajax)
     */
    public function ready(InvoiceItem $invoice_item)
    {
        if (env('APP_ENV') == 'local') {
            \Log::info('Invoice item '.$invoice_item->id.' ready status updated');
        }
        $invoice_item->ready = !$invoice_item->ready;
        $invoice_item->save();
    }
}
