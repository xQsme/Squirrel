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
        return redirect()->route('home');
    }

}
