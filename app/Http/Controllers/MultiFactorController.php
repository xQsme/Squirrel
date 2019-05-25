<?php

namespace App\Http\Controllers;

use App\Classes\GoogleAuthenticator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MultiFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = \Auth::user();
        if(!empty($user->google_code)){
            if(!$user->google_authenticated){
                return view('auth.google');
            }
        }

        if(!empty($user->fido_code)){
            if(!$user->fido_authenticated){
                return view('auth.fido');
            }
        }
        if(!empty($user->email_code)){
            if(!$user->email_authenticated){
                return view('auth.email');
            }
        }
        if(!empty($user->sms_code)){
            if(!$user->sms_authenticated){
                return view('auth.sms');
            }
        }
        if($user->session != \Session::getId()){
            return view('auth.pin');
        }
        return redirect()->route('home');
    }

    public function validatePin(Request $request)
    {
        $user = \Auth::user();
        if(\Hash::check($request->code, \Auth::user()->pin))
        {
            $user->session=\Session::getId();
            $user->save();
        }
        return redirect()->route('home');
    }

    public function changePin()
    {
        return view('pin');
    }

    public function savePin(Request $request)
    {
        if(strlen($request->pin) < 4 || strlen($request->pin) > 6)
        {
            $message = ['message_error' => 'Pin must be between 4 and 6 characters'];
            return redirect()->back()->with($message);
        }
        $user = \Auth::user();
        $user->pin = \Hash::make($request->pin);
        $user->save();
        $message = ['message_success' => 'Pin Changed'];
        return redirect()->route('settings')->with($message);
    }

    public function forgotPin()
    {
        $user = \Auth::user();
        $user->session=\Session::getId();
        $user->google_authenticated = false;
        $user->fido_authenticated = false;
        $user->sms_authenticated = false;
        $user->email_authenticated = false;
        $user->save();
        return redirect()->route('home');
    }

}
