<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Invoice;
use App\InvoiceItem;
use App\Company;

class SuperAdminController extends Controller
{
    public function phpinfo()
    {
        return view('content.phpinfo');
    }

    public function stats()
    {
        $stats = ['total_invoices' => Invoice::all()->count(),
                  'total_line_items' => InvoiceItem::all()->count(),
                  'total_companies' => Company::all()->count(),
                  'total_users' => User::all()->count(),
                 ];

        $companies = Company::all()->sortBy('created_at');
        return view('content.stats', compact('stats', 'companies'));
    }
}
