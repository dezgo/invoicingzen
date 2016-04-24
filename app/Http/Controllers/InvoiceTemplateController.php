<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\InvoiceTemplate;
use Illuminate\Support\Facades\Auth;

class InvoiceTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice_templates = InvoiceTemplate::allInCompany(Auth::user()->company_id);
        return view('invoice_template.index', compact('invoice_templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invoice_template.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'string|required',
            'template' => 'string|required',
        ]);

        $invoice_template = new InvoiceTemplate();
        $invoice_template->company_id = Auth::user()->company_id;
        $invoice_template->title = $request->title;
        $invoice_template->template = $request->template;
        $invoice_template->save();

		return redirect('/invoice_template');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceTemplate $invoice_template)
    {
        return view('invoice_template.edit', compact('invoice_template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceTemplate $invoice_template)
    {
        $invoice_template->title = $request->title;
        $invoice_template->template = $request->template;
        $invoice_template->update();
		return redirect('/invoice_template');
    }

    // show form to confirm deletion of resource
    public function delete(InvoiceTemplate $invoice_template)
    {
        return view('invoice_template.delete', compact('invoice_template'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy(InvoiceTemplate $invoice_template)
 	{
 		$invoice_template->delete();
 		return redirect('/invoice_template');
 	}
}
