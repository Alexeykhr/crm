@extends('layouts.app')

@section('title'){{ $me->name }}@endsection

@section('content')

    <profile i-user="{{ json_encode($me) }}"
             user="{{ isset($user) ? json_encode($user) : null }}"
             can-edit="{{ $canEdit }}"
    ></profile>

@endsection
