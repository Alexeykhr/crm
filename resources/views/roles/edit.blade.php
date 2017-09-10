@extends('layouts.app')

@section('title', 'Редагування ролі')

@section('content')

    <roles-page i-user="{{ json_encode($me) }}"
    ></roles-page>

@endsection
