<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use WebAuthn\WebAuthn;
use App\Login;
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
        //$formats[] = 'android-safetynet';   
        $formats[] = 'fido-u2f';   
        //$formats[] = 'none';   
        $formats[] = 'packed';   
        //$formats[] = 'tpm';
        
        // new Instance of the server library.
        // make sure that $rpId is the domain name.
        $this->WebAuthn = new WebAuthn('SQRL', 'squirrel-mcif.me', $formats);
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
        $fidoAuthenticationMethod->signatureCounter = encrypt($data->signatureCounter);
        $fidoAuthenticationMethod->AAGUID = $data->AAGUID;
        $fidoAuthenticationMethod->user()->associate($loggedOnUser);
        $fidoAuthenticationMethod->save();

        $loggedOnUser->fido_authenticated = true;
        $loggedOnUser->fido_code = 1;
        $loggedOnUser->save();

        $return = new \stdClass();
        $return->success = true;
        $return->msg = 'Registration Success.';

        print(json_encode($return));
    }

    public function getGetArgs(Request $request) {
        $ids = array();
        $loggedOnUser = \Auth::user();
        $userFidoAuthMethods = $loggedOnUser->fidoAuthenticationMethods;

        // load registrations from session stored there by processCreate.
        // normaly you have to load the credential Id's for a username
        // from the database.
        if (is_a($userFidoAuthMethods, 'Illuminate\Database\Eloquent\Collection')) {
            foreach ($userFidoAuthMethods as $reg) {
                $ids[] = $reg->credentialId;
            }
        }
        if (count($ids) === 0) {
            throw new \Exception('no registrations in session.');
        }

        $getArgs = $this->WebAuthn->getGetArgs($ids);
        Session::put('challenge', $this->WebAuthn->getChallenge());
        print(json_encode($getArgs));
        // save challange to session. you have to deliver it to processGet later.
    }

    public function processGet(Request $request) {
        $clientDataJSON = base64_decode($request->input('clientDataJSON'));
        $authenticatorData = base64_decode($request->input('authenticatorData'));
        $signature = base64_decode($request->input('signature'));
        $id = base64_decode($request->input('id'));
        $challenge = Session::get('challenge');
        $credentialPublicKey = null;

        // looking up correspondending public key of the credential id
        // you should also validate that only ids of the given user name are taken for the login.
        $loggedOnUser = \Auth::user();
        $userFidoAuthMethods = $loggedOnUser->fidoAuthenticationMethods;
        if (is_a($userFidoAuthMethods, 'Illuminate\Database\Eloquent\Collection')) {
            foreach ($userFidoAuthMethods as $reg) {
                if ($reg->credentialId === $id) {
                    $credentialPublicKey = $reg->credentialPublicKey;
                    break;
                }
            }
        }
        if ($credentialPublicKey === null) {
            throw new \Exception('Public Key for credential ID not found!');
        }
        // process the get request. throws WebAuthnException if it fails
        $this->WebAuthn->processGet($clientDataJSON, $authenticatorData, $signature, $credentialPublicKey, $challenge);
        $return = new \stdClass();
        $return->success = true;

        if($loggedOnUser->email_code == '')
        {
            $login = new Login();
            $login->user_id = $loggedOnUser->id;
            $login->ip = \Request::ip();
            $login->save();
        }
        
        $loggedOnUser->fido_authenticated = true;
        $loggedOnUser->save();

        print(json_encode($return));
    }

    public function deactivate()
    {
        $user = \Auth::user();
        $user->fido_code = "";
        $user->fido_authenticated = 0;

        $userFidoAuthMethods = $user->fidoAuthenticationMethods;
        if (is_a($userFidoAuthMethods, 'Illuminate\Database\Eloquent\Collection')) {
            foreach ($userFidoAuthMethods as $method) {
                $method->delete();
            }
        }
        $user->save();
        $message = ['message_success' => 'Fido Authentication Removed'];

        return redirect()->route('settings')->with($message);
    }
}
