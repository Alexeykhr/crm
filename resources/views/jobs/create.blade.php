@extends('layouts.app')

@section('title', 'Створення посади')

@section('h-btn-title', 'Всі посади')
@section('h-btn-icon', 'arrow_back')
@section('h-btn-href', '/jobs')
@section('h-btn-class', 'md-raised')

@section('content')

    <jobs-create i-user="{{ json_encode($me) }}"
    ></jobs-create>

@endsection
