@extends('layouts.app')

@section('title', 'Перегляд користувача')

@section('h-btn-title', 'Всі працівники')
@section('h-btn-icon', 'arrow_back')
@section('h-btn-href', '/users')
@section('h-btn-class', 'md-raised')

@section('content')

    <users-page in-user="{{ json_encode($user) }}"
                action="{{ $action }}"
    ></users-page>

@endsection
