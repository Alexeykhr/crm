@extends('layouts.app')

@section('title', 'Створення ролі')

@section('content')

    <roles-page i-user="{{ json_encode($me) }}"
    ></roles-page>

@endsection
