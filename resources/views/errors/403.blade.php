@extends('errors::illustrated-layout')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'The page you are accessing is forbidden Jabroni!'))

@section('image')
    <img style="margin:auto" src="https://i.imgur.com/VTKXAqR.png"/>
@endsection
