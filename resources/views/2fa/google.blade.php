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
                <div class="card-header">Set up Google Authenticator</div>
                <div class="card-body" style="text-align:center">
                    Set up your two factor authentication by scanning the barcode below.<br>Alternatively, you can use the code {{ $secret }}
                    <div>
                        <img src="{{ $qrCode }}">
                    </div>
                    You must set up your Google Authenticator app before continuing.
                    <form action="{{route('google-complete')}}" class="form-group form-inline" method="post" style>
                        {{csrf_field()}}
                        <input type="hidden" name="secret" value="{{$secret}}">
                        <input type="number" name="code" class="form-control col-md-5" placeholder="Authenticator Code">
                        <div class="col-md-2"></div>
                        <button type="submit" class="btn btn-primary col-md-5">Verify Authenticator</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection