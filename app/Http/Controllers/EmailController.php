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
        $data['code'] = $code;
        \Mail::send(['text'=>'mail'], $data, function($message) {
            $message->to(\Auth::user()->email, \Auth::user()->name)->subject
               ('Squirrel E-Mail authentication confirmation');
            $message->from('SquirrelMCIF@gmail.com','Squirrel');
         });
        return view('2fa.email-confirm', compact('code'));
    }

    public function complete(Request $request)
    {
        if($request->secret == $request->code)
        {
            $user = \Auth::user();
            $user->email_code = $request->secret;
            $user->save();
            $message = ['message_success' => 'E-Mail Authentication Set Up'];
            return redirect()->route('settings')->with($message);
        }
        $message = 'Wrong Code';
        $code = $request->secret;
        return view('2fa.email-confirm', compact('code', 'message'));
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
