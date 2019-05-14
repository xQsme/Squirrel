@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="panel-heading">
            @if(isset($title))
                <h1 style="text-align: center">{{$title}}</h1>
            @endif
        </div>

        @include('partials.flashmessages')

        @if(count($errors) > 0)
            @include('partials.errors')
        @endif


        <div class="panel-body">

            @yield('my-content')

        </div>

    </div>
@endsection