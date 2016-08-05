<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tools;
use Carbon\Carbon;

class ToolsController extends Controller
{
    public function accruals()
    {
        return view('tools.accruals');
    }

    public function accruals_calc(Request $request)
    {
        $request->flash();
        $data = Tools::getAccruals(
            new Carbon($request->start_date),
            $request->amount,
            $request->every_n_days,
            $request->periods);
        return view('tools.accruals', compact('data'));
    }

    public function allocate_prepayment()
    {
        return view('tools.allocate_prepayment');
    }

    public function allocate_prepayment_calc(Request $request)
    {
        $request->flash();
        $data = Tools::allocatePrepayment(
            new Carbon($request->start_date),
            new Carbon($request->end_date),
            $request->amount);
        return view('tools.allocate_prepayment', compact('data'));
    }
}
