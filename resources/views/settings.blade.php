@extends('layouts.general')

@section('my-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Settings</div>
                <div class="card-body">
                    <input type="checkbox" id="google" @if(\Auth::user()->google_code != "") checked @endif>Google Authenticator<br>
                    <input type="checkbox" id="fido" @if(\Auth::user()->fido_code != "") checked @endif>FIDO Authenticator<br>
                    <input type="checkbox" id="email" @if(\Auth::user()->email_code != "") checked @endif>E-Mail Code<br>
                    <input type="checkbox" id="sms" @if(\Auth::user()->sms_code != "") checked @endif>SMS Code<br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    window.onload=function()
    {
        let google = document.getElementById("google");
        google.onclick = () => {
            if(google.checked)
            {
                window.location = window.location.origin +  "/google-activate";
            }
            else
            {
                window.location = window.location.origin +  "/google-deactivate";
            }
        };
        let fido = document.getElementById("fido");
        fido.onclick = () => {
            if(fido.checked)
            {
                window.location = window.location.origin +  "/fido-activate";
            }
            else
            {
                window.location = window.location.origin +  "/fido-deactivate";
            }
        };
        let email = document.getElementById("email");
        email.onclick = () => {
            if(email.checked)
            {
                window.location = window.location.origin +  "/email-activate";
            }
            else
            {
                window.location = window.location.origin +  "/email-deactivate";
            }
        };
        let sms = document.getElementById("sms");
        sms.onclick = () => {
            if(sms.checked)
            {
                window.location = window.location.origin +  "/settings", "sms-activate";
            }
            else
            {
                window.location = window.location.origin +  "/sms-deactivate";
            }
        };
    }
</script>
