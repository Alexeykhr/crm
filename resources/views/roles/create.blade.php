@extends('layouts.app')

@section('title', 'Створення ролі')

@section('content')

    <roles-create i-user="{{ json_encode($me) }}"
    ></roles-create>

@endsection
