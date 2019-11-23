@extends('layouts.general')

@section('my-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">MFA Selection Method</div>
                <div class="card-body">
                    <span style="text-align:left" class="m-2">You have multifactor authentication enabled, please pick the method you wish to use to authenticate yourself:</span>
                    <div style="text-align:center" class="m-2 list-group">
                        @if(!empty($user->google_code))
                            <a class="list-group-item" href="{{route('get-auth-view', ['method' => 'google'])}}" >Google Authenticator</a>
                        @endif
                        @if(!empty($user->fido_code))
                            <a class="list-group-item" href="{{route('get-auth-view', ['method' => 'fido'])}}"> FIDO Authenticator</a>
                        @endif
                        @if(!empty($user->email_code))
                            <a class="list-group-item" href="{{route('get-auth-view', ['method' => 'email'])}}">Email Authenticator</a>
                        @endif
                        @if(!empty($user->sms_code))
                            <a class="list-group-item" href="{{route('get-auth-view', ['method' => 'sms'])}}">SMS Authenticator</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection