@extends('layouts.app')

@section('content')
    <div class="container">

        @include('partials.flashmessages')

        <div class="panel-body">

            @yield('my-content')

        </div>

    </div>
@endsection