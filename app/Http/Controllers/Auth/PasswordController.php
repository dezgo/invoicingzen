<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $redirectPath = '/';
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // public function sendResetLinkEmail(Request $request)
    // {
    //     dd($request);
    //     $cssToInlineStyles = new CssToInlineStyles();
    //
    //     $html = parent::sendResetLinkEmail($request);
    //     $css = file_get_contents(__DIR__ . '/css/all.css');
    //
    //     // output
    //     return $cssToInlineStyles->convert(
    //         $html,
    //         $css
    //     );
    //
    // }
}
