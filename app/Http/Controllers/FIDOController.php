<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Firehed\U2F\Server;
use Firehed\U2F\RegisterResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class FIDOController extends Controller
{
    public $server;

    public function __construct()
    {
        $this->middleware('auth');
        $this->server = new Server();
        $this->server->setTrustedCAs(glob('certs/yubico.PEM'))
       ->setAppId('https://squirrel.test');
    }

    public function activate()
    {
        $request = $this->server->generateRegisterRequest();
        $sign_requests = $this->server->generateSignRequests([]);
        
        $jsonRequest = json_encode($request, JSON_UNESCAPED_SLASHES);
        Session::put('request', $request);
       
        $jsonSignRequests = json_encode(array_values($sign_requests));

        return view('2fa.fido', compact('jsonRequest', 'jsonSignRequests'));
    }

    public function complete(Request $request)
    {
        $request = $this->server->generateRegisterRequest();

        

        $sign_requests = $this->server->generateSignRequests([]);
        dd($request);

    }

    public function signature(Request $request){
        $user = Auth::user();
        $u2fRequestJson = $request->input('jsonRequest');
        $u2fSignatureJson = $request->input('jsonSignature');      


        
        $this->server->setRegisterRequest(Session::get('request'));

        $u2fSignature = json_decode($u2fSignatureJson);

     //   dd($_POST, $this->server);
        
        $resp = RegisterResponse::fromJson($u2fSignatureJson ?? '');
            

        // Attempt to register with parsed response
        $registration = $this->server->register($resp);

        // Store Registration alongside user
        $kha = substr($registration->getKeyHandleWeb(), 0, 10);
        $data['registrations'][$kha] = $registration;
        write_user_data($user, $data);

        // Return some JSON for the AJAX handler to use
        echo json_encode($_SESSION);

        try {
            

            // Parse response JSON
            
            
            
        } catch (SecurityException $e) {
            dd($e);
        } catch (InvalidDataException $e) {
            dd($e);
        } catch (\Throwable $e) {
            dd($e);
        }
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
