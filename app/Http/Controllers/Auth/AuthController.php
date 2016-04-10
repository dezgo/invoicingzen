<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Log;
use App\Company;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    // private function getRootURL(Request $request, User $user)
    // {
    //     $host = $request->server('HTTP_HOST');
    //     $host_names = explode(".", $host);
    //     $subdomain = $user->company->subdomain;
    //     if ($host_names[0] != $subdomain) {
    //         return 'http://'.$subdomain.'.'.$host;
    //     }
    // }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest', ['except' => ['logout', 'edit', 'update']]);
    }

    // protected function authenticated(Request $request, User $user)
    // {
    //     return redirect($this->getRootURL($request, $user));
    // }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'business_name' => 'required|unique:users',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $company = Company::create([
            'company_name' => $data['business_name'],
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'business_name' => $data['business_name'],
        ]);
        $user->roles()->attach(2);
        $user->company_id = $company->id;
        $user->save();

        return $user;
    }

    /**
     * Open user profile for updating
     *
     * @param  array  $data
     * @return User
     */
    protected function edit()
    {
        $user = Auth::user();
        return view('auth.edit', compact('user'));
    }

    /**
     * Update made, process it
     *
     * @param  array  $data
     * @return User
     */
    protected function update(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.Auth::user()->id,
            'business_name' => 'required|unique:users,business_name,'.Auth::user()->id,
        ]);

        Auth::user()->name = $request->name;
        Auth::user()->email = $request->email;
        Auth::user()->save();

        $request->session()->flash('status', 'User record updated');
        $user = Auth::user();

        return view('auth.edit', compact('user'));
    }
}
