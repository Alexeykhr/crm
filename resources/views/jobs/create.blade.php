@extends('layouts.app')

@section('title', 'Створення посади')

@section('content')

    <jobs-create i-user="{{ json_encode($me) }}"
    ></jobs-create>

@endsection
