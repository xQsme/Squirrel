<?php

namespace App\Listeners;

use App\Login as Log;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if(!\Auth::viaRemember())
        {
            $user = \Auth::user();
            $user->google_authenticated = false;
            $user->fido_authenticated = false;
            $user->sms_authenticated = false;
            $user->email_authenticated = false;
            $user->save();
            if($event->user->google_code == '' && $event->user->fido_code == '' && $event->user->email_code == '')
            {
                $login = new Log();
                $login->user_id = $event->user->id;
                $login->ip = \Request::ip();
                $login->save();
            }
        }
    }
}
