@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Role</th>
                                <th scope="col">Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>The Brains</td>
                                <td>Mac</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>The Looks</td>
                                <td>Dennis</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>The Muscle</td>
                                <td>Frank</td>
                            </tr>
                            <tr>
                                <th scope="row">4</th>
                                <td>The Useless Chick</td>
                                <td>Dee</td>
                            </tr>
                            <tr>
                                <th scope="row">5</th>
                                <td>WILDCARD!</td>
                                <td>Charlie</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
