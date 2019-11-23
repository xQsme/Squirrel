@extends('layouts.general')

@section('my-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Google Authenticator</div>
                <div class="card-body" style="text-align:center">
                    <img src="/img/auth.png" style="width: 50%; padding-bottom:20px">
                    <form action="{{route('google-complete')}}" class="form-group form-inline" method="post" style>
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