@extends('layouts.app')

@section('title'){{ $me->name }}@endsection

@section('content')

    <profile user="{{ isset($user) ? json_encode($user) : json_encode($me) }}"
             can-edit="{{ $canEdit }}"
    ></profile>

@endsection
