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

        if(!empty($user->google_code)){
            if(!$user->google_authenticated){
                return redirect()->route('multi-factor');
            }
        }

        if(!empty($user->fido_code)){
            if(!$user->fido_authenticated){
                return redirect()->route('multi-factor');
            }
        }
        if(!empty($user->email_code)){
            if(!$user->email_authenticated){
                return redirect()->route('multi-factor');
            }
        }
        if(!empty($user->sms_code)){
            if(!$user->sms_authenticated){
                return redirect()->route('multi-factor');
            }
        }

        if($user->ask_pin){
            return redirect()->route('multi-factor');
        }

        return $next($request);    
    }
}
