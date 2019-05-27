@extends('layouts.general')

@section('my-content')
<div style="text-align: center; margin-top: -20px" class="col-md-12">
    <p style="font-size: 18px; margin-bottom: -10px">While we wait for <a href="https://www.grc.com/sqrl/sqrl.htm">SQRL</a> to release the 1.0 version, we've implemented a website and a <a href="https://play.google.com/store">mobile app</a> with different authentication factors to better understand how to achieve secure authentication.</p>
    <img src="./img/auth.png" style="width: 25%">
    <img src="./img/fido.png" style="width: 25%">
    <img src="./img/mail.png" style="width: 25%">
    <p style="font-size: 18px; margin-top: -5px">So far we've implemented authenticator support, we're working on FIDO support, and we also have e-mail authentication.</p>
    <p style="font-size: 18px; margin-top: -16px">Feel free to register and try out our website and app, we have taken every measure we could to ensure the user's security.</p>
</div>
@endsection
<script>
    window.onload=function()
    {
        replaceImage();
    }
    window.onresize = function(event)
    {
        replaceImage();
    }
    function replaceImage()
    {
        let image = document.getElementById("banner");
        if(image != null)
        {
            if(window.innerWidth/window.innerHeight > 1.5)
            {
                image.src = "./img/banner-slim.jpg";
            }
            else
            {
                image.src = "./img/banner.jpg";
            }
        }
    }
</script>