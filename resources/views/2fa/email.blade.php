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
                 To set up e-mail authentication press the button below.<br>
                 <a href="{{ route('email-confirm') }}" type="button" class="btn btn-primary">Add E-Mail Authentication</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection