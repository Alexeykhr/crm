@extends('layouts.app')

@section('title', 'Перегляд ролі')

@section('content')

    <roles-page i-user="{{ json_encode($me) }}"
    ></roles-page>

@endsection
