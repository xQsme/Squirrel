<?php

namespace App\Http\Controllers;

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

}