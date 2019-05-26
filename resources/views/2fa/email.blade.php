@extends('layouts.general')

@section('my-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Set up E-Mail Authentication</div>
                <div class="card-body" style="text-align:center">
                    <p style="margin-bottom: -2%">Press the image to send the E-Mail code to {{\Auth::user()->email}}</p>
                    <a href="#" onClick="sendMail()"><img src="./img/mail.png" style="width: 50%"></a>
                    <p id="show" style="margin-top: -2%; display: none">E-Mail sent! Please type the code we sent you to  below:</p>
                    <form action="{{route('email-complete')}}" class="form-group form-inline" method="post" style>
                        {{csrf_field()}}
                        <input type="text" name="code" class="form-control col-md-5" placeholder="E-Mail Code">
                        <div class="col-md-2"></div>
                        <button type="submit" class="btn btn-primary col-md-5">Add E-Mail Authentication</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    function sendMail()
    {
        fetch(window.location.origin +  "/email-send").then(()=>{
            document.getElementById('show').style.display = "block";
        });
    }
</script>