<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MultiFactorAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if(!empty($user->google_code) || !empty($user->fido_code) || !empty($user->email_code) || !empty($user->sms_code)){
            if(!$user->authenticated){
                return redirect()->route('multi-factor');
            }
        }

        if($user->session != \Session::getId()){
            return view('auth.pin');
        }

        return $next($request);    
    }
}
