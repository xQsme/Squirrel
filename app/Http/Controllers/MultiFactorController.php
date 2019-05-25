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
        if($user->ask_pin){
            return view('auth.pin');
        }
        return redirect()->route('home');
    }

    public function askPin()
    {
        $user = \Auth::user();
        $user->ask_pin = true;
        $user->save();
        return;
    }

    public function validatePin(Request $request)
    {
        $user = \Auth::user();
        if(\Hash::check($request->code, \Auth::user()->pin))
        {
            $user->ask_pin=false;
            $user->save();
        }
        return redirect()->route('home');
    }

}
