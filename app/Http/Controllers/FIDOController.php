<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use WebAuthn\WebAuthn;
use Illuminate\Support\Facades\Log;
use Session;
use Illuminate\Http\Request;
use App\FidoAuthenticationMethod;

class FIDOController extends Controller
{   
    public $requireResidentKey, $WebAuthn;

    public function __construct()
    {
        $this->middleware('auth');

        //session_start();
        // read get argument and post body
        //$fn = $_GET['fn'];
        //$this->requireResidentKey = false;
        /*
        $post = trim(file_get_contents('php://input'));
        if ($post) {
            $post = json_decode($post);
        }*/
    
        // Formats
        $formats = array();
        $formats[] = 'android-key';   
        $formats[] = 'android-safetynet';   
        $formats[] = 'fido-u2f';   
        $formats[] = 'none';   
        $formats[] = 'packed';   
        //$formats[] = 'tpm';
        
        // new Instance of the server library.
        // make sure that $rpId is the domain name.
        $this->WebAuthn = new WebAuthn('SQRL', 'sqrl.test', $formats);
        // add root certificates to validate new registrati
        $this->WebAuthn->addRootCertificates('rootCertificates/solo.pem');
        $this->WebAuthn->addRootCertificates('rootCertificates/yubico.pem');
        $this->WebAuthn->addRootCertificates('rootCertificates/hypersecu.pem');
        $this->WebAuthn->addRootCertificates('rootCertificates/globalSign.pem');
        $this->WebAuthn->addRootCertificates('rootCertificates/googleHardware.pem');
    }

    public function activate()
    {
        return view('2fa.fido');
    }

    public function getCreateArgs()
    {
        $user = \Auth::user();
        $createArgs = $this->WebAuthn->getCreateArgs($user->id, $user->name, $user->name, 20);
        print(json_encode($createArgs));
        // save challange to session. you have to deliver it to processGet later.
        Session::put('challenge', $this->WebAuthn->getChallenge());
    }

    public function processCreate(Request $request) {
        $loggedOnUser = \Auth::user();

        $clientDataJSON = base64_decode($request->input('clientDataJSON'));
        $attestationObject = base64_decode($request->input('attestationObject'));
        $challenge = Session::get('challenge');
        // processCreate returns data to be stored for future logins. in this example we store it in the php session.
        // Normaly you have to store the data in a database connected with the user name.
        $data = $this->WebAuthn->processCreate($clientDataJSON, $attestationObject, $challenge);

        $fidoAuthenticationMethod = new FidoAuthenticationMethod;

        $fidoAuthenticationMethod->credentialId = $data->credentialId;
        $fidoAuthenticationMethod->credentialPublicKey = $data->credentialPublicKey;
        $fidoAuthenticationMethod->certificate = $data->certificate;
        $fidoAuthenticationMethod->signatureCounter = $data->signatureCounter;
        $fidoAuthenticationMethod->AAGUID = $data->AAGUID;
        $fidoAuthenticationMethod->user()->associate($loggedOnUser);
        $fidoAuthenticationMethod->save();

        $return = new \stdClass();
        $return->success = true;
        $return->msg = 'Registration Success.';

        print(json_encode($return));
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
