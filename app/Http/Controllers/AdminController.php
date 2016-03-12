<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		return view('admin.settings', compact('settings'));
	}

    public function update(Request $request)
    {
		$this->validate($request, [
			'next_invoice_number' => 'numeric',
			'markup' => 'numeric',
			]);

		\Setting::set('next_invoice_number', $request->next_invoice_number);
		\Setting::set('markup', $request->markup);
		\Setting::setExtraColumns(['company_id' => Company::my_id()]);
        \Setting::save();
        $request->session()->flash('status', trans('settings.update_success'));
        return redirect(url('/settings'));
    }
}
