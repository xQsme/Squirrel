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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection