@extends('layouts.general')

@section('my-content')
<ul style="text-align: center; margin-top: -20px" class="col-md-12">
    <H3>Security Measures:</H3>
    <li style="font-size: 18px; margin-bottom: -10px">HTTPS protocol with TLS encryption;</li>
    <li style="font-size: 18px; margin-bottom: -10px">HTTPS API calls for the mobile app;</li>
    <li style="font-size: 18px; margin-bottom: -10px">Salted Hash for passwords, PINs, and secret codes;</li>
    <li style="font-size: 18px; margin-bottom: -10px">Up to 4 authentication factors available;</li>
    <li style="font-size: 18px; margin-bottom: -10px">One time use for generated codes;</li>
    <li style="font-size: 18px; margin-bottom: -10px">Remembered sessions require PIN;</li>
    <li style="font-size: 18px; margin-bottom: -10px">FIDO authentication provides protection for <a href="https://us.norton.com/internetsecurity-wifi-what-is-a-man-in-the-middle-attack.html">MITM</a> attacks;</li>
    <li style="font-size: 18px; margin-bottom: -10px">SSH key required to access the server;</li>
    <li style="font-size: 18px; margin-bottom: -10px">Database hidden from public access (remote access disabled).</li>
</ul>
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
                image.src = "./img/banner-sec.jpg";
            }
            else
            {
                image.src = "./img/banner.jpg";
            }
        }
    }
</script>