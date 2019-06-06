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
                    <div id="fido-div" class="form-group form-inline">
                        {{csrf_field()}}
                        <button type="button" onclick="newregistration()" class="btn btn-primary col-md-5 mx-auto mt-3">Register your key</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    /**
     * creates a new FIDO2 registration
     * @returns {undefined}
     */
    function newregistration() {
        if (!window.fetch || !navigator.credentials || !navigator.credentials.create) {
            window.alert('Browser not supported.');
            return;
        }
        // get default args
        window.fetch('getCreateArgs', {method:'GET',cache:'no-cache'}).then(function(response) {
            console.log(response)
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
            console.log(json);
            return json;
            // create credentials
        }).then(function(createCredentialArgs) {
            console.log(createCredentialArgs);
            return navigator.credentials.create(createCredentialArgs);
            // convert to base64
        }).then(function(cred) {
            return {
                clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
                attestationObject: cred.response.attestationObject ? arrayBufferToBase64(cred.response.attestationObject) : null
            };
            // transfer to server
        }).then(JSON.stringify).then(function(AuthenticatorAttestationResponse) {
            console.log(AuthenticatorAttestationResponse)

            //return window.fetch('processCreate', {method:'POST', body: AuthenticatorAttestationResponse, cache:'no-cache'});
            
            return window.fetch("processCreate", {
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
            // convert to JSON
        }).then(function(response) {
            console.log(response)
            return response.json();
            // analyze response
        }).then(function(json) {
            if (json.success) {
                window.alert(json.msg || 'registration success');
            } else {
                throw new Error(json.msg);
            }
            // catch errors
        }).catch(function(err) {
            window.alert(err.message || 'unknown error occured');
        });
    }
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
        window.fetch('server.php?fn=getGetArgs' + getGetParams(), {method:'GET',cache:'no-cache'}).then(function(response) {
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
            return window.fetch('server.php?fn=processGet' + getGetParams(), {method:'POST', body: AuthenticatorAttestationResponse, cache:'no-cache'});
            // convert to json
        }).then(function(response) {
            return response.json();
            // analyze response
        }).then(function(json) {
            if (json.success) {
                window.alert(json.msg || 'login success');
            } else {
                throw new Error(json.msg);
            }
            // catch errors
        }).catch(function(err) {
            window.alert(err.message || 'unknown error occured');
        });
    }
    function clearregistration() {
        window.fetch('server.php?fn=clearRegistrations' + getGetParams(), {method:'GET',cache:'no-cache'}).then(function(response) {
            return response.json();
        }).then(function(json) {
            if (json.success) {
                window.alert(json.msg);
            } else {
                throw new Error(json.msg);
            }
        }).catch(function(err) {
            window.alert(err.message || 'unknown error occured');
        });
    }
    /**
     * convert RFC 1342-like base64 strings to array buffer
     * @param {mixed} obj
     * @returns {undefined}
     */
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