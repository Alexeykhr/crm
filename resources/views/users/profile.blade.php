@extends('layouts.app')

@section('title'){{ $user->name }}@endsection

@section('content')

    <profile user="{{ json_encode($user) }}"
             role="{{ json_encode($user->role) }}"
             job="{{ json_encode($user->job) }}"
    ></profile>

@endsection
