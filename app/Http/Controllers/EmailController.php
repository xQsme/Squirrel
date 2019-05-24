<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function activate()
    {
        return view('2fa.email');
    }

    public function confirm()
    {
        $data['content'] = "Squirrel confirmation code:";
        $code = str_random(20);
        $user = \Auth::user();
        $user->email_temp_code = \Hash::make($code);
        $user->save();
        $data['code'] = $code;
        \Mail::send(['text'=>'mail'], $data, function($message) {
            $message->to(\Auth::user()->email, \Auth::user()->name)->subject
               ('Squirrel E-Mail authentication confirmation');
            $message->from('SquirrelMCIF@gmail.com','Squirrel');
         });
        return view('2fa.email-confirm');
    }

    public function complete(Request $request)
    {
        if(\Hash::check($request->code, \Auth::user()->email_temp_code))
        {
            $user = \Auth::user();
            $user->email_code = \Hash::make($request->code);
            $user->email_authenticated = true;
            $user->save();
            $message = ['message_success' => 'E-Mail Authentication Set Up'];
            return redirect()->route('settings')->with($message);
        }
        $message = 'Wrong Code';
        return view('2fa.email-confirm', compact('message'));
    }

    public function deactivate()
    {
        $user = \Auth::user();
        $user->email_code = "";
        $user->save();
        $message = ['message_success' => 'E-Mail Authentication Removed'];
        return redirect()->route('settings')->with($message);
    }
}
