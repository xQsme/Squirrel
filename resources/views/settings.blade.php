@extends('layouts.general')

@section('my-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Settings</div>
                <div class="card-body">
                    <input type="checkbox" name="google" id="google" @if(\Auth::user()->google2fa_secret != "") checked @endif>Google Authenticator<br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    window.onload=function()
    {
        let checkbox = document.getElementById("google");
        checkbox.onclick = () => {
            if(checkbox.checked)
            {
                window.location = window.location.href.replace("settings", "activate");
            }
            else
            {
                window.location = window.location.href.replace("settings", "deactivate");
            }
        };
    }
</script>
