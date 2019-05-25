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
                <div class="card-header">Set up E-Mail Authentication</div>
                <div class="card-body" style="text-align:center">
                    Please type the code we sent you to {{\Auth::user()->email}} below:<br>
                    <form action="{{route('email-complete')}}" class="form-group form-inline" method="post" style>
                        {{csrf_field()}}
                        <input type="text" name="code" class="form-control col-md-5">
                        <div class="col-md-2"></div>
                        <button type="submit" class="btn btn-primary col-md-5">Add E-Mail Authentication</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection