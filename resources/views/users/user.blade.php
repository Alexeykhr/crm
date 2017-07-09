@extends('layouts.app')

@section('title'){{ $me->name }}@endsection

@section('content')

    <profile i-user="{{ json_encode($me) }}"
             edit="{{ $edit }}"
    ></profile>

@endsection
