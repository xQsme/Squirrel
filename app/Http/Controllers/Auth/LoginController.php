<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if(!\Auth::viaRemember()){
            $user = \Auth::user();
            $user->google_authenticated = false;
            $user->fido_authenticated = false;
            $user->sms_authenticated = false;
            $user->email_authenticated = false;
        }
        $user->session = \Session::getId();
        $user->save();
    }

    protected function credentials(Request $request)
    {
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username()
            : 'name';

        return [
            $field => $request->get($this->username()),
            'password' => $request->password,
        ];
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->google_authenticated = false;
        $user->fido_authenticated = false;
        $user->sms_authenticated = false;
        $user->email_authenticated = false;
        $user->save();

        $this->guard()->logout();

        $request->session()->invalidate();       

        return $this->loggedOut($request) ?: redirect('/');
    }

}
