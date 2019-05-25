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
                <div class="card-header">Change Pin</div>
                <div class="card-body" style="text-align:center">
                    <img src="./img/lock.png" style="width: 50%; padding-bottom:20px">
                    <form action="{{route('save-pin')}}" class="form-group form-inline" method="post" style>
                        {{csrf_field()}}
                        <input type="number" name="pin" class="form-control col-md-5" placeholder="Pin Code">
                        <div class="col-md-2"></div>
                        <button type="submit" class="btn btn-primary col-md-5">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection