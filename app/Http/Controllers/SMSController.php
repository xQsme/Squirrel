<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class SMSController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function activate()
    {
        $message = ['message_error' => 'SMS service is paid ¯\_(ツ)_/¯'];
        return redirect()->back()->with($message);
    }

    public function deactivate()
    {
        $message = ['message_error' => 'SMS service is paid ¯\_(ツ)_/¯'];
        return redirect()->back()->with($message);
    }
}
