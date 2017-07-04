@extends('layouts.app')

@section('title')
    Користувачі
@endsection

@section('content')

    <users data="{{ json_encode($users) }}"></users>

@endsection
