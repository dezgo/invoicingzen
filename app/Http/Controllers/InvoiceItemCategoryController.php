<?php

namespace App\Http\Controllers;

use App\InvoiceItemCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceItemCategoryRequest;

class InvoiceItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice_item_categories = InvoiceItemCategory::all();
        return view('invoiceitemcategory.index', compact('invoice_item_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invoiceitemcategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceItemCategoryRequest $request)
    {
        InvoiceItemCategory::create($request->all());
        return redirect('/invoice_item_category');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceItemCategory $invoice_item_category)
    {
        return view('invoiceitemcategory.show', compact('invoice_item_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceItemCategory $invoice_item_category)
    {
        return view('invoiceitemcategory.edit', compact('invoice_item_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvoiceItemCategoryRequest $request, InvoiceItemCategory $invoice_item_category)
    {
        $invoice_item_category->update($request->all());
        return redirect('/invoice_item_category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceItemCategory $invoice_item_category)
    {
        $invoice_item_category->delete();
        return redirect('/invoice_item_category');
    }

    /**
     * Show the specified resource to be deleted.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(InvoiceItemCategory $invoice_item_category)
    {
        return view('invoiceitemcategory.delete', compact('invoice_item_category'));
    }
}
