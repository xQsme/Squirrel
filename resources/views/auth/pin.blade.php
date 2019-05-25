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
                <div class="card-header">Pin Authentication</div>
                <div class="card-body" style="text-align:center">
                    <img src="./img/auth.png" style="width: 50%; padding-bottom:20px">
                    <form action="{{route('google-authenticate')}}" class="form-group form-inline" method="post" style>
                        {{csrf_field()}}
                        <input type="number" name="code" class="form-control col-md-5" placeholder="Authenticator Code">
                        <div class="col-md-2"></div>
                        <button type="submit" class="btn btn-primary col-md-5">Authenticate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection