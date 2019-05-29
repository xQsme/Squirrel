@extends('layouts.general')

@section('my-content')
@if(isset($message))
    <div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{$message}}
    </div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Set up FIDO</div>
                <div class="card-body" style="text-align:center">
                    <img src="./img/fido.png" style="width: 50%">
                    
                    <form action="{{route('fido-signature')}}" id="fido-form" class="form-group form-inline" method="post" style>
                        {{csrf_field()}}
                        <input name="jsonRequest" id="jsonRequest" type=hidden value={{urldecode($jsonRequest)}}>
                        <input name="jsonSignature" id="jsonSignature" type=hidden value=>
                        <button id="fido-submit" type="submit" class="btn btn-primary col-md-5">Complete Fido Registration</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    window.onload=function()
    {
        let request = null;

        document.getElementById('fido-form').addEventListener("submit", function(e) {
            e.preventDefault();
            var request = document.getElementById("jsonRequest").value;

            request = JSON.parse(request);
            console.log(request);
            
            u2f.register([request], [], u2fPostRegisterData);
            console.log(u2f.register);
        });

        function u2fPostRegisterData(sig) {
            if (sig.errorCode) { showAuthError(sig.errorCode); return; }

            // Send data from U2F token to server over AJAX
            console.log(sig);

            document.getElementById('jsonSignature').value = JSON.stringify(sig);
            document.getElementById('fido-form').submit()
            
        }

        function u2fSign(ajaxResponse) {
            setFieldText('auth_request_to_sign', JSON.stringify(ajaxResponse));
            showPress();

            u2f.sign(ajaxResponse, u2fPostSignData);
        }

        function u2fPostSignData(sig) {
            hidePress();
            setFieldText('auth_signature', JSON.stringify(sig));
            if (sig.errorCode) { showAuthError(sig.errorCode); return; }

            // Do auth POST
            ajaxPost('/complete_auth.php',
                    {"signature_str": JSON.stringify(sig)},
                    displayResponse,
                    displayResponse);
        }

        function displayResponse(resp) {
            console.log('response', resp);
        }
  
        function showAuthError(code) {
            // https://developers.yubico.com/U2F/Libraries/Client_error_codes.html
            switch (code) {
            case 1:
                message = 'other error';
                break;
            case 2:
                message = 'bad request';
                break;
            case 3:
                message = 'unsupported client configuration';
                break;
            case 4:
                message = 'ineligible request';
                break;
            case 5:
                message = 'timeout';
                break;
            }
            alert(message);
        };
    }
</script>