<?php

namespace App\Http\Controllers;

use App\Login;
use App\Classes\GoogleAuthenticator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoogleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function activate()
    {
        $ga = new GoogleAuthenticator();
        $secret = $ga->createSecret();
        $qrCode = $ga->getQRCodeGoogleUrl('Squirrel', $secret);
        return view('2fa.google', compact('secret', 'qrCode'));
    }

    public function complete(Request $request)
    {
        $ga = new GoogleAuthenticator();
        if($ga->verifyCode($request->secret, $request->code, 2))
        {
            $user = \Auth::user();
            $user->google_code = encrypt($request->secret);
            $user->google_authenticated = true;
            $user->save();
            $message = ['message_success' => 'Google Authenticator Set Up'];
            return redirect()->route('settings')->with($message);
        }
        $secret = $request->secret;
        $qrCode = $ga->getQRCodeGoogleUrl('Squirrel', $secret);
        $message = 'Wrong One Time Code';
        return view('2fa.google', compact('secret', 'qrCode', 'message'));
    }

    public function deactivate()
    {
        $user = \Auth::user();
        $user->google_code = "";
        $user->save();
        $message = ['message_success' => 'Google Authenticator Removed'];
        return redirect()->route('settings')->with($message);
    }

    function authenticate(Request $request)
    {
        $ga = new GoogleAuthenticator();
        $user = \Auth::user();
        if($ga->verifyCode(decrypt(\Auth::user()->google_code), $request->code, 2))
        {
            if($user->fido_code == '' && $user->email_code == '')
            {
                $login = new Login();
                $login->user_id = $user->id;
                $login->ip = \Request::ip();
                $login->save();
            }
            $user->google_authenticated=true;
            $user->save();
        }
        return redirect()->route('home');
    }
}
