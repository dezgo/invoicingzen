<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/invoice');
        }
        else {
            return view('content.index');
        }
    }
}
