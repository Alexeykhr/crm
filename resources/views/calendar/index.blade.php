@extends('layouts.app')

@section('title', 'Дні народження')

@section('content')

    <calendar i-user="{{ json_encode($me) }}"
              in-users="{{ json_encode($users) }}"
    ></calendar>

@endsection

