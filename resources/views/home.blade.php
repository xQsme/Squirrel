@extends('layouts.general')

@section('my-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Source</th>
                                <th scope="col">IP</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\Auth::user()->logins as $login)
                                <tr>
                                    <td>{{$login->source}}</td>
                                    <td>{{$login->ip}}</td>
                                    <td>{{$login->created_at->format('d/m/Y')}}</td>
                                    <td>{{$login->created_at->format('H:i:s')}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
