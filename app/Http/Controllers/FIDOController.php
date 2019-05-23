<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class FIDOController extends Controller
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
        return view('google2fa.setup', compact('secret', 'qrCode'));
    }

    public function complete()
    {
        $ga = new GoogleAuthenticator();
        if($ga->verifyCode($request->secret, $request->code, 2))
        {
            $user = \Auth::user();
            $user->google2fa_secret = $request->secret;
            $user->save();
            $message = ['message_success' => 'Google Authenticator Set Up'];
            return redirect()->route('settings')->with($message);
        }
        $secret = $request->secret;
        $qrCode = $ga->getQRCodeGoogleUrl('Squirrel', $secret);
        $message = 'Wrong One Time Code';
        return view('google2fa.setup', compact('secret', 'qrCode', 'message'));
    }

    public function deactivate()
    {
        $user = \Auth::user();
        $user->google2fa_secret = "";
        $user->save();
        $message = ['message_success' => 'Google Authenticator Removed'];
        return redirect()->route('settings')->with($message);
    }
}
