<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            //Auth::guard('api')->user()->token()->revoke(); //revoke last token
            $user = Auth::user();
            $response = [
                'status' => 'success',
                'email' => $user->email,
                'name' => $user->name,
                'user_id' => $user->id,
                'token' => $user->createToken('MyApp')->accessToken
            ];
            $user->logins[0]->source='App';
            $user->logins[0]->save();
            return response()->json($response, 200);
        }
        else if (Auth::attempt(['name' => request('email'), 'password' => request('password')]))
        {
            //Auth::guard('api')->user()->token()->revoke(); //revoke last token
            $user = Auth::user();
            $response = [
                'status' => 'success',
                'email' => $user->email,
                'name' => $user->name,
                'user_id' => $user->id,
                'token' => $user->createToken('MyApp')->accessToken
            ];
            $user->logins[0]->source='App';
            $user->logins[0]->save();
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

    public function dump(Request $request)
    {
        $response = Auth::user()->logins;

        foreach($response as $login)
        {
            $login->date = $login->created_at->format('d/m/Y');
            $login->time = $login->created_at->format('H:i:s');
        }

        return response()->json($response);
    }

    function register(Request $request)
{
    $valid = validator($request->only('email', 'name', 'password'), [
        'name' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    if ($valid->fails()) {
        $jsonError=response()->json($valid->errors()->all(), 400);
        return $jsonError;
    }

    $data = request()->only('email','name','password');

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password'])
    ]);

    $jsonSuccess=response()->json('Registered', 200);
    return $jsonSuccess;
}

}