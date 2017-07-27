@extends('layouts.app')

@section('title', 'Користувачі')

@section('content')

    <users i-user="{{ json_encode($me) }}"
           in-roles="{{ json_encode($roles) }}"
           in-jobs="{{ json_encode($jobs) }}"
    ></users>

@endsection
