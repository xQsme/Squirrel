<?php

namespace App\Http\Controllers;

use Google2FA;
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
        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Save the registration data in an array
        $registration_data = \Auth::user();
        // Add the secret key to the registration data
        $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();

        // Generate the QR image. This is the image the user will scan with their app
     // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );

        // Pass the QR barcode image to our view
        return view('google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret']]);
    }

    public function complete(Request $request){

        if($request->code != '' && Google2FA::verifyGoogle2FA($request->secret, $request->code))
        {
            $user = \Auth::user();
            $user->google2fa_secret = $request->secret;
            $user->save();
            $message = ['message_success' => 'Google Authenticator Set Up'];
            return redirect()->route('settings')->with($message);
        }
        $google2fa = app('pragmarx.google2fa');
        $registration_data = \Auth::user();
        $registration_data["google2fa_secret"] = $request->secret;
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );
        return view('google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret'], 'message' => 'Wrong One Time Code']);
    }
}
