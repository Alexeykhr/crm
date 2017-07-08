@extends('layouts.app')

@section('title'){{ $user->name }}@endsection

@section('content')

    <profile user="{{ json_encode($user) }}"
             role="{{ json_encode($role) }}"
             job="{{ json_encode($job) }}"
             edit="{{ $edit }}"
    ></profile>

@endsection
