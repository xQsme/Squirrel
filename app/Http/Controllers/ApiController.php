<?php

namespace App\Http\Controllers;

use App\User;
use App\Login;
use App\Classes\GoogleAuthenticator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $user = Auth::user();
            $response = [
                'status' => 'success',
                'email' => $user->email,
                'name' => $user->name,
                'user_id' => $user->id,
                'token' => $user->createToken('MyApp')->accessToken,
                'google' => $user->google_code != '',
                'fido' => $user->fido_code != '',
                'email_code' => $user->email_code != ''
            ];
            return response()->json($response, 200);
        }
        else if (Auth::attempt(['name' => request('email'), 'password' => request('password')]))
        {
            $user = Auth::user();
            $response = [
                'status' => 'success',
                'email' => $user->email,
                'name' => $user->name,
                'user_id' => $user->id,
                'token' => $user->createToken('MyApp')->accessToken,
                'google' => $user->google_code != '',
                'fido' => $user->fido_code != '',
                'email_code' => $user->email_code != ''
            ];
            return response()->json($response, 200);
        }
        else
        {
            $response = [
                'status' => 'error',
                'error' => 'Unauthorised'
            ];
            return response()->json(['error' => $response], 401);
        }
    }

    public function logout()
    {
        Auth::guard('api')->user()->token()->revoke();
        Auth::guard('api')->user()->token()->delete();

        $response = [
            'status' => 'ok',
            'message' => 'logout success'
        ];

        return response()->json($response, 200);
    }

    public function dump()
    {
        $response['logins'] = Auth::user()->logins;
        
        foreach($response['logins'] as $login)
        {
            $login->date = $login->created_at->format('d/m/Y');
            $login->time = $login->created_at->format('H:i:s');
        }

        return response()->json($response);
    }

    function register(Request $request)
    {
        $valid = validator($request->only('email', 'name', 'password', 'pin'), [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'pin' => 'required|string|min:4|max:6'
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return $jsonError;
        }

        $data = request()->only('email','name','password', 'pin');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'pin' => \Hash::make($data['pin']),
        ]);

        $jsonSuccess=response()->json('Registered', 200);
        return $jsonSuccess;
    }

    function google()
    {
        $ga = new GoogleAuthenticator();
        $user = \Auth::user();
        if($ga->verifyCode(decrypt(\Auth::user()->google_code), request('code'), 2))
        {
            if($user->fido_code == '' && $user->email_code == '')
            {
                $login = new Login();
                $login->user_id = $user->id;
                $login->source="App";
                $login->ip = \Request::ip();
                $login->save();
            }
            $jsonSuccess=response()->json('Authenticated', 200);
            return $jsonSuccess;
        }
        $jsonError=response()->json('Failed', 400);
        return $jsonError;
    }

    function email()
    {
        $data['content'] = "Squirrel confirmation code:";
        $code = str_random(10);
        $user = \Auth::user();
        $user->email_code = \Hash::make($code);
        $user->save();
        $data['code'] = $code;
        \Mail::send(['text'=>'mail'], $data, function($message) {
            $message->to(\Auth::user()->email, \Auth::user()->name)->subject
               ('Squirrel E-Mail authentication');
            $message->from('SquirrelMCIF@gmail.com','Squirrel');
         });
        $jsonSuccess=response()->json($code, 200);
        return $jsonSuccess;
    }

    function validateEmail()
    {
        if(\Hash::check(request('code'), \Auth::user()->email_code))
        {
            $login = new Login();
            $login->user_id = \Auth::user()->id;
            $login->source="App";
            $login->ip = \Request::ip();
            $login->save();
            $jsonSuccess=response()->json('Authenticated', 200);
            return $jsonSuccess;
        }
        $jsonError=response()->json('Failed', 400);
        return $jsonError;
    }

}