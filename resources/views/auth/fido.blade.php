@extends('layouts.general')

@section('my-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">FIDO Authenticator</div>
                <div class="card-body" style="text-align:center">
                    <img src="./img/fido.png" style="width: 50%">
                    <div id="fido-div" class="form-group form-inline">
                        {{csrf_field()}}
                        <button type="button" onclick="checkregistration()" class="btn btn-primary col-md-5 mx-auto mt-3">Click here to authenticate yourself</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>

    /**
     * checks a FIDO2 registration
     * @returns {undefined}
     */
    function checkregistration() {
        if (!window.fetch || !navigator.credentials || !navigator.credentials.create) {
            window.alert('Browser not supported.');
            return;
        }
        // get default args
        window.fetch('getGetArgs', {method:'GET',cache:'no-cache'}).then(function(response) {
            console.log(response);
            return response.json();
            // convert base64 to arraybuffer
        }).then(function(json) {
            // error handling
            if (json.success === false) {
                throw new Error(json.msg);
            }
            // replace binary base64 data with ArrayBuffer. a other way to do this
            // is the reviver function of JSON.parse()
            recursiveBase64StrToArrayBuffer(json);
            return json;
            // create credentials
        }).then(function(getCredentialArgs) {
            return navigator.credentials.get(getCredentialArgs);
            // convert to base64
        }).then(function(cred) {
            return {
                id: cred.rawId ? arrayBufferToBase64(cred.rawId) : null,
                clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
                authenticatorData: cred.response.authenticatorData ? arrayBufferToBase64(cred.response.authenticatorData) : null,
                signature : cred.response.signature ? arrayBufferToBase64(cred.response.signature) : null
            };
            // transfer to server
        }).then(JSON.stringify).then(function(AuthenticatorAttestationResponse) {
            return window.fetch("processGet", {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token": $('input[name="_token"]').val()
                },
                method: "post",
                credentials: "same-origin",
                body: AuthenticatorAttestationResponse
            });
            // convert to json
        }).then(function(response) {
            return response.json();
            // analyze response
        }).then(function(json) {
            if (json.success) {
                window.alert(json.msg || 'login success');
                window.location.replace("https://squirrel-mcif.me/home");
            } else {
                throw new Error(json.msg);
            }
            // catch errors
        }).catch(function(err) {
            window.alert(err.message || 'unknown error occured');
        });
    }

    function recursiveBase64StrToArrayBuffer(obj) {
        let prefix = '?BINARY?B?';
        let suffix = '?=';
        if (typeof obj === 'object') {
            for (let key in obj) {
                if (typeof obj[key] === 'string') {
                    let str = obj[key];
                    if (str.substring(0, prefix.length) === prefix && str.substring(str.length - suffix.length) === suffix) {
                        str = str.substring(prefix.length, str.length - suffix.length);
                        let binary_string = window.atob(str);
                        let len = binary_string.length;
                        let bytes = new Uint8Array(len);
                        for (var i = 0; i < len; i++)        {
                            bytes[i] = binary_string.charCodeAt(i);
                        }
                        obj[key] = bytes.buffer;
                    }
                } else {
                    recursiveBase64StrToArrayBuffer(obj[key]);
                }
            }
        }
    }
    /**
     * Convert a ArrayBuffer to Base64
     * @param {ArrayBuffer} buffer
     * @returns {String}
     */
    function arrayBufferToBase64(buffer) {
        var binary = '';
        var bytes = new Uint8Array(buffer);
        var len = bytes.byteLength;
        for (var i = 0; i < len; i++) {
            binary += String.fromCharCode( bytes[ i ] );
        }
        return window.btoa(binary);
    }
    /**
     * force https on load
     * @returns {undefined}
     */
    window.onload = function() {
        if (location.protocol !== 'https:' && location.host !== 'localhost') {
            location.href = location.href.replace('http', 'https');
        }
    }
</script>